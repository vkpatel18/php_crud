<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header("Location: list.php"); 
    exit;
}

$con = mysqli_connect("localhost", "root", "", "php_crud") or die("Connection Failed.");
$email = $password = "";
$emailErr = $passwordErr = "";

if (isset($_POST['login'])) {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (empty($email)) {
        $emailErr = "Email is required.";
    }

    if (empty($password)) {
        $passwordErr = "Password is required.";
    }

    if (empty($emailErr) && empty($passwordErr)) {
        $sql = "SELECT * FROM `registers` WHERE email='$email'";
        $result = mysqli_query($con, $sql) or die("Query Unsuccessful.");
        $row = mysqli_fetch_assoc($result);

        if ($row && password_verify($password, $row['password'])) {
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $row['name'];
            $_SESSION['role'] = $row['user_type'];
            header("location: list.php");
        } else {
            echo "Credentials do not match.";
        }
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
    <title>Login User</title>
    <link rel="stylesheet" href="css/main.css">
</head>
<body>
<h2 class="div" style="margin-right: 110px;">Login User</h2>
    <form class="form" method="POST">
        <div class="text-center ml-2">
            <div class="col-md-4">
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

                <a href="register.php" class="btn btn-success">Register</a>
                <button type="submit" name="login" class="btn btn-primary">Login</button>
            </div>
        </div>
    </form>
</body>
</html>
