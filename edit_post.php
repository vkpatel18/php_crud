<?php
include 'config.php';
$post_id = $_GET['edit_id'];
$conn = mysqli_connect("localhost", "root", "", "php_crud") or die("Connection Failed.");
$sql = "SELECT * FROM `new_post` WHERE id= '$post_id'";
$result5 = mysqli_query($conn, $sql) or die("Query Unsuccessful.");

$title = $content = '';
$titleErr = $contentErr = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $title = $_POST['title'];
  $content = $_POST['content'];

  if (empty($title)) {
    $titleErr = "Title is required.";
  } 

  if (empty($content)) {
    $contentErr = "Content is required.";
  }

  if (empty($titleErr) && empty($contentErr)) {
    session_start();
    $user = $_SESSION['username'];
    $query = "SELECT * FROM registers WHERE `name` = '$user'";
    $result1 = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result1);
    $id = $row['id'];

    $post_title = mysqli_real_escape_string($conn, $title);
    $post_content = mysqli_real_escape_string($conn, $content);

    $post_id = $_POST['post_id'];

    $query = "UPDATE new_post SET title='$post_title', content='$post_content' WHERE id=$post_id AND user_id=$id";
    $result5 = mysqli_query($conn, $query);

    if ($result1) {
      header("Location: view_post.php?msg=ups");
      exit();
    } else {
      echo "Error: " . mysqli_error($conn);
    }
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>News Post</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="css/main.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
</head>
<body>
<h2 class="div">Edit Post</h2>
<center>
<form action="#" method="POST"> 
  <?php 
    if (mysqli_num_rows($result5) > 0) {
      while ($row5 = mysqli_fetch_assoc($result5)) {
  ?>
<input type="hidden" name="post_id" value="<?php echo $post_id; ?>"
  <div>
    <label for="fname">Title:</label><br><br>
    <input type="text" id="title" name="title" value="<?php echo $row5['title']; ?>"> 
    <br><br>
    <span class="text-danger"><?php echo $titleErr; ?></span>
  </div>
  <div>
    <label>Description:</label><br><br>
    <textarea name="content" id="content"><?php echo $row5['content']; ?></textarea> 
    <br><br>
    <span class="text-danger"><?php echo $contentErr; ?></span>
  </div>
  <input type="submit" id="submit" name="submit" value="Submit" class="btn btn-success btn-sm">
</form>
<?php
    }
}
?>
</center>
</body>
</html>
