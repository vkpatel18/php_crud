<?php
include 'config.php';
$name = $address = $class = $phone = '';
$nameErr = $addressErr = $classErr = $phoneErr = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $address = $_POST['address'];
    $class = $_POST['class'];
    $phone = $_POST['phone'];

    if (empty($name)) {
        $nameErr = "Name is required.";
    }

    if (empty($address)) {
        $addressErr = "Address is required.";
    }

    if (empty($class)) {
        $classErr = "Class is required.";
    }

    if (empty($phone)) {
        $phoneErr = "Phone number is required.";
    }

    if (empty($nameErr) && empty($addressErr) && empty($classErr) && empty($phoneErr)) {
        $stu_name = $_POST['name'];
        $stu_address = $_POST['address'];
        $stu_class = $_POST['class'];
        $stu_phone = $_POST['phone'];

        // for personally record show.
        session_start();
        $user = $_SESSION['username'];
        $query = "SELECT * FROM registers WHERE `name` = '$user'";
        $result1 = mysqli_query($conn, $query);
        $row = mysqli_fetch_assoc($result1);
        $id = $row['id'];
    
        $conn = mysqli_connect("localhost","root","","php_crud") or die("Connection Failed.");
        $sql = "INSERT INTO students(name,address,class,phone,user_id) VALUES('{$stu_name}','{$stu_address}','{$stu_class}','{$stu_phone}',{$id})";
        $result = mysqli_query($conn,$sql) or die("Query Unsuccessful.");
    
        header("Location: list.php?msg=ins");
    
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
    <title>Create User</title>
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
</head>
<body>
<h2 class="div">Create User</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" class="form" method="POST">
        <div class="text-center ml-2">
            <div class="col-md-4">
                <div class="form-group">
                    <label for="exampleInputPassword1" class="form-label mt-4">Name:</label>
                    <input type="text" class="form-control" name="name" placeholder="Enter Name" value="<?php echo htmlspecialchars($name); ?>">
                    <span class="text-danger"><?php echo $nameErr; ?></span>
                </div>
                <div class="form-group">
                    <label for="exampleInputPassword1" class="form-label mt-4">Address:</label>
                    <input type="text" class="form-control" name="address" placeholder="Enter Address" value="<?php echo htmlspecialchars($address); ?>">
                    <span class="text-danger"><?php echo $addressErr; ?></span>
                </div>
                <div class="form-group">
                    <label for="exampleInputPassword1" class="form-label mt-4">Class:</label>
                    <select id="" name="class" class="form-control">
                        <option value="">Select Class</option>
                        <?php 
                            $conn = mysqli_connect("localhost","root","","php_crud") or die("Connection Failed.");
                            $sql = "SELECT * FROM studentsclass";
                            $result = mysqli_query($conn,$sql);
                            while($row = mysqli_fetch_assoc($result)) {
                                $classId = $row['id'];
                                $className = $row['name'];
                                $selected = ($classId == $class) ? 'selected' : '';
                        ?>
                        <option value="<?php echo $classId; ?>" <?php echo $selected; ?>><?php echo $className; ?></option>
                        <?php } ?>
                    </select>
                    <span class="text-danger"><?php echo $classErr; ?></span>
                </div>
                <div class="form-group">
                    <label for="exampleInputPassword1"  class="form-label mt-4">Phone:</label>
                    <input type="number" class="form-control" name="phone" placeholder="Enter Mobile Number" value="<?php echo htmlspecialchars($phone); ?>">
                    <span class="text-danger"><?php echo $phoneErr; ?></span>
                </div><br>

                <button type="submit" id="create-btn" class="btn btn-success">Submit</button>
            </div>
        </div>
    </form>
</body>
</html>

