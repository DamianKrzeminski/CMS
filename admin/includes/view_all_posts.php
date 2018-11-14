<?php include "delete_modal.php";?>
<?php
if(isset($_POST['checkBoxArray'])){
    foreach($_POST['checkBoxArray'] as $post_value_id){
       $bulk_options = $_POST['bulk_options'];
        switch($bulk_options){
            case 'published':
                query("UPDATE posts SET post_status = '{$bulk_options}' WHERE post_id = {$post_value_id}");
            break;
            case 'draft':
                query("UPDATE posts SET post_status = '{$bulk_options}' WHERE post_id = {$post_value_id}");
            break;
            case 'delete':
                query("DELETE FROM posts WHERE post_id = {$post_value_id}");
            break;
            case 'reset':
                query("UPDATE posts SET post_views_count = 0 WHERE post_id = {$post_value_id}");
            break;
            case 'clone':
                $result = query("SELECT * FROM posts WHERE post_id = '{$post_value_id}'");
                while($row = fetchRecords($result)){
                    $post_title = $row['post_title'];
                    $user_id = $row['user_id'];
                    $post_user = $row['post_user'];
                    $post_category_id = $row['post_category_id'];
                    $post_status = $row['post_status'];
                    $post_image = $row['post_image'];
                    $post_tags = $row['post_tags'];
                    $post_content = $row['post_content'];
                    $post_date = $row['post_date'];
                }
                query("INSERT INTO posts (post_category_id, user_id, post_title, post_user, post_date, post_image, post_content, post_tags, post_status) VALUES ('{$post_category_id}', '{$user_id}', '{$post_title}', '{$post_user}', now(), '{$post_image}', '{$post_content}', '{$post_tags}', '{$post_status}')");
            break;
        }
    }
}
?>
<form action="" method="post">
    <table class="table table-bordered table-hover">
        <div id="bulkOptionContainer" class="col-xs-4">
            <select class="form-control" name="bulk_options" id="">
                <option value=""> Select Options </option>
                <option value="published"> Publish </option>
                <option value="draft"> Draft </option>
                <option value="delete"> Delete </option>
                <option value="reset"> Reset Views </option>
                <option value="clone"> Clone </option>
            </select>
        </div>
        <div class="col-xs-4">
            <input type="submit" name="submit" class="btn btn-success" value="Apply">
            <a class="btn btn-primary" href="posts.php?source=add_post">Add New</a>
        </div>
        <thead>
            <tr>
                <th><input id="selectAllBoxes" type="checkbox"></th>
                <th> Id </th>
                <th> User </th>
                <th> Title </th>
                <th> Category </th>
                <th> Status </th>
                <th> Image </th>
                <th> Tags </th>
                <th> Comments </th>
                <th> Date </th>
                <th> Views </th>
                <th> Likes </th>
                <th> View Post </th>
                <th> Edit </th>
                <th> Delete </th>
                <th> Reset Views </th>
            </tr>
        </thead>
        <tbody>
            <?php
            if(isset($_GET['source'])){
                $source = escape($_GET['source']);
                switch($source){
                    case 'all':
                        $select_posts = query("SELECT posts.post_id, posts.post_user, posts.post_title, posts.post_category_id, posts.post_status, posts.post_image, posts.post_tags, posts.post_comment_count, posts.post_date, posts.post_views_count, posts.likes, categories.cat_id, categories.cat_title FROM posts LEFT JOIN categories ON posts.post_category_id = categories.cat_id ORDER BY posts.post_id DESC");
                    break;
                    case 'user':
                        $select_posts = query("SELECT posts.post_id, posts.post_user, posts.post_title, posts.post_category_id, posts.post_status, posts.post_image, posts.post_tags, posts.post_comment_count, posts.post_date, posts.post_views_count, posts.likes, categories.cat_id, categories.cat_title FROM posts LEFT JOIN categories ON posts.post_category_id = categories.cat_id WHERE posts.user_id='" . loggedInUserId() . "' ORDER BY posts.post_id DESC");
                    break;
                }
            }
            while($row = mysqli_fetch_assoc($select_posts)){
                $post_id = $row['post_id'];
                $post_user = $row['post_user'];
                $post_title = $row['post_title'];
                $post_category_id = $row['post_category_id'];
                $post_status = $row['post_status'];
                $post_image = $row['post_image'];
                $post_tags = $row['post_tags'];
                $post_comment_count = $row['post_comment_count'];
                $post_date = $row['post_date'];
                $post_views_count = $row['post_views_count'];
                $post_likes = $row['likes'];
                $cat_title = $row['cat_title'];
                $cat_id = $row['cat_id'];
                    ?>
                    <tr>
                    <td><input class="checkBoxes" type="checkbox" name="checkBoxArray[]" value="<?php echo $post_id;?>"></td>
                    <?php
                    echo "<td> $post_id </td>";
                    echo "<td> $post_user </td>";
                    echo "<td> $post_title </td>";
                    echo "<td> $cat_title </td>";
                    echo "<td> $post_status </td>";
                    echo "<td> <img width='100' src='../images/$post_image' alt=''> </td>";
                    echo "<td> $post_tags </td>";
                $send_comment_query = query("SELECT * FROM comments WHERE comment_post_id = '{$post_id}'");
                $row = fetchRecords($send_comment_query);
                $comment_id = $row['comment_id'];
                $count_comments = countRecords($send_comment_query);
                    echo "<td><a href='post_comments.php?id=$post_id'> $count_comments </a></td>";
                    echo "<td> $post_date </td>";
                    echo "<td> $post_views_count </td>";
                    echo "<td> $post_likes </td>";
                    echo "<td><a class='btn btn-primary' href='../post.php?p_id={$post_id}'> View Post </a></td>";
                    echo "<td><a class='btn btn-info' href='posts.php?source=edit_post&p_id={$post_id}'> Edit </a></td>";
                    ?>
                    <form action="" method="post">
                        <input type="hidden" name="post_id" value="<?php echo $post_id;?>">
                        <td> <input class="btn btn-danger" type="submit" name="delete" value="Delete"> </td>
                        <input type="hidden" name="post_id" value="<?php echo $post_id;?>">
                        <td> <input class="btn btn-danger" type="submit" name="reset" value="Reset Views"> </td>
                    </form>   
                </tr>
            <?php }?>
        </tbody>
    </table>
</form>
<?php
if(isset($_POST['delete'])){
    $the_post_id = escape($_POST['post_id']);
    $stmt = prepareStmt("DELETE FROM posts WHERE post_id=?");
    mysqli_stmt_bind_param($stmt, 'i', $the_post_id);
    mysqli_stmt_execute($stmt);
    switch($source){
        case 'all':
            redirect("posts.php?source=all");
        break;
        case 'user':
            redirect("posts.php?source=user");
        break;
    }  
}
?> 
<?php
if(isset($_POST['reset'])){
    $the_post_id = escape($_POST['post_id']);
    $stmt = prepareStmt("UPDATE posts SET post_views_count=0 WHERE post_id=?");
    mysqli_stmt_bind_param($stmt, 'i', $the_post_id);
    mysqli_stmt_execute($stmt);
    switch($source){
        case 'all':
            redirect("posts.php?source=all");
        break;
        case 'user':
            redirect("posts.php?source=user");
        break;
    }
}
?>

<script>
    $(document).ready(function () {
       $(".delete_link").on('click', function(){
           var id = $(this).attr("rel");
           var delete_url = "posts.php?delete={$post_id}"+ id +" ";
           $(".modal_delete_link").attr("href", delete_url);
           $("#myModal").modal('show');
       });
    });
</script>
