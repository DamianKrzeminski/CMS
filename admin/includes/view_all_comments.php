<table class="table table-bordered table-hover">
    <thead>
        <tr>
            <th> Id </th>
            <th> Author </th>
            <th> Content </th>
            <th> Email </th>
            <th> Status </th>
            <th> In Response to </th>
            <th> Data </th>
            <th> Approve </th>
            <th> Unapprove </th>
            <th> Delete </th>
        </tr>
    </thead>
    <tbody>
        <?php
        if(isset($_GET['source'])){
            $source = escape($_GET['source']);
            switch($source){
                case 'all':
                    $select_comments = query("SELECT * FROM comments");
                break;
                case 'user':
                    $select_comments = query("SELECT * FROM comments WHERE user_id='" . loggedInUserId() . "'");
                break;
            }
        }
        while($row = mysqli_fetch_assoc($select_comments)){
            $comment_id = $row['comment_id'];
            $comment_post_id = $row['comment_post_id'];
            $comment_author = $row['comment_author'];
            $comment_email = $row['comment_email'];
            $comment_content = $row['comment_content'];
            $comment_status = $row['comment_status'];
            $comment_date = $row['comment_date'];
            echo "<tr>";
                echo "<td> $comment_id </td>";
                echo "<td> $comment_author </td>";
                echo "<td> $comment_content </td>";
                echo "<td> $comment_email </td>";;
                echo "<td> $comment_status </td>";
                $select_post_id_query = query("SELECT * FROM posts WHERE post_id = $comment_post_id");
                while($row = mysqli_fetch_assoc($select_post_id_query)){
                    $post_id = $row['post_id'];
                    $post_title = $row['post_title'];
                    echo "<td><a href='../post.php?p_id=$post_id'> $post_title </a></td>";
                }
                echo "<td>$comment_date</td>";
                ?>
                <form action="" method="post">
                    <input type="hidden" name="comment_id" value="<?php echo $comment_id;?>">
                    <td> <input class="btn btn-primary" type="submit" name="approve" value="Approve"> </td>
                    <input type="hidden" name="comment_id" value="<?php echo $comment_id;?>">
                    <td> <input class="btn btn-primary" type="submit" name="unapprove" value="Unapprove"> </td>
                    <input type="hidden" name="comment_id" value="<?php echo $comment_id;?>">
                    <td> <input class="btn btn-danger" type="submit" name="delete" value="Delete"> </td>
                </form>
                <?php
            echo "</tr>";
        }
        ?>
    </tbody>
</table>
<?php
if(isset($_POST['approve'])){
    $the_comment_id = escape($_POST['comment_id']);
    $stmt = prepareStmt("UPDATE comments SET comment_status = 'approved' WHERE comment_id=?");
    mysqli_stmt_bind_param($stmt, 'i', $the_comment_id);
    mysqli_stmt_execute($stmt);
    switch($source){
        case 'all':
            redirect("comments.php?source=all");
        break;
        case 'user':
            redirect("comments.php?source=user");
        break;
    } 
    mysqli_stmt_close($stmt);
}
if(isset($_POST['unapprove'])){
    $the_comment_id = escape($_POST['comment_id']);
    $stmt = prepareStmt("UPDATE comments SET comment_status = 'unapproved' WHERE comment_id=?");
    mysqli_stmt_bind_param($stmt, 'i', $the_comment_id);
    mysqli_stmt_execute($stmt);
    switch($source){
        case 'all':
            redirect("comments.php?source=all");
        break;
        case 'user':
            redirect("comments.php?source=user");
        break;
    }
    mysqli_stmt_close($stmt);
}
if(isset($_POST['delete'])){
    $the_comment_id = escape($_POST['comment_id']);
    $stmt = prepareStmt("DELETE FROM comments WHERE comment_id=?");
    mysqli_stmt_bind_param($stmt, 'i', $the_comment_id);
    mysqli_stmt_execute($stmt);
    switch($source){
        case 'all':
            redirect("comments.php?source=all");
        break;
        case 'user':
            redirect("comments.php?source=user");
        break;
    }
    mysqli_stmt_close($stmt);
}
?> 