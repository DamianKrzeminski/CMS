<?php //=====DATABASE HELPER FUNCTIONS=====?>

<?php
function Confirm_Query($result){
    global $connection;
    if(!$result){
        die("QUERY FAILED " . mysqli_error($connection));
    }
}
?>

<?php
function prepareStmt($query){
    global $connection;
    $stmt = mysqli_prepare($connection, $query);
    Confirm_Query($stmt);
    return $stmt;
}
?>

<?php
function query($query){
    global $connection;
    $result = mysqli_query($connection, $query);
    Confirm_Query($result);
    return $result;
}
?>

<?php
function escape($string){
    global $connection;
    return mysqli_real_escape_string($connection, trim($string));
}
?>

<?php
function fetchRecords($result){
    return mysqli_fetch_array($result);
}
?>

<?php
function countRecords($result){
    return mysqli_num_rows($result);
}
?>

<?php //=====END DATABASE HELPERS=====?>
<?php //=====GENERALS HELPERS=====?>

<?php
function getUsername(){
    return isset($_SESSION['username']) ? $_SESSION['username'] : null;
}
?>

<?php
function isSetUserRole(){
    if(isset($_SESSION['user_role'])){
        return true;
    }else{
        return false;
    }
    return false;
}
?>

<?php
function loggedInUserId(){
    if(isLoggedIn()){
        $result = query("SELECT * FROM users WHERE username='" . $_SESSION['username'] . "'");
        $user = fetchRecords($result);
        return countRecords($result) >= 1 ? $user['user_id'] : false;
    }
}
?>

<?php
function redirect($location){
    header("Location:" . $location);
    exit;
}
?>

<?php
function ifItISMethod($method = null){
    if($_SERVER['REQUEST_METHOD'] == strtoupper($method)){
        return true;
    }
    return false;
}
?>

<?php //=====END GENERALS HELPERS=====?>
<?php //=====AUTHENTICATION HELPER=====?>

<?php
function is_admin(){
    if(isLoggedIn()){
        $row = fetchRecords(query("SELECT user_role FROM users WHERE user_id=" . $_SESSION['user_id'] . ""));
        if($row['user_role'] == 'admin'){
            return true;
        }else{
            return false;
        }
    }
    return false;
}
?>

<?php //=====END AUTHENTICATION HELPER=====?>
<?php //=====USER SPECIFIC HELPERS=====?>

<?php
function getAllUserRecordCount($table){
    return countRecords(query("SELECT * FROM {$table} WHERE user_id='" . loggedInUserId() . "'"));
}
?>

<?php
function getAllUserStatusCount($table, $column, $status){
    return countRecords(query("SELECT * FROM {$table} WHERE {$column} = '{$status}' AND user_id='" . loggedInUserId() . "'"));
}
?>

<?php //=====END USER SPECIFIC HELPERS=====?>
<?php //=====ADMIN SPECIFIC HELPERS=====?>

<?php
function getAllAdminRecordCount($table){
    return countRecords(query("SELECT * FROM {$table}"));
}
?>

<?php
function getAllAdminStatusCount($table, $column, $status){
    return countRecords(query("SELECT * FROM {$table} WHERE {$column} = '{$status}'"));
}
?>

<?php //=====END USER SPECIFIC HELPERS=====?>
<?php //=====LIKES=====?>

<?php
function likesAndUnlikes($like){
    global $connection;
    $user_id = $_POST['user_id'];
    $post_id = $_POST['post_id'];
    //1. FETCHING THE RIGHT POST
    $post = fetchRecords(query("SELECT * FROM posts WHERE post_id=$post_id"));
    $likes = $post['likes'];
    if($like == 'liked'){
        //2. UPDATE INCREMENTING POST WITH LIKES
        query("UPDATE posts SET likes=$likes+1 WHERE post_id=$post_id");
        //3. CREATE LIKES FOR POST
        query("INSERT INTO likes(user_id, post_id) VALUES($user_id, $post_id)");
        exit();
    }else if($like == 'unliked'){
        //2. DELETE LIKES
        query("DELETE FROM likes WHERE post_id=$post_id AND user_id=$user_id");
        //3. UPDATE DECREMENTING LIKES
        query("UPDATE posts SET likes=$likes-1 WHERE post_id=$post_id");
        exit();
    }
}
?>

<?php
function getCountPostLikes($post_id){
    echo countRecords(query("SELECT * FROM likes WHERE post_id=$post_id"));
}
?>

<?php
function userLikedThisPost($post_id){
    return countRecords(query("SELECT * FROM likes WHERE user_id=" . loggedInUserId() . " AND post_id={$post_id}")) >= 1 ? true : false;
}
?>

<?php //=====END LIKES=====?>
<?php //=====LOGIN=====?>

<?php
function isLoggedIn(){
    if(isset($_SESSION['user_role'])){
        return true;
    }
    return false;
}
?>

<?php
function checkIfUserIsLoggedInAndRedirect(){
    if(isLoggedIn()){
        redirect("/cms/index.php");
    }
}
?>

<?php
function login_user($username, $password){
    $username = escape($username);
    $password = escape($password);
    while($row = fetchRecords(query("SELECT * FROM users WHERE username = '{$username}'"))){
        $db_user_id = $row['user_id'];
        $db_username = $row['username'];
        $db_user_password = $row['user_password'];
        $db_user_firstname = $row['user_firstname'];
        $db_user_lastname = $row['user_lastname'];
        $db_user_role = $row['user_role'];
        if(password_verify($password, $db_user_password)){
            $_SESSION['user_id'] = $db_user_id;
            $_SESSION['username'] = $db_username;
            $_SESSION['firstname'] = $db_user_firstname;
            $_SESSION['lastname'] = $db_user_lastname;
            $_SESSION['user_role'] = $db_user_role;
            redirect("/cms/admin");
        }else{
            return false;
        }
    }
    return true;
}
?>

<?php //=====END LOGIN=====?>
<?php //=====USERS ONLINE=====?>

<?php
function users_online(){
    if(isset($_GET['onlineusers'])){
        global $connection;
        if(!$connection){
            session_start();
            include("../includes/db.php");
            $session = session_id();
            $time = time();
            $time_out_in_seconds = 05;
            $time_out = $time - $time_out_in_seconds;
            $count = countRecords(query("SELECT * FROM users_online WHERE session = '$session'")); 
            if($count == NULL){
                query("INSERT INTO users_online (session, time)  VALUES ('$session', '$time')");
            }else{
                query("UPDATE users_online SET time='$time' WHERE session = '$session'");
            }
            echo $count_user = countRecords(query("SELECT * FROM users_online WHERE time > '$time_out'"));
        }
    }
} // get request isset
users_online();
?>

<?php //=====END USERS ONLINE=====?>
<?php //=====REGISTRATION=====?>

<?php
function register_user($username, $email, $password){
    $username = escape($username);
    $email = escape($email);
    $password = escape($password);
    $password = password_hash($password, PASSWORD_BCRYPT, array('cost'=>12));    
    $register_user_query = query("INSERT INTO users (username, user_email, user_password, user_role) VALUES ('{$username}', '{$email}', '{$password}', 'subscriber')");
}
?>

<?php
function email_exists($email){
    if(countRecords(query("SELECT user_email FROM users WHERE user_email = '$email'"))>0){
        return true;
    }else{
        return false;
    }
}
?>

<?php
function username_exists($username){
    if(countRecords(query("SELECT username FROM users WHERE username = '$username'"))>0){
        return true;
    }else{
        return false;
    }
}
?>

<?php //=====END REGISTRATION=====?>
<?php //=====IMAGE=====?>

<?php
function imagePlaceholder($image=''){
    if(!$image){
        return '';
    }else{
        return $image;
    }
}
?>

<?php //=====END IMAGE=====?>
<?php //=====CRUD QUERIES FOR CATEGORIES=====?>

<?php
function Insert_Categories(){
    global $connection;
    if(isset($_POST['submit'])){
        $cat_title = $_POST['cat_title'];
        if($cat_title == "" || empty($cat_title)){
            echo "This field should not be empty";
        }else{
            $stmt = prepareStmt("INSERT INTO categories(cat_title, user_id) VALUES (?,?)");
            $user_id=loggedInUserId();
            mysqli_stmt_bind_param($stmt, 'si', $cat_title, $user_id);
            mysqli_stmt_execute($stmt);
        }
        mysqli_stmt_close($stmt);
    }
}
?>

<?php
function findAllCategories(){
    $select_categories = query("SELECT * FROM categories");
    while($row = mysqli_fetch_assoc($select_categories)){
        $cat_id = $row['cat_id'];
        $cat_title = $row['cat_title'];
        echo "<tr>";
        echo "<td> {$cat_id} </td>";
        echo "<td> {$cat_title} </td>";
        echo "<td><a class='btn btn-danger' href='categories.php?delete={$cat_id}'> Delete </a></td>";
        echo "<td><a class='btn btn-info' href='categories.php?edit={$cat_id}'> Edit </a></td>";
        echo "</tr>";
    }
}
?>

<?php
function updateCategories(){
    if(isset($_GET['edit'])){
        $cat_id = escape($_GET['edit']);
        include "includes/update_categories.php";
    }
}
?>

<?php
function deleteCategories(){
    if(isset($_GET['delete'])){
        $the_cat_id = escape($_GET['delete']);
        $stmt = prepareStmt("DELETE FROM categories WHERE cat_id=?");
        mysqli_stmt_bind_param($stmt, 'i', $the_cat_id);
        mysqli_stmt_execute($stmt);
        redirect('categories.php');
    }
}
?>

<?php //=====END CRUD QUERIES FOR CATEGORIES=====?>
<?php //=====FUNCTIONS FOR POSTS=====?>

<?php
function createPosts(){
    global $connection;
    $post_title = escape($_POST['title']);
    $post_user = getUsername();
    $post_category_id = $_POST['post_category'];
    $post_status = $_POST['post_status'];
    $post_image = $_FILES['image']['name'];
    $post_image_temp = $_FILES['image']['tmp_name'];
    $post_tags = escape($_POST['post_tags']);
    $post_content = escape($_POST['post_content']);
    $post_date = date('d-m-y');
    move_uploaded_file($post_image_temp, "../images/$post_image");
    $stmt = prepareStmt("INSERT INTO posts (post_category_id, user_id, post_title, post_user, post_date, post_image, post_content, post_tags, post_status) VALUES (?,?, ?,?,now(),?,?,?,?)");
    $user_id = loggedInUserId();
    mysqli_stmt_bind_param($stmt, 'iissssss', $post_category_id, $user_id, $post_title, $post_user, $post_image, $post_content, $post_tags, $post_status);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    $the_post_id = mysqli_insert_id($connection);
    
    echo "<p class='bg-success'>Post Created. <a href='../post.php?p_id={$the_post_id}'>View Post</a></p>";
}
?>    

<?php //=====END FUNCTIONS FOR POSTS=====?>