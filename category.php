<?php include "includes/db.php"?>
<?php include "includes/header.php"?>
<?php include "includes/navigation.php"?>
<!-- Page Content -->
<div class="container">
    <div class="row">
        <!-- Blog Entries Column -->
        <div class="col-md-8">
            <?php
            if(isset($_GET['category'])){
                $post_category_id = escape($_GET['category']);
            if(is_admin()){
                    $stmt = prepareStmt("SELECT post_id, post_title, post_user, post_date, post_image, post_content FROM posts WHERE post_category_id = ? ");
                    mysqli_stmt_bind_param($stmt, "i", $post_category_id);
                    mysqli_stmt_execute($stmt);
                    mysqli_stmt_bind_result($stmt, $post_id, $post_title, $post_user, $post_date, $post_image, $post_content);
                    mysqli_stmt_store_result($stmt);
            }else{
                    $stmt = prepareStmt("SELECT post_id, post_title, post_user, post_date, post_image, post_content FROM posts WHERE post_category_id = ? AND post_status = ?");
                    $published = 'published';
                    mysqli_stmt_bind_param($stmt, "is", $post_category_id, $published);
                    mysqli_stmt_execute($stmt);
                    mysqli_stmt_bind_result($stmt, $post_id, $post_title, $post_user, $post_date, $post_image, $post_content);
                    mysqli_stmt_store_result($stmt);
                }    
            if(mysqli_stmt_num_rows($stmt) === 0){
                echo "<h1 class='text-center'> No post avaliable" . mysqli_stmt_num_rows($stmt) . "</h1>";
            }
            while(mysqli_stmt_fetch($stmt)):
            ?>
            <h1 class="page-header">
                 Page Heading 
                <small> Posts </small>
            </h1>
            <!-- First Blog Post -->
            <h2>
                <a href="post.php?p_id=<?php echo $post_id?>"> <?php echo $post_title;?> </a>
            </h2>
            <p class="lead">
                by <a href="author_posts.php?author=<?php echo $post_user;?>&p_id=<?php echo $post_id;?>"> <?php echo $post_user;?> </a>
            </p>
            <p><span class="glyphicon glyphicon-time"></span> <?php echo $post_date;?> </p>
            <hr>
                <a href="post.php?p_id=<?php echo $post_id;?>">
                    <img class="img-responsive" src="images/<?php echo $post_image;?>" alt="">
                </a>
            <hr>
            <p> <?php echo $post_content;?> </p>
            <a class="btn btn-primary" href="post.php?p_id=<?php echo $post_id;?>"> Read More <span class="glyphicon glyphicon-chevron-right"></span></a>
            <hr>
            <?php endwhile;
                mysqli_stmt_close($stmt);
            }else{
                redirect("index.php");
            }
            ?>
        </div>    
        <!-- Blog Sidebar Widgets Column -->
        <?php include "includes/sidebar.php"?>
    </div>
    <!-- /.row -->
    <hr>
<?php include "includes/footer.php"?>        