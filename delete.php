<?php 
    echo $stu_id = $_GET['delete_id'];
    
    include 'config.php';

    $sql = "DELETE FROM students WHERE id = {$stu_id}";
    $result = mysqli_query($conn,$sql) or die("Query Unsuccessful.");

    header("location: list.php?msg=del");
    mysqli_close($conn);
?>

