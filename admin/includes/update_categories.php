<form action="" method="post">
    <div class="form-group">
        <label for="cat_title"> Update Category </label>
        <?php
        if(isset($_GET['edit'])){
            $cat_id = escape($_GET['edit']);
            $stmt = prepareStmt("SELECT * FROM categories WHERE cat_id=?");
            mysqli_stmt_bind_param($stmt, 'i', $cat_id);
            mysqli_stmt_execute($stmt);
            $select_categories_id = mysqli_stmt_get_result($stmt);
            while($row = mysqli_fetch_assoc($select_categories_id)){
                $cat_id = $row['cat_id'];
                $cat_title = $row['cat_title'];
        ?>
        <input class="form-control" type="text" name="cat_title" value="<?php if(isset($cat_title)){echo $cat_title;}?>">
        <?php 
            }
        }
        if(isset($_POST['update_category'])){
            $the_cat_title = $_POST['cat_title'];
            $stmt = prepareStmt("UPDATE categories SET cat_title = ? WHERE cat_id = ?");
            mysqli_stmt_bind_param($stmt, 'si', $the_cat_title, $cat_id);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
            redirect("categories.php");
        }
        ?>
    </div> 
    <div class="form-group">
        <input class="btn btn-primary" type="submit" name="update_category" value="Update Category">
    </div>
</form>