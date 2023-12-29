<?php
session_start();
include 'config.php';

// for personally record show.
$user = $_SESSION['username'];
$query = mysqli_query($conn, "select * from registers where `name` = '$user'");
$row = mysqli_fetch_array($query);
$id = $row['id'];

// pagination.
$limit = 3;

if(isset($_GET['page'])){
    $page = $_GET['page'];
    if($page <= 0){
        $page = 1;
    }
}else{
    $page = 1;
}
$offset = ($page-1) * $limit;
$user = $_SESSION['user_id'];
$sql = "SELECT * FROM new_post WHERE user_id = $user LIMIT {$offset},{$limit}";
$result = mysqli_query($conn,$sql) or die("SQL Query Failed.");

// searching.
if(isset($_POST['search'])){
    $search = $_POST['search'];
    $conn = mysqli_connect("localhost","root","","php_crud") or die("Connection Failed.");
    $sql = "SELECT * FROM new_post WHERE user_id = '$id' AND title LIKE '%".$search."%' OR content LIKE '%".$search."%'";
    $result = mysqli_query($conn,$sql);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="css/main.css">
    <title>User Post</title>
</head>
<body>
    
<?php
// success message.
if(isset($_GET['msg']) AND $_GET['msg'] == 'ins'){
    echo '<div class="alert alert-success" role="alert">
        Record Inserted Successfully..!!
        </div>';
}

if (isset($_GET['msg']) and $_GET['msg'] == 'ups') {
    echo '<div class="alert alert-primary" role="alert">
            Record Updated Successfully..!!
            </div>';
}
if (isset($_GET['msg']) and $_GET['msg'] == 'del') {
    echo '<div class="alert alert-danger" role="alert">
                    Record Deleted Successfully..!!
                </div>';
}
?>

    <div class="container">
        <h2 class="div">User Post</h2>
        <a class="add btn btn-info"><?php echo $_SESSION['username'] ?></a>
        <a href="logout.php" class="add btn btn-danger" style="margin-right: 9px;">Logout</a>
        <a href="list.php" class="add btn btn-warning" style="margin-right: 9px;">View List</a>
        <a href="add_post.php" class="add btn btn-dark" style="margin-right: 9px;">Add Post</a>

        <form method="post">
            Search: <input type="text" name="search" placeholder="Enter a keyword"><br />
            <input type="submit" value="search" class="btn btn-primary btn-sm" style="margin-left: 243px; margin-top: -57px;">
        </form>

        <table class="table table-striped">
            <thead>
				<tr>
					<th>Id</th>
					<th>Title</th>
					<th>Content</th>
					<th>Image</th>
					<th>Action</th>
				</tr>
            </thead>
            <tbody>
                <?php if(mysqli_num_rows($result) > 0){ ?>
                <?php while ($row = mysqli_fetch_assoc($result)) {
                ?>
					<tr>
						<td><?php echo $row['id'] ?></td>
						<td><?php echo $row['title'] ?></td>
						<td><?php echo $row['content'] ?></td>
						<td>
                            <img src="<?php echo "upload/".$row['image']; ?>" height="60" width="80" alt="image">
                        </td>
						<td>
							<a href="edit_post.php?edit_id=<?php echo $row['id']; ?>" class="btn btn-outline-primary">Edit</a>
							<a href="delete_post.php?delete_id=<?php echo $row['id']; ?>" id="delete-btn" class="btn btn-outline-danger">Delete</a>
						</td>
					</tr>
				<?php } ?>
                <?php } else { ?>
                    <tr style="text-align:center;">
                        <td colspan="5">No record Found !</td>
                    </tr>
                    <?php } ?>
            </tbody>
        </table>
    </div>

    
    <?php 
    // for pagination.
    $user = $_SESSION['user_id'];
    $pagination = "SELECT * FROM new_post WHERE user_id = $user";
    $result3 = mysqli_query($conn,$pagination) or die("SQL Query Failed.");

    if(mysqli_num_rows($result3) > 0){
        $total_records = mysqli_num_rows($result3);
        $total_page = ceil($total_records/$limit);

?>

    <div class="pagination">
        <ul style="margin-left: 1230px;">
            <?php if($page > 1){ ?>
                <a href="view_post.php?page=<?php echo $page-1 ?>" class="btn btn-outline-warning">Previous</a>
            <?php } ?>

            <?php for ($i=1; $i <=$total_page; $i++) { ?>
                <?php
                if ($i == $page) { ?>
                    <a href='view_post.php?page=<?php echo $i; ?>' class="btn btn-warning">
                        <strong><?php echo $i; ?></strong>
                    </a>
                <?php } else { ?>
                    <a href='view_post.php?page=<?php echo $i; ?>' class="btn btn-outline-warning"><?php echo $i; ?></a>
                <?php } ?>
            <?php } ?>

            <?php if($page < $total_page){ ?>
            <a href="view_post.php?page=<?php echo $page+1 ?>" class="btn btn-outline-warning">Next</a>
            <?php } ?>
        </ul>
    </div>

<?php } ?>

</body>
</html>

<?php
mysqli_close($conn);
?>