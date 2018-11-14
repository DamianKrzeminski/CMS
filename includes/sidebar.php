<?php
if(ifItISMethod('post')){
    if(isset($_POST['username']) && isset($_POST['password'])){
        login_user($_POST['username'], $_POST['password']);
    }else{
        redirect('index.php');
    }
}
?>
<div class="col-md-4"> 
    <!-- Blog Search Well -->  
    <div class="well"> 
        <h4> Blog Search </h4>
        <form action="search.php" method="post">
            <div class="input-group">
                <input name="search" type="text" class="form-control">
                <span class="input-group-btn">
                    <button type="submit" name="submit" class="btn btn-default">
                        <span class="glyphicon glyphicon-search"></span>
                    </button>
                </span>
            </div>
        </form>
        <!-- Search form -->
        <!-- /.input-group -->
    </div>    
    <!-- Login -->                                    
    <div class="well"> 
       <?php if(isSetUserRole()):?>
       <h4> Logged in as <?php echo getUsername();?></h4>
       <a href="includes/logout.php" class="btn btn-primary"> Logout </a>
       <?php else:?>
       <h4> Login </h4>
        <form method="post">
            <div class="form-group">
                <input name="username" type="text" class="form-control" placeholder=" Enter Username ">
            </div>
            <div class="input-group">
                <input name="password" type="password" class="form-control" placeholder=" Enter Pasword ">
                <span class="input-group-btn">
                    <button class="btn btn-primary" name="login" type="submit"> Submit </button>
                </span>
            </div>
        </form>
       <?php endif;?>
        <!-- Search form -->
        <!-- /.input-group -->
    </div>    
    <!-- Blog Categories Well -->
    <div class="well">
        <?php
        $select_categories_sidebar = query("SELECT * FROM categories");  
        ?>
        <h4> Blog Categories </h4>
        <div class="row">
            <div class="col-lg-12">
                <ul class="list-unstyled">
                    <?php
                    while($row = mysqli_fetch_assoc($select_categories_sidebar)){
                        $cat_title = $row['cat_title'];
                        $cat_id = $row['cat_id'];
                        echo "<li><a href='category.php?category=$cat_id'> {$cat_title} </a></li>";
                    }
                    ?>
                </ul>
            </div>
        </div>
        <!-- /.row -->
    </div>
    <!-- Side Widget Well -->
    <?php include "widget.php"?>
</div>