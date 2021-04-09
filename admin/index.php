<?php session_start()?>
<?php include_once "language.php"?>
<?php include "resources/includes/header.inc"?>
<?php require "config.php"?>
<?php
/*Start request method*/
if($_SERVER['REQUEST_METHOD'] == "POST")
{
    $adminUsername = $_POST['adminusername'];
    $adminPassword = $_POST['adminpassword'];
    $hasedPass = sha1($adminPassword);
    $stmt = $con->prepare("SELECT * FROM users WHERE username=? AND password=? AND groupid !=0");
    $stmt->execute(array($adminUsername , $hasedPass));
    /* rowCount() -> Boolen function to check if user is existed or not*/
    $count = $stmt->rowCount();
    /*fetchAll() -> fetch data from DB at array*/
    $row = $stmt->fetch(); 
    //fetchAll() ->> TO bring every row from database
    //fetch() ->> TO bring one row from database
    $in_DB = 1;
    // echo "<pre>";
    // print_r($row);
    // echo "</pre>";
    if ($count == $in_DB)
    {
        $_SESSION['USER_NAME'] = $adminUsername;
        $_SESSION['USER_ID'] = $row['user_id'];
        $_SESSION['FULL_NAME'] = $row['fullname'];
        $_SESSION['GROUP_ID'] = $row['groupid'];
        header("location:dashboard.php");
        exit();
    }
    else
    {
        echo "Check username and password";
    }
    
}
?>
<div class="longin">
<h1 class="text-center"><?php echo $lang['TITLE'];?></h1>
<div class="container">
<form method="post" action="<?php $_SERVER['PHP_SELF']?>">
<div class="mb-3">
    <label for="exampleInputEmail1" class="form-label"><?php echo $lang['USERNAME'];?></label>
    <input type="text" name= "adminusername" class="form-control">
    <div id="emailHelp" class="form-text"><?php echo $lang['PRIVACY'];?></div>
</div>
<div class="mb-3">
    <label for="exampleInputPassword1" class="form-label"><?php echo $lang['PASSWORD'];?></label>
    <input type="password" name ="adminpassword" class="form-control" id="exampleInputPassword1">
</div>
<button type="submit" class="btn btn-primary"><?php echo $lang['SUBMIT'];?></button>
</form>
</div>
</div>

    <?php include "resources/includes/footer.inc"?>
