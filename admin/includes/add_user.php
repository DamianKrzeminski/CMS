<?php
if(isset($_POST['create_user'])){
    $user_firstname = escape($_POST['user_firstname']);
    $user_lastname = escape($_POST['user_lastname']);
    $user_role = $_POST['user_role'];
    $username = escape($_POST['username']);
    $user_email = escape($_POST['user_email']);
    $user_password = escape($_POST['user_password']);
    $user_password = password_hash($user_password, PASSWORD_BCRYPT, array('cost'=>10));
    $stmt = prepareStmt("INSERT INTO users(user_firstname, user_lastname, user_role, username, user_email, user_password) VALUES(?,?,?,?,?,?)");
    mysqli_stmt_bind_param($stmt, 'ssssss', $user_firstname, $user_lastname, $user_role, $username, $user_email, $user_password);
    mysqli_stmt_execute($stmt);
    echo "User Created: " . " " . "<a href='users.php'> View Users </a>";
    mysqli_stmt_close($stmt);
}
?>
<form action="" method="post" enctype="multipart/form-data">
    <div class="form-group">
        <label for="user_firstname"> Firstname </label>
        <input class="form-control" type="text" name="user_firstname">
    </div>
    <div class="form-group">
        <label for="user_lastname"> Lastname </label>
        <input class="form-control" type="text" name="user_lastname">
    </div>
    <div class="form-group">
        <select name="user_role" id="">
        <option value="subscriber"> User Role </option>
        <option value="admin"> Admin </option>
        <option value="subscriber"> Subscriber </option>
        </select>
    </div>
    <div class="form-group">
        <label for="username"> Username </label>
        <input class="form-control" type="text" name="username">
    </div>
    <div class="form-group">
        <label for="user_email"> Email </label>
        <input class="form-control" type="text" name="user_email">
    </div>
    <div class="form-group">
        <label for="user_password"> Password </label>
        <input class="form-control" type="password" name="user_password">
    </div>
    <div class="form-group">
        <input class="btn btn-primary" type="submit" name="create_user" value="Add User">
    </div>
</form>