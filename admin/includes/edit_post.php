<?php
if(isset($_GET['p_id'])){
    $the_post_id = escape($_GET['p_id']);
    $stmt = prepareStmt("SELECT * FROM posts WHERE post_id=?");
    mysqli_stmt_bind_param($stmt, 'i', $the_post_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    while($row = mysqli_fetch_assoc($result)){
        $post_id = $row['post_id'];
        $post_user = $row['post_user'];
        $post_title = $row['post_title'];
        $post_category_id = $row['post_category_id'];
        $post_status = $row['post_status'];
        $post_image = $row['post_image'];
        $post_content = $row['post_content'];
        $post_tags = $row['post_tags'];
        $post_comment_count = $row['post_comment_count'];
        $post_date = $row['post_date'];
    }
    mysqli_stmt_close($stmt);
    if(isset($_POST['update_post'])){
        $post_title = escape($_POST['post_title']);
        $post_category_id = $_POST['post_category'];
        $post_status = $_POST['post_status'];
        $post_image = $_FILES['image']['name'];
        $post_image_temp = $_FILES['image']['tmp_name'];
        $post_content = escape($_POST['post_content']);
        $post_tags = escape($_POST['post_tags']);
        move_uploaded_file($post_image_temp, "../images/$post_image");
        if(empty($post_image)){
            $stmt = prepareStmt("SELECT * FROM posts WHERE post_id=?");
            mysqli_stmt_bind_param($stmt, 'i', $the_post_id);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            while($row = mysqli_fetch_array($result)){
                $post_image = $row['post_image'];
            }
            mysqli_stmt_close($stmt);
        }
        $stmt = prepareStmt("UPDATE posts SET post_title = ?, post_category_id = ?, post_date = now(), post_status = ?, post_tags = ?, post_content = ?, post_image = ? WHERE post_id = ?");
        mysqli_stmt_bind_param($stmt, 'sissssi', $post_title, $post_category_id, $post_status, $post_tags, $post_content, $post_image, $the_post_id);
        mysqli_stmt_execute($stmt);
        echo "<p class='bg-success'>Post Updated. <a href='../post.php?p_id={$the_post_id}'> View Post </a> or <a href='posts.php?source=user'> Edit More Posts</a></p>";
        mysqli_stmt_close($stmt);
    }
}
?>
<form action="" method="post" enctype="multipart/form-data">
    <div class="form-group">
        <label for="title"> Post Title </label>
        <input class="form-control" type="text" name="post_title" value="<?php echo $post_title;?>">
    </div>
    <div class="form-group">
        <label for="categories"> Categories </label>
        <select name="post_category" id="">
            <?php
            $select_categories = query("SELECT * FROM categories");
            while($row = mysqli_fetch_assoc($select_categories)){
                $cat_id = $row['cat_id'];
                $cat_title = $row['cat_title'];
                if($cat_id == $post_category_id){
                    echo "<option selected value='{$cat_id}'>{$cat_title}</option>";
                }else{
                    echo "<option value='{$cat_id}'>{$cat_title}</option>";
                }
            }
            ?>
        </select>
    </div>
    <div class="form-group">
        <label for="users"> User </label>
        <label for='users'><?php echo $post_user;?></label>
    </div>
    <div class="form-group">
        <label for="users"> Post Status </label>
        <select name="post_status" id="">
            <option value='<?php echo $post_status;?>'> <?php echo $post_status;?> </option>
            <?php
            if($post_status == 'published'){
                echo "<option value='draft'> Draft </option>";
            }else{
                echo "<option value='published'> Publish </option>";
            }
            ?>
        </select>
    </div>
    <div class="form-group">
        <img width="100" src="../images/<?php echo $post_image;?>">
        <input type="file" name="image">
    </div>
    <div class="form-group">
        <label for="post_tags"> Post Tags </label>
        <input class="form-control" type="text" name="post_tags" value="<?php echo $post_tags;?>">
    </div>
    <div class="form-group">
        <label for="post_content"> Post Content </label>
        <textarea class="form-control" type="text" name="post_content" id="body" col="30" rows="10"><?php echo str_replace('\r\n','</br>',$post_content);?></textarea>
    </div>
    <div class="form-group">
        <input class="btn btn-primary" type="submit" name="update_post" value="Update Post">
    </div>
</form>