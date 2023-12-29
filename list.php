<?php
include 'config.php';
session_start();

// not go to login page if not set.
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// for personally record show.
$user = $_SESSION['username'];
$query = mysqli_query($conn, "select * from registers where `name` = '$user'");
$rowr = mysqli_fetch_array($query);
$id = $rowr['id'];
$role = $rowr['user_type'];

// pagination.
$limit = 3;

if (isset($_GET['page'])) {
    $page = $_GET['page'];
    if ($page <= 0) {
        $page = 1;
    }
} else {
    $page = 1;
}

$offset = ($page - 1) * $limit;
$sql = "SELECT * FROM students WHERE user_id = '$id' LIMIT {$offset},{$limit}";

if ($role == 'admin') {
    $sql = "SELECT * FROM students LIMIT {$offset},{$limit}";
}
$result = mysqli_query($conn, $sql) or die("SQL Query Failed.");

// searching.
if (isset($_POST['search'])) {
    $search = $_POST['search'];
    $conn = mysqli_connect("localhost", "root", "", "php_crud") or die("Connection Failed.");
    $sql = "SELECT * FROM students WHERE user_id = '$id' AND name LIKE '%" . $search . "%' OR address LIKE '%" . $search . "%'";
    $result = mysqli_query($conn, $sql);
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
    <title>All Records</title>
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
        <h2 class="div">All Records</h2>
        <a class="add btn btn-info"><?php echo $_SESSION['username'] ?></a>
        <a href="logout.php" class="add btn btn-danger" style="margin-right: 9px;">Logout</a>
        <a href="view_post.php" class="add btn btn-warning" style="margin-right: 9px;">View Post</a>
        <a href="create.php" class="add btn btn-dark" style="margin-right: 9px;">Add User</a>

        <form method="post">
            Search: <input type="text" name="search" placeholder="Enter a keyword"><br />
            <input type="submit" value="search" class="btn btn-primary btn-sm" style="margin-left: 243px; margin-top: -57px;">
        </form>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Name</th>
                    <th>Address</th>
                    <th>Class</th>
                    <th>Phone</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($result) > 0) {?>
                <?php while ($row = mysqli_fetch_assoc($result)) {
    ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['name']; ?></td>
                    <td><?php echo $row['address']; ?></td>
                    <td><?php echo $row['class']; ?></td>
                    <td><?php echo $row['phone']; ?></td>
                    <td>
                        <a href="edit.php?edit_id=<?php echo $row['id']; ?>" class="btn btn-outline-primary">Edit</a>
                        <a href="delete.php?delete_id=<?php echo $row['id']; ?>" id="delete-btn" class="btn btn-outline-danger">Delete</a>
                    </td>
                </tr>
                <?php }?>
                <?php } else {?>
                    <tr style="text-align: center;">
                        <td colspan="6">No record Found !</td>
                    </tr>
                    <?php }?>
            </tbody>
        </table>
    </div>

<?php
// for pagination.
$pagination = "SELECT * FROM students WHERE user_id = '$id'";

if ($role == 'admin') {
    $pagination = "SELECT * FROM students";
}

$result1 = mysqli_query($conn, $pagination) or die("SQL Query Failed.");

if (mysqli_num_rows($result1) > 0) {
    $total_records = mysqli_num_rows($result1);
    $total_page = ceil($total_records / $limit);
    ?>

    <div class="pagination">
        <ul style="margin-left: 1195px;">
            <?php if ($page > 1) {?>
                <a href="list.php?page=<?php echo $page - 1 ?>" class="btn btn-outline-info">Previous</a>
            <?php }?>

            <?php for ($i = 1; $i <= $total_page; $i++) {?>
                <?php
if ($i == $page) {?>
                    <a href='list.php?page=<?php echo $i; ?>' class="btn btn-info">
                        <strong><?php echo $i; ?></strong>
                    </a>
                <?php } else {?>
                    <a href='list.php?page=<?php echo $i; ?>' class="btn btn-outline-info"><?php echo $i; ?></a>
                <?php }?>
            <?php }?>

            <?php if ($page < $total_page) {?>
            <a href="list.php?page=<?php echo $page + 1 ?>" class="btn btn-outline-info">Next</a>
            <?php }?>
        </ul>
    </div>

<?php }?>

</body>
</html>

<?php
mysqli_close($conn);
?>

