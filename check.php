<?php 
    if (empty($emailErr) && empty($passwordErr)) {
        $sql = "SELECT * FROM `registers` WHERE email='$email'";
        $result = mysqli_query($con, $sql) or die("Query Unsuccessful.");
        $row = mysqli_fetch_assoc($result);
        
        if($row['usertype'] == 'admin'){
            echo 'I am admin';
            // You can add admin-specific actions here
        } else if($row['usertype'] == 'user'){
            echo 'I am user';
            // You can add user-specific actions here
        } else {
            echo "Invalid user type";
        }
    
        if ($row && password_verify($password, $row['password'])) {
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $row['name'];
            header("location: list.php");
        } else {
            echo "Credentials do not match.";
        }
    }
?>

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'user') NOT NULL
);

CREATE TABLE records (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    data TEXT NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id)
);


CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'user') NOT NULL
);

CREATE TABLE records (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    data TEXT NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id)
);


<?php 
    session_start();
    
    // Connect to the database
    $db = new mysqli("localhost", "username", "password", "your_database");
    
    // Check for a valid database connection
    if ($db->connect_error) {
        die("Connection failed: " . $db->connect_error);
    }
    
    // Check if the user is logged in
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit();
    }
    
    // Retrieve user's role from the database
    $user_id = $_SESSION['user_id'];
    $query = "SELECT role FROM users WHERE id = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($user_role);
    $stmt->fetch();
    $stmt->close();
    
    // Implement authorization checks
    if ($user_role === 'admin') {
        // Admin has access to all records
        $records_query = "SELECT * FROM records";
    } else {
        // Regular user can only access their records
        $records_query = "SELECT * FROM records WHERE user_id = ?";
    }
    
    // Fetch and display records based on the query
    $stmt = $db->prepare($records_query);
    if ($user_role !== 'admin') {
        $stmt->bind_param("i", $user_id);
    }
    $stmt->execute();
    $result = $stmt->get_result();
    
    while ($row = $result->fetch_assoc()) {
        // Display record data
        echo $row['data'] . "<br>";
    }
    
    $stmt->close();
    $db->close();  
?>