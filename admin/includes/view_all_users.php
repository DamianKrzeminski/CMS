<table class="table table-bordered table-hover">
    <thead>
        <tr>
            <th> Id </th>
            <th> Username </th>
            <th> Firstname </th>
            <th> Lastname </th>
            <th> Email </th>
            <th> Role </th>
            <th> Admin </th>
            <th> Subscriber </th>
            <th> Edit </th>
            <th> Delete </th>
        </tr>
    </thead>
    <tbody>
        <?php
        $select_users = query("SELECT * FROM users");
        while($row = mysqli_fetch_assoc($select_users)){
            $user_id = $row['user_id'];
            $username = $row['username'];
            $user_password = $row['user_password'];
            $user_firstname = $row['user_firstname'];
            $user_lastname = $row['user_lastname'];
            $user_email = $row['user_email'];
            $user_image = $row['user_image'];
            $user_role = $row['user_role'];
            echo "<tr>";
                echo "<td> $user_id </td>";
                echo "<td> $username </td>";
                echo "<td> $user_firstname </td>";
                echo "<td> $user_lastname </td>";;
                echo "<td> $user_email </td>";
                echo "<td> $user_role </td>";
                ?>
                <form action="" method="post">
                    <input type="hidden" name="user_id" value="<?php echo $user_id;?>">
                    <td> <input class="btn btn-primary" type="submit" name="change_to_admin" value="Admin"> </td>
                    <input type="hidden" name="user_id" value="<?php echo $user_id;?>">
                    <td> <input class="btn btn-primary" type="submit" name="change_to_sub" value="Subscriber"> </td>
                <?php
                echo "<td><a class='btn btn-info' href='users.php?source=edit_user&edit_user={$user_id}'> Edit </a></td>";   
                ?>
                    <input type="hidden" name="user_id" value="<?php echo $user_id;?>">
                    <td> <input class="btn btn-danger" type="submit" name="delete" value="Delete"> </td>
                </form>
                <?php
            echo "</tr>";
        }
        ?>
    </tbody>
</table>
<?php
if(isset($_POST['change_to_admin'])){
    $the_user_id = escape($_POST['user_id']);
    $stmt = prepareStmt("UPDATE users SET user_role = 'admin' WHERE user_id=?");
    mysqli_stmt_bind_param($stmt, 'i', $the_user_id);
    mysqli_stmt_execute($stmt);
    redirect("users.php");
    mysqli_stmt_close($stmt);
}
if(isset($_POST['change_to_sub'])){
    $the_user_id = escape($_POST['user_id']);
    $stmt = prepareStmt("UPDATE users SET user_role = 'subscriber' WHERE user_id=?");
    mysqli_stmt_bind_param($stmt, 'i', $the_user_id);
    mysqli_stmt_execute($stmt);
    redirect("users.php");
    mysqli_stmt_close($stmt);
}
if(isset($_POST['delete'])){
    $the_user_id = escape($_POST['user_id']);
    $stmt = prepareStmt("DELETE FROM users WHERE user_id=?");
    mysqli_stmt_bind_param($stmt, 'i', $the_user_id);
    mysqli_stmt_execute($stmt);
    redirect("users.php");
    mysqli_stmt_close($stmt);
}
?> 