<?php 
    $conn = mysqli_connect("localhost","root","","php_crud") or die("Connection Failed.");
    $sql = "SELECT students.id,students.name,students.address,students.class,students.phone FROM students JOIN studentsclass WHERE students.class = studentsclass.id";
    $result = mysqli_query($conn,$sql) or die("Query Unsuccessful.");  
?>