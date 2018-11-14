<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <div class="container">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="/cms"> CMS Front </a>
        </div>
        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
                <?php
                $select_all_categories_query = query("SELECT * FROM categories");
                while($row = mysqli_fetch_assoc($select_all_categories_query)){
                    $cat_title = $row['cat_title'];
                    $cat_id = $row['cat_id'];
                    $category_class = '';
                    $registration_class = '';
                    $page_name = basename($_SERVER['PHP_SELF']);
                    $registration = 'registration.php';
                    if(isset($_GET['category']) && $_GET['category'] == $cat_id){
                       $category_class = 'active'; 
                    }else if($page_name == $registration){
                       $registration_class = 'active';
                    } 
                    echo "<li class='$category_class'><a href='/cms/category.php?category={$cat_id}'> {$cat_title} </a></li>";
                }
                ?>
                <?php if(isLoggedIn()){?>
                <li>
                    <a href="/cms/admin"> Admin/User </a>
                </li>
                <li>
                    <a href="/cms/includes/logout.php"> Logout </a>
                </li>
                <?php }else{?>
                <li>
                    <a href="/cms/login"> Login </a>
                </li>
                <li class='<?php echo $registration_class;?>'>
                    <a href="/cms/registration.php"> Registration </a>
                </li>
                <?php }?>
                <?php
                if(isset($_SESSION['user_role'])){
                    if(isset($_GET['p_id'])){
                        $the_post_id = escape($_GET['p_id']);
                        echo "<li><a href='/cms/admin/posts.php?source=edit_post&p_id={$the_post_id}'> Edit Post </a></li>";
                    }
                }
                ?>
                <li>
                    <a href="contact.php"> Contact </a>
                </li>
            </ul>
        </div>
        <!-- /.navbar-collapse -->
    </div>
    <!-- /.container -->
</nav>