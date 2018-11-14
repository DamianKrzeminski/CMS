<?php include "includes/admin_header.php";?>
<?php
$username = getUsername();
$select_user_profile_query = query("SELECT * FROM users WHERE username = '{$username}'");
while($row = fetchRecords($select_user_profile_query)){
    $user_id = $row['user_id'];
    $username = $row['username'];
    $user_password = $row['user_password'];
    $user_firstname = $row['user_firstname'];
    $user_lastname = $row['user_lastname'];
    $user_email = $row['user_email'];
    $user_role = $row['user_role'];
}
?>
<?php
if(isset($_POST['edit_user'])){
    $new_user_firstname = escape($_POST['user_firstname']);
    $new_user_lastname = escape($_POST['user_lastname']);
    $new_username = escape($_POST['username']);
    $user_email = escape($_POST['user_email']);
    $user_password = escape($_POST['user_password']);
    if(!empty($user_password)){
        $stmt = prepareStmt("SELECT user_password FROM users WHERE user_id=?");
        mysqli_stmt_bind_param($stmt, 'i', $the_user_id);
        mysqli_stmt_execute($stmt);
        $get_user_query = mysqli_stmt_get_result($stmt);
        $row = fetchRecords($get_user_query);
        $db_user_password = $row['user_password'];
        if($db_user_password != $user_password){
            $hash_user_password = password_hash($user_password, PASSWORD_BCRYPT, array('cost'=>10));
        }
    $stmt = prepareStmt("UPDATE users SET username = ?, user_password = ?, user_firstname = ?, user_lastname = ?, user_email = ? WHERE username = ?");
    mysqli_stmt_bind_param($stmt, 'ssssss', $new_username, $hash_user_password, $new_user_firstname, $new_user_lastname, $user_email, $username);
    mysqli_stmt_execute($stmt);
    $_SESSION['username'] = $new_username;
    $_SESSION['firstname'] = $new_user_firstname;
    $_SESSION['lastname'] = $new_user_lastname;
    mysqli_stmt_close($stmt);
    redirect("profile.php");
    }
}
?>
<div id="wrapper">
    <!-- Navigation -->
    <?php include "includes/admin_navigation.php";?>
    <div id="page-wrapper">
        <div class="container-fluid">
            <!-- Page Heading -->
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">
                        Profile
                        <?php echo getUsername()?>
                    </h1>
                    <form action="" method="post" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="user_firstname"> Firstname </label>
                            <input class="form-control" type="text" name="user_firstname" value="<?php echo $user_firstname;?>">
                        </div>
                        <div class="form-group">
                            <label for="user_lastname"> Lastname </label>
                            <input class="form-control" type="text" name="user_lastname" value="<?php echo $user_lastname;?>">
                        </div>
                        <div class="form-group">
                            <label for="username"> Username </label>
                            <input class="form-control" type="text" name="username" value="<?php echo $username;?>">
                        </div>
                        <div class="form-group">
                            <label for="user_email"> Email </label>
                            <input class="form-control" type="text" name="user_email" value="<?php echo $user_email;?>">
                        </div>
                        <div class="form-group">
                            <label for="user_password"> Password </label>
                            <input autocomplete="off" class="form-control" type="password" name="user_password">
                        </div>
                        <div class="form-group">
                            <input class="btn btn-primary" type="submit" name="edit_user" value="Update Profile">
                        </div>
                    </form>
                </div>
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </div> 
<?php include "includes/admin_footer.php";?>