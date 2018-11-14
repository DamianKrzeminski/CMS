<?php include "includes/db.php"?>
<?php include "includes/header.php"?>
<?php include "includes/navigation.php"?>
<?php   
if(isset($_POST['liked'])){
    likesAndUnlikes('liked');
}else if(isset($_POST['unliked'])){
    likesAndUnlikes('unliked');
}
?>
<!-- Page Content -->
<div class="container">
    <div class="row">
        <!-- Blog Entries Column -->
        <div class="col-md-8">
            <?php
            if(isset($_GET['p_id'])){
                $the_post_id = escape($_GET['p_id']);
                $stmt = prepareStmt("UPDATE posts SET post_views_count = post_views_count +1 WHERE post_id=?");
                mysqli_stmt_bind_param($stmt, 'i', $the_post_id);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);
                if(is_admin()){
                    $stmt = prepareStmt("SELECT * FROM posts WHERE post_id=?");
                }else{
                     $stmt = prepareStmt("SELECT * FROM posts WHERE post_id=? AND post_status = 'published' ");
                }
                mysqli_stmt_bind_param($stmt, 'i', $the_post_id);
                mysqli_stmt_execute($stmt);
                $select_all_posts_query = mysqli_stmt_get_result($stmt);
                if(countRecords($select_all_posts_query)<1){
                    echo "<h1 class='text-center'> No post avaliable </h1>";
                }else{
            while($row = mysqli_fetch_assoc($select_all_posts_query)){
                $post_title = $row['post_title'];
                $post_user = $row['post_user'];
                $post_date = $row['post_date'];
                $post_image = $row['post_image'];
                $post_content = $row['post_content'];
            ?>
            <h1 class="page-header">
                Post
            </h1>
            <!-- First Blog Post -->
            <h2>
                <?php echo $post_title;?>
            </h2>
            <p class="lead">
                by <a href="author_posts.php?author=<?php echo $post_user;?>&p_id=<?php echo $post_id;?>"> <?php echo $post_user;?> </a>
            </p>
            <p><span class="glyphicon glyphicon-time"></span> <?php echo $post_date;?> </p>
            <hr>
                <img class="img-responsive" src="images/<?php echo $post_image;?>" alt="">
            <hr>
            <p> <?php echo $post_content;?> </p>
             <hr>
            <?php if(isLoggedIn()){?>
                    <div class="row">
                        <p class="pull-right"><a class="<?php echo userLikedThisPost($the_post_id) ? 'unlike' : 'like';?>" href=""><span class="glyphicon glyphicon-thumbs-up" data-toggle="tooltip" data-placement="top" title="<?php echo userLikedThisPost($the_post_id) ? 'I liked this befor' : 'Want to like it?';?>"></span><?php echo userLikedThisPost($the_post_id) ? ' Unlike' : ' Like';?></a></p>
                    </div>
           <?php }else{?>
                     <div class="row">
                         <p class="pull-right login-to-post">You need to <a href="/cms/login.php">Login</a> to like</p>
                     </div>
            <?php }?>
            <div class="row">
                <p class="pull-right likes">Like: <?php getCountPostLikes($the_post_id) ?></p>
            </div>
            <div class="clearfix"></div>
            <?php }?>
            <!-- Blog Comments -->
            <?php
            if(isset($_POST['create_comment'])){
                $the_post_id = escape($_GET['p_id']);
                $comment_author = getUsername();
                $comment_email = escape($_POST['comment_email']);
                $comment_content = escape($_POST['comment_content']);
                if(!empty($comment_author) && !empty($comment_email) && !empty($comment_content)){
                    $stmt = prepareStmt("INSERT INTO comments (comment_post_id, user_id, comment_author, comment_email, comment_content, comment_status, comment_date) VALUES (?,?,?,?,?,?, now())");
                    $comment_status = 'approved';
                    $user_id = loggedInUserId();
                    mysqli_stmt_bind_param($stmt, 'iissss', $the_post_id, $user_id, $comment_author, $comment_email, $comment_content, $comment_status);
                    mysqli_stmt_execute($stmt);
                    mysqli_stmt_close($stmt);
                    $query = prepareStmt("UPDATE posts SET post_comment_count = post_comment_count +1 WHERE post_id=?");
                    mysqli_stmt_bind_param($stmt, 'i', $the_post_id);
                    mysqli_stmt_execute($stmt);
                }else{
                    echo "<script>alert('Fields cannot be empty')</script>";
                }
                mysqli_stmt_close($stmt);
            }
            ?>
            <!-- Comments Form -->
            <div class="well">
                <h4> Leave a Comment: </h4>
                <form action="" method="post" role="form">
                    <div class="form-group">
                        <label for="author"> <?php echo getUsername();?> </label>
                    </div>
                    <div class="form-group">
                        <label for="email"> Email </label>
                        <input class="form-control" type="email" name="comment_email">
                    </div>
                    <div class="form-group">
                        <label for="comment"> Your Comment </label>
                        <textarea class="form-control" name="comment_content" rows="3"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary" name="create_comment"> Submit </button>
                </form>
            </div>
            <hr>
            <h3 class="media-heading"> Comments</h3>
            <!-- Posted Comments -->
            <?php
            $stmt = prepareStmt("SELECT * FROM comments WHERE comment_post_id =? AND comment_status = 'approved' ORDER BY comment_id DESC");
            mysqli_stmt_bind_param($stmt, 'i', $the_post_id);
            mysqli_stmt_execute($stmt);
            $select_comment_query = mysqli_stmt_get_result($stmt);
            while($row = fetchRecords($select_comment_query)){
                $comment_date = $row['comment_date'];
                $comment_content = $row['comment_content'];
                $comment_author = $row['comment_author'];
            ?>
            <!-- Comment -->
            <div class="media">
                <div class="media-body"> <?php echo $comment_author;?>
                    <h4 class="media-heading">
                        <small><?php echo $comment_date;?></small>
                    </h4>
                    <?php echo $comment_content;?>
                </div>
            </div>
            <?php
            }
            mysqli_stmt_close($stmt);
            }
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
<!--LIKING-->
<script>
    $(document).ready(function(){
        $("[data-toggle='tooltip]").tooltip();
        var post_id = <?php echo $the_post_id?>;
            var user_id = <?php echo loggedInUserId()?>;
        $('.like').click(function(){
           $.ajax({
                url: "/cms/post.php?p_id=<?php echo $the_post_id;?>",
                type: 'post',
                data: {
                    'liked': 1,
                    'post_id': post_id,
                    'user_id': user_id
                }
           }); 
        });
    });
</script>
<!--UNLIKING  -->
<script>
    $(document).ready(function(){
        var post_id = <?php echo $the_post_id?>;
            var user_id = <?php echo loggedInUserId()?>;
        $('.unlike').click(function(){
           $.ajax({
                url: "/cms/post.php?p_id=<?php echo $the_post_id;?>",
                type: 'post',
                data: {
                    'unliked': 1,
                    'post_id': post_id,
                    'user_id': user_id
                }
           }); 
        });
    });
</script>                 