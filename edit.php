<?php
include 'config.php';

$stu_id = $_GET['edit_id'];
$conn = mysqli_connect("localhost", "root", "", "php_crud") or die("Connection Failed.");
$sql = "SELECT * FROM `students` WHERE id= '$stu_id'";
$result = mysqli_query($conn, $sql) or die("Query Unsuccessful.");

$name = $address = $class = $phone = '';
$nameErr = $addressErr = $classErr = $phoneErr = '';

if (isset($_POST['update'])) {
    $stu_id = $_POST['id'];
    $stu_name = $_POST['name'];
    $stu_address = $_POST['address'];
    $stu_class = $_POST['class'];
    $stu_phone = $_POST['phone'];

    if (empty($stu_name)) {
        $nameErr = "Name is required.";
    }

    if (empty($stu_address)) {
        $addressErr = "Address is required.";
    }

    if (empty($stu_class)) {
        $classErr = "Class is required.";
    }

    if (empty($stu_phone)) {
        $phoneErr = "Phone number is required.";
    }

    if (empty($nameErr) && empty($addressErr) && empty($classErr) && empty($phoneErr)) {
        $conn = mysqli_connect("localhost", "root", "", "php_crud") or die("Connection Failed.");
        $sql = "UPDATE students SET name = '{$stu_name}', address = '{$stu_address}', class = '{$stu_class}', phone = '{$stu_phone}' WHERE id = {$stu_id}";
        $result = mysqli_query($conn, $sql) or die("Query Unsuccessful.");

        header("Location: list.php?msg=ups");
        mysqli_close($conn);
    }
}

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>
    <title>Edit User</title>
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
</head>
<body>
<h2 class="div">Edit User</h2>
    <form action="" class="form" method="POST" enctype="multipart/form-data">
        <div class="text-center ml-2">
            <div class="col-md-4">
                <div class="form-group">
                    <label for="exampleInputPassword1" class="form-label mt-4">Name:</label>
                    <input type="hidden" class="form-control" name="id" value="<?php echo $row['id']; ?>">
                    <input type="text" class="form-control" name="name" value="<?php echo $row['name']; ?>" placeholder="Enter Name">
                    <span class="text-danger"><?php echo $nameErr; ?></span>
                </div>
                <div class="form-group">
                    <label for="exampleInputPassword1" class="form-label mt-4">Address:</label>
                    <input type="text" class="form-control" name="address" value="<?php echo $row['address']; ?>" placeholder="Enter Address">
                    <span class="text-danger"><?php echo $addressErr; ?></span>
                </div>
                <div class="form-group">
                    <label for="exampleInputPassword1" class="form-label mt-4">Class:</label>
                    <?php
                    $sql1 = "SELECT * FROM `studentsclass`";
                    $result1 = mysqli_query($conn, $sql1) or die("Query Unsuccessful.");

                    if (mysqli_num_rows($result1) > 0) {
                        echo "<select name='class' class='form-control'>";
                        while ($row1 = mysqli_fetch_assoc($result1)) {
                            if ($row['class'] == $row1['id']) {
                                $select = "selected";
                            } else {
                                $select = "";
                            }
                            echo "<option {$select} value='{$row1['id']}'>{$row1['name']}</option>";
                        }
                        echo "</select>";
                    }
                    ?>
                    <span class="text-danger"><?php echo $classErr; ?></span>
                </div>
                <div class="form-group">
                    <label class="form-label mt-4">Phone:</label>
                    <input type="text" class="form-control" name="phone" value="<?php echo $row['phone']; ?>" placeholder="Enter Phone Number">
                    <span class="text-danger"><?php echo $phoneErr; ?></span>
                </div><br>

                <button type="submit" name="update" id="updated-btn" class="btn btn-success">Update</button>

            </div>
        </div>
    </form>
    <?php
    }
}
?>
</body>
</html>

