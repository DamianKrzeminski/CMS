<?php include "includes/db.php"?>
<?php include "includes/header.php"?>
<?php include "includes/navigation.php"?>
<!-- Page Content -->
<div class="container">
    <div class="row">   
        <!-- Blog Entries Column -->
        <div class="col-md-8">          
            <?php
            if(isset($_POST['submit'])){
                $search = escape($_POST['search']);
                
                $search_query = query("SELECT * FROM posts WHERE post_tags LIKE '%{$search}%'");
                //$param = "%{$search}%";
                //mysqli_stmt_bind_param($stmt);
                //mysqli_stmt_execute($stmt);
                //$search_query = mysqli_stmt_get_result($stmt);
                
                $count = mysqli_num_rows($search_query);
                
                if($count == 0){
                    
                    echo "<h1> NO RESULT </h1>";
                }else{
                    while($row = mysqli_fetch_assoc($search_query)){
                        $post_title = $row['post_title'];
                        $post_user = $row['post_user'];
                        $post_date = $row['post_date'];
                        $post_image = $row['post_image'];
                        $post_content = $row['post_content'];
                        //die($search);
            ?>
            <h1 class="page-header">
                Posts
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
            <a class="btn btn-primary" href="post.php?p_id=<?php echo $post_id?>"> Read More <span class="glyphicon glyphicon-chevron-right"></span></a>
            <hr>
            <?php 
                    }    
                }
            }
            ?>                   
        </div>  
        <!-- Blog Sidebar Widgets Column -->
        <?php include "includes/sidebar.php"?>
    </div>
    <!-- /.row -->
    <hr>
<?php include "includes/footer.php"?>     