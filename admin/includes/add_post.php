<?php
if(isset($_POST['create_post'])){
    createPosts();
}
?>
<form action="" method="post" enctype="multipart/form-data">
    <div class="form-group">
        <label for="title"> Post Title </label>
        <input class="form-control" type="text" name="title">
    </div>
    <div class="form-group">
        <label for="category"> Category </label>
        <select name="post_category" id="">
            <?php
            $select_categories = query("SELECT * FROM categories");
            while($row = mysqli_fetch_assoc($select_categories)){
                $cat_id = $row['cat_id'];
                $cat_title = $row['cat_title'];
                echo "<option value='{$cat_id}'>{$cat_title}</option>";
            }
            ?>
        </select>
    </div>
    <div class="form-group">
        <label for="users"> User </label>
        <label for='users'><?php echo getUsername();?></label>
    </div>
    <div class="form-group">
        <select name="post_status" id="">
            <option value='draft'> Post Status </option>
            <option value='published'> Published </option>
            <option value='draft'> Draft </option>
        </select>
    </div>
    <div class="form-group">
        <label for="post_image"> Post Image </label>
        <input type="file" name="image">
    </div>
    <div class="form-group">
        <label for="post_tags"> Post Tags </label>
        <input class="form-control" type="text" name="post_tags">
    </div>
    <div class="form-group">
        <label for="post_content"> Post Content </label>
        <textarea class="form-control" name="post_content" id="body" cols="30" rows="10"></textarea>
    </div>
    <div class="form-group">
        <input class="btn btn-primary" type="submit" name="create_post" value="Publish Post">
    </div>
</form>