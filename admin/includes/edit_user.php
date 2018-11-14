<?php
if(isset($_GET['edit_user'])){
    $the_user_id = escape($_GET['edit_user']);
    $stmt = prepareStmt("SELECT * FROM users WHERE user_id=?");
    mysqli_stmt_bind_param($stmt, 'i', $the_user_id);
    mysqli_stmt_execute($stmt);
    $select_users_query = mysqli_stmt_get_result($stmt);
    while($row = mysqli_fetch_assoc($select_users_query)){
        $user_id = $row['user_id'];
        $username = $row['username'];
        $user_password = $row['user_password'];
        $user_firstname = $row['user_firstname'];
        $user_lastname = $row['user_lastname'];
        $user_email = $row['user_email'];
        $user_image = $row['user_image'];
        $user_role = $row['user_role'];
    }
    mysqli_stmt_close($stmt);
    if(isset($_POST['edit_user'])){
        $user_firstname = escape($_POST['user_firstname']);
        $user_lastname = escape($_POST['user_lastname']);
        $user_role = $_POST['user_role'];
        $username = escape($_POST['username']);
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
            
            $stmt = prepareStmt("UPDATE users SET username = ?, user_password = ?, user_firstname = ?, user_lastname = ?, user_email = ?, user_role = ? WHERE user_id = ?");
            mysqli_stmt_bind_param($stmt, 'ssssssi', $username, $hash_user_password, $user_firstname, $user_lastname, $user_email, $user_role, $the_user_id);
            mysqli_stmt_execute($stmt);
            echo "User Updated " . "<a href='users.php'>View Users?</a>";
            mysqli_stmt_close($stmt);
        }
    }   
}
?>
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
        <select name="user_role" id="">
        <option value="<?php echo $user_role;?>"><?php echo $user_role;?></option>
        <?php
            if($user_role == 'admin'){
                echo "<option value='subscriber'> subscriber </option>";
            }else{
                echo "<option value='admin'> admin </option>";
            }
        ?>
        </select>
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
        <input class="btn btn-primary" type="submit" name="edit_user" value="Update User">
    </div>
</form>