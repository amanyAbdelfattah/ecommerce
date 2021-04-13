<?php
session_start();
$do = "";
if(isset($_GET['do']))
{
    // echo $_GET['do'];
    $do = $_GET['do'];
}
else
{
    // echo "sorry";
    $do = "manage";
}
?>
<?php if(isset($_SESSION['USER_NAME'])):?>
    <?php include "resources/includes/header.inc"?>
    <?php require "config.php"?>
    <?php include "resources/includes/navbar.inc"?>

    <!-- Start Member CRUD Page-->

    <?php if($do == "manage"):?>
    <!--Start all members page-->
    <?php 
        //start pagenation
        $recorded_per_page = 5;
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $start_From = ($page-1)*$recorded_per_page;
        
        //end pagenation
        // Select All From Database
        $stmt=$con->prepare("SELECT * FROM users WHERE groupid=0 LIMIT $start_From , $recorded_per_page");
        $stmt->execute();
        $rows = $stmt->fetchAll();
        // echo "<pre>";
        // echo print_r($rows);
        // echo "</pre>";
    ?>
    <div class="container">
        <h1 class="text-center"><?php echo $lang['MAMBERS'];?></h1>
        <!-- Add Members -->
        <a class="btn btn-primary m-3" href="?do=add">
            <i class="fas fa-user-plus"></i> <?php echo $lang['AddMembers'];?>
        </a>
        <table class="table">
<thead>
    <tr>
        <th scope="col"><?php echo $lang['Member Photo'];?></th>
        <th scope="col"><?php echo $lang['UserName'];?></th>
        <th scope="col"><?php echo $lang['EMAIL'];?></th>
        <th scope="col"><?php echo $lang['CreatedAT'];?></th>
        <th scope="col"><?php echo $lang['CONTROL'];?></th>
    </tr>
</thead>
<tbody>
<?php foreach($rows as $row):?>
    <tr>
        <!-- php echo IS THE SAME AS = -->
        <th scope="row">
            <img style="height:20vh" src="public\image\uploads\members\<?= $row["path"]?>" alt="<?= $row["path"]?>">
            </th>
        <th scope="row"><?= $row["username"]?></th>
        <td><?= $row["email"]?></td>
        <td><?= $row["created_at"]?></td> <!--we use timestamp type to -->
        <td>
            <a class="btn btn-info m-1" href="?do=show&userid=<?= $row['user_id']?>" title="<?php echo $lang['SHOW'];?>">
                <i class="fas fa-eye"></i>
            </a>
            <?php if($_SESSION['GROUP_ID'] == 1):?>
            <a class="btn btn-warning m-1" href="?do=edit&userid=<?= $row['user_id']?>" title="<?php echo $lang['EDIT'];?>">
                <i class="fas fa-edit"></i>
            </a>
            <a class="btn btn-danger m-1" href="?do=delete&userid=<?= $row['user_id']?>" title="<?php echo $lang['Delete'];?>">
                <i class="fas fa-trash"></i>
            </a>
            <?php endif?>
        </td>
    </tr>
<?php endforeach?>
</tbody>
</table>
<!--Start paginate counter-->
<?php 
    $stmt = $con -> prepare("SELECT * FROM users WHERE groupid=0 ORDER BY user_id DESC");
    $stmt -> execute();
    $total_recorded = $stmt -> rowCount();
    // ceil : function to approximate float to integer
    $total_page = ceil($total_recorded / $recorded_per_page);

    $start_loop = 1;
    $end_loop = $total_page;

?>
<nav aria-label="Page navigation example">
<ul class="pagination justify-content-center">
<?php for($i = $start_loop; $i <= $end_loop; $i++):?>
    <li class="page-item"><a class="page-link" style="font-size: 25px" href="?do=manage&page=<?= $i?>"><?= $i?></a></li>
    <?php endfor?>
</ul>
</nav>
<!--End paginate counter-->
    </div>
        <!--End all members page-->

    <?php elseif($do == "add"):?>
        <div class="container">
            <h1 class="text-center"><?php echo $lang['AddMembers'];?></h1>
    <form method="post" action="?do=insert" enctype="multipart/form-data">
    <div class="mb-3">
        <label class="form-label"><?php echo $lang['UserName'];?></label>
        <input type="text" class="form-control" name="username">
    </div>
    <div class="mb-3">
        <label for="exampleInputEmail1" class="form-label"><?php echo $lang['EMAIL'];?></label>
        <input type="email" class="form-control" name="email">
    </div>
    <div class="mb-3">
        <label for="exampleInputPassword1" class="form-label"><?php echo $lang['PASSWORD'];?></label>
        <input type="password" class="form-control" name="password">
    </div>
    <div class="mb-3">
        <label for="exampleInputEmail1" class="form-label"><?php echo $lang['FULLNAME'];?></label>
        <input type="text" class="form-control" name="fullname">
    </div>
    <div class="mb-3">
        <label for="formFile" class="form-label"><?php echo $lang['UPLOAD']?></label>
        <input class="form-control" type="file" id="formFile" name="avatar">
    </div>
    <button type="submit" class="btn btn-primary"><?php echo $lang['SUBMIT'];?></button>
</form>
</div>
        
    <?php elseif($do == "insert"):?>
        <?php 
            if($_SERVER['REQUEST_METHOD'] == "POST")
            {
                // $avatar = $_FILES['avatar'];
                $avatarName = $_FILES['avatar']['name'];
                $avatarType = $_FILES['avatar']['type'];
                $avatarTmpName = $_FILES['avatar']['tmp_name'];
                $avatarError = $_FILES['avatar']['error'];
                $avatarSize = $_FILES['avatar']['size'];
                // echo "<pre>";
                // print_r($avatar);
                // echo "</pre>";
                $avatarAllowedExtension = array("image/jpeg" , "image/png" , "image/jpg");
                if(in_array($avatarType , $avatarAllowedExtension))
                {
                    $avatar = rand(0 , 1000)."_".$avatarName;
                    $destination = "public\image\uploads\members\\".$avatar;
                    move_uploaded_file($avatarTmpName , $destination);
                }
                $username = $_POST['username'];
                $email = $_POST['email'];
                $password = sha1($_POST['password']);
                $fullname = $_POST['fullname'];
                // start back-end validation
                $formErrors = array();
                if(empty($username))
                {
                    $formErrors[] = "<h1>" . "Username must not be empty" . "</h1>";
                }
                //Try to user || (strlen($username)<4) but For best practice to only print one condition if the input is empty or less than 4 characters use elseif
                elseif(strlen($username) < 4)
                {
                    $formErrors[] = "<h1>" . "Sorry, Username must not be less then 4 characters" . "</h1>";
                }
                elseif(empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL))
                {
                    $formErrors[] = "<h1>" . "Please enter your email in a valid manner" . "</h1>";
                }
                elseif(empty($password))
                {
                    $formErrors[] = "<h1>" . "Please enter your password" . "</h1>";
                }
                elseif(empty($fullname))
                {
                    $formErrors[] = "<h1>" . "Please enter your fullname" . "</h1>";
                }
                elseif($avatarError = 0)
                {
                    $formErrors[] = "<h1>" . "Please upload your photo" . "</h1>";
                }
                if(empty($formErrors))
                {
                    $stmt=$con->prepare("INSERT INTO users (username,password,email,fullname,groupid,created_at,path) VALUES (?,?,?,?,0,now(),?)");
                    $stmt->execute(array($username,$password,$email,$fullname,$avatar));
                    header("location:members.php?do=add");
                }
                else
                {
                    foreach($formErrors as $error)
                    {
                        echo $error . "<br>";
                        exit();
                    } 
                }
                // end back-end validation
            }
            else
            {
                header("location:members.php");
            }
        ?>
    <?php elseif($do == "edit"):?>
        <?php
        
        $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;
        $stmt = $con -> prepare("SELECT * FROM users WHERE user_id = ?");
        $stmt -> execute(array($userid));
        $row = $stmt->fetch();
        $count = $stmt -> rowCount();
        ?>
        <?php if($count == 1):?>
        <div class="container">
            <h1 class="text-center"><?php echo $lang['EditMembers']?></h1>
            <form method="post" action="?do=update" enctype="multipart/form-data">
    <div class="mb-3">
    <input type="hidden" class="form-control" value="<?= $row['user_id']?>" name="userid">
    <label for="exampleInputEmail1" class="form-label"><?php echo $lang['UserName'];?></label>
    <input type="text" class="form-control" value="<?= $row['username']?>" name="username">
</div>
<div class="mb-3">
    <label for="exampleInputPassword1" class="form-label"><?php echo $lang['PASSWORD'];?></label>
    <input type="password" class="form-control" id="exampleInputPassword1" name="newpassword">
    <input type="hidden" class="form-control" id="exampleInputPassword1" value="<?= $row['password']?>" name="oldpassword">
</div>
<div class="mb-3">
    <label for="exampleInputPassword1" class="form-label"><?php echo $lang['GROUPID'];?></label>
    <input type="password" class="form-control" id="exampleInputPassword1" name="newgroupid">
    <input type="hidden" class="form-control" id="exampleInputPassword1" value="<?= $row['groupid']?>" name="oldgroupid">
</div>
<div class="mb-3">
    <label for="exampleInputEmail1" class="form-label"><?php echo $lang['EMAIL'];?></label> 
    <input type="email" class="form-control" value="<?= $row['email']?>" name="email">
</div>
<div class="mb-3">
    <label for="exampleInputEmail1" class="form-label"><?php echo $lang['FULLNAME'];?></label>
    <input type="text" class="form-control" value="<?= $row['fullname']?>" name="fullname">
</div>
<div class="mb-3">
    <label for="formFile" class="form-label"><?php echo $lang['UPLOAD']?></label>
    <input class="form-control" type="file" id="formFile" name="avatar">
</div>
<button type="submit" class="btn btn-primary"><?php echo $lang['Update'];?></button>
</form>
</div>
        <?php endif?>
    <?php elseif($do == "update"):?>
        <?php
            if($_SERVER["REQUEST_METHOD"] == "POST")
            {
                $userid = $_POST['userid'];
                $username = $_POST["username"];
                $email = $_POST["email"];
                $fullname = $_POST["fullname"];
                $avatarName = $_FILES['avatar']['name'];
                $avatarType = $_FILES['avatar']['type'];
                $avatarTmpName = $_FILES['avatar']['tmp_name'];
                $avatarAllowedExtension = array("image/png" , "image/jpg" , "image/jpeg");
                if(in_array($avatarType , $avatarAllowedExtension))
                {
                    $avatar = rand(0 , 1000)."_".$avatarName;
                    $destination = "public\image\uploads\members\\".$avatar;
                    move_uploaded_file($avatarTmpName , $destination);
                }
                $groupID = empty($_POST['newgroupid']) ? $_POST['oldgroupid'] : $_POST['newgroupid'];
                $password = empty($_POST['newpassword']) ? $_POST['oldpassword'] : $_POST['newpassword'];
                $hashedPass = sha1($password);
                $stmt = $con -> prepare("UPDATE users SET username=? , password=?, email=? , fullname=?, groupid=?, path=? WHERE user_id=?");
                $stmt -> execute(array($username , $hashedPass , $email , $fullname , $groupID , $avatar , $userid));
                header("location:members.php");
            }
        ?>
    <?php elseif($do == "delete"):?>
        <?php
                $userid = $_GET["userid"];
                $stmt = $con -> prepare("DELETE FROM users WHERE user_id=?");
                $stmt -> execute(array($userid));
            ?>
    <?php elseif($do == "show"):?>
        <?php 
            $userid = $_GET["userid"];
            $stmt=$con->prepare("SELECT * FROM users WHERE user_id=?");
            $stmt->execute(array($userid));
            $row=$stmt->fetch();
            echo"<pre>";
            print_r($row);
            echo"</pre>";
        ?>
        <a href="members.php" class="btn btn-dark m-2"><?php echo $lang['BACK'];?></a>
        <?php endif?>
    <!-- THIS endif is for the above elseifs-->
    <?php include "resources/includes/footer.inc"?>
<!-- End Member CRUD page-->
<?php else:?>
    <?php header("location:index.php")?>
<?php endif?>
<!-- THIS endif is for the $_SESSION['USER_NAME']-->