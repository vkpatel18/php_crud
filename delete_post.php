<?php 
    echo $post_id = $_GET['delete_id'];
    
    include 'config.php';

    $sql = "DELETE FROM new_post WHERE id = {$post_id}";
    $result = mysqli_query($conn,$sql) or die("Query Unsuccessful.");

    header("location: view_post.php?msg=del");
    mysqli_close($conn);
?>

