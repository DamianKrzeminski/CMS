<?php include "includes/db.php"?>
<?php include "includes/header.php"?>
<?php include "includes/navigation.php"?>
<!-- Page Content -->
<div class="container">
    <div class="row">
        <!-- Blog Entries Column -->
        <div class="col-md-8">
            <?php
            if(isset($_GET['p_id'])){
                $the_post_id = escape($_GET['p_id']);
                $the_post_author = escape($_GET['author']);
            }
            $stmt = prepareStmt("SELECT * FROM posts WHERE post_user=?");
            mysqli_stmt_bind_param($stmt, 's', $the_post_author);
            mysqli_stmt_execute($stmt);
            $select_all_posts_query = mysqli_stmt_get_result($stmt);
            while($row = mysqli_fetch_assoc($select_all_posts_query)){
                $post_id = $row['post_id'];
                $post_title = $row['post_title'];
                $post_user = $row['post_user'];
                $post_date = $row['post_date'];
                $post_image = $row['post_image'];
                $post_content = $row['post_content'];
            ?>
            <h1 class="page-header">
                Page Heading
                <small>Secondary Text</small>
            </h1>
            <!-- First Blog Post -->
            <h2>
                <a href="/cms/post.php?p_id=<?php echo $post_id?>"><?php echo $post_title;?></a>
            </h2>
            <p class="lead">
                by <?php echo $post_user;?>
            </p>
            <p><span class="glyphicon glyphicon-time"></span> <?php echo $post_date;?> </p>
            <hr>
                <a href="/cms/post.php?p_id=<?php echo $post_id;?>">
                    <img class="img-responsive" src="images/<?php echo $post_image;?>" alt="">
                </a>
            <hr>
            <p> <?php echo $post_content;?> </p>
            <a class="btn btn-primary" href="post.php?p_id=<?php echo $post_id?>"> Read More <span class="glyphicon glyphicon-chevron-right"></span></a>
            <hr>
            <?php 
            }
            mysqli_stmt_close($stmt);
            ?>
        </div>    
        <!-- Blog Sidebar Widgets Column -->
        <?php include "includes/sidebar.php"?>
    </div>
    <!-- /.row -->
    <hr>
<?php include "includes/footer.php"?>            