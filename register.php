<?php
session_start();
$name = $email = $password = $confirm_password = $user_type = '';
$nameErr = $emailErr = $passwordErr = $confirmPasswordErr = $user_typeErr = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $user_type = $_POST['user_type'];
    
    // for validation errors.
    if (empty($name)) {
        $nameErr = "Name is required.";
    }

    if (empty($email)) {
        $emailErr = "Email is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $emailErr = "Invalid email format.";
    } 

    if (empty($password)) {
        $passwordErr = "Password is required.";
    } elseif (strlen($password) < 6) {
        $passwordErr = "Password must be at least 6 characters long.";
    }

    if ($password !== $confirm_password) {
        $confirmPasswordErr = "Passwords do not match.";
    }

    if (empty($user_type)) {
        $user_typeErr = "User type is required.";
    }

    // if field are filled than.
    if (empty($nameErr) && empty($emailErr) && empty($passwordErr) && empty($confirmPasswordErr && empty($user_typeErr))) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // for email already exists.
        $conn = mysqli_connect("localhost","root","","php_crud") or die("Connection Failed.");
        $sql = "SELECT * FROM registers WHERE email='$email'";
        $result = mysqli_query($conn,$sql);
        $present = mysqli_num_rows($result);
        if($present > 0){
            $emailErr = "email already exists.";
        }else {   
            $sql = "INSERT INTO `registers`(`name`, `email`, `password`, `user_type`) VALUES ('$name', '$email', '$hashed_password', '$user_type')";
            $result = mysqli_query($conn, $sql) or die("Query Unsuccessful.");
            header('location: list.php');
        }
        mysqli_close($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>
    <title>Register User</title>
    <link rel="stylesheet" href="css/main.css">
</head>
<body>
<h2 class="div" style="margin-right: 110px;">Register User</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" class="form" method="POST">
        <div class="text-center ml-2">
            <div class="col-md-4">
                <div class="form-group">
                    <label for="exampleInputPassword1" class="form-label mt-4">Name:</label>
                    <input type="text" class="form-control" name="name" placeholder="Enter Name" value="<?php echo htmlspecialchars($name); ?>">
                    <span class="text-danger"><?php echo $nameErr; ?></span>
                </div>
                <div class="form-group">
                    <label for="exampleInputPassword1" class="form-label mt-4">Email:</label>
                    <input type="text" class="form-control" name="email" placeholder="Enter Email Address" value="<?php echo htmlspecialchars($email); ?>">
                    <span class="text-danger"><?php echo $emailErr; ?></span>
                </div>
                
                <div class="form-group">
                    <label for="exampleInputPassword1"  class="form-label mt-4">Password:</label>
                    <input type="password" class="form-control" name="password" placeholder="Enter Password">
                    <span class="text-danger"><?php echo $passwordErr; ?></span>
                </div><br>
                
                <div class="form-group">
                    <label for="exampleInputPassword1" class="form-label mt-4">Confirm Password:</label>
                    <input type="password" class="form-control" name="confirm_password" placeholder="Enter Confirm Password">
                    <span class="text-danger"><?php echo $confirmPasswordErr; ?></span>
                </div><br>

                <div class="form-group">
                    <label for="exampleInputPassword1" class="form-label mt-4">User Type:</label>
                    <select name="user_type" id="" class="form-control">
                        <option value="">Select</option>
                        <option value="admin" <?php if ($user_type === 'admin') echo 'selected'; ?>>Admin</option>
                        <option value="user" <?php if ($user_type === 'user') echo 'selected'; ?>>User</option>
                    </select>
                    <span class="text-danger"><?php echo $user_typeErr; ?></span>
                </div><br>

                <button type="submit" name="register-btn" id="register-btn" class="btn btn-success">Register</button>
            </div>
        </div>
    </form>
</body>
</html>

