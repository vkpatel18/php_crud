<?php
include 'config.php';

$title = $content = $image = '';
$titleErr = $contentErr = $imageErr = '';


if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $title = $_POST['title'];
  $content = $_POST['content'];
  $image = $_FILES['image']['name'];

  if (empty($title)) {
    $titleErr = "Title is required.";
  } 

  if (empty($content)) {
    $contentErr = "Content is required.";
  }

  // if (empty($image)) {
  //   $imageErr = "Image is required.";
  // }

  if (empty($titleErr) && empty($contentErr)  && empty($imageErr)) {
    session_start();
    $user = $_SESSION['username'];
    $query = "SELECT * FROM registers WHERE `name` = '$user'";
    $result1 = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result1);
    echo $id = $row['id'];

    $post_title = mysqli_real_escape_string($conn, $title);
    $post_content = mysqli_real_escape_string($conn, $content);
    $post_image = mysqli_real_escape_string($conn, $image);

    //image uplaod.
    $img_name = $_FILES['image']['name'];
    move_uploaded_file($_FILES['image']['tmp_name'],'upload/'.$img_name); 

    $query = "INSERT INTO new_post(`title`, `content`, `user_id`,`image`) VALUES ('$post_title', '$post_content', '$id', '$post_image')";
    $result1 = mysqli_query($conn, $query);

    if ($result1) {
      header("Location: view_post.php?msg=ins");
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
<h2 class="div">Create Post</h2>
<center>
<form action="#" method="POST" enctype="multipart/form-data"> 
  <div>
    <label for="fname">Title:</label><br><br>
    <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($title); ?>"> 
    <br><br>
    <span class="text-danger"><?php echo $titleErr; ?></span>
  </div>
  <div>
    <label>Description:</label><br><br>
    <textarea name="content" id="content"><?php echo htmlspecialchars($content); ?></textarea> 
    <br><br>
    <span class="text-danger"><?php echo $contentErr; ?></span>
  </div>
  <div>
    <label for="image">Image:</label><br><br>
    <input type="file" name="image"><br><br>
    <span class="text-danger"><?php echo $imageErr; ?></span>
    <br><br>
  </div>
  <input type="submit" id="submit" name="submit" value="Submit" class="btn btn-success btn-sm">
</form>
</center>
</body>
</html>
