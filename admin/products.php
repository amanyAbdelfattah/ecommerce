<?php
session_start();
$do = "";
if(isset($_GET['do']))
{
    $do = $_GET['do'];
}
else
{
    $do="manage";
}
?>
<?php if(isset($_SESSION['USER_NAME'])): ?>
        <?php include "resources/includes/header.inc"?>
        <?php require "config.php"?>
        <?php include "resources/includes/navbar.inc"?>

        <?php if($do == "manage"):?>
        <?php
            $show_per_page = 5;
            $page = isset($_GET['page']) ? $_GET['page'] : 1;
            $start_from = ($page-1) * $show_per_page;
            $stmt = $con->prepare("SELECT products.* , categories.cat_name FROM products INNER JOIN categories ON categories.cat_id=products.cat_id LIMIT $start_from , $show_per_page");
            $stmt -> execute();
            $products = $stmt->fetchAll();
        ?>
        <div class="container">
            <h1 class="text-center"><?php echo $lang['Products'];?></h1>
            <a class="btn btn-primary m-3" href="?do=add">
                <i class="fas fa-cart-plus"></i> <?php echo $lang['AddProducts'];?>
            </a>
            <table class="table table-striped">
    <thead>
    <tr>
        <th scope="col"><?php echo $lang['Product Photo']?></th>    
        <th scope="col"><?php echo $lang['ProductName'];?></th>
        <th scope="col"><?php echo $lang['ProductCategory'];?></th>
        <th scope="col"><?php echo $lang['ProductPrice']?></th>
        <th scope="col"><?php echo $lang['CreatedAT'];?></th>
        <th scope="col"><?php echo $lang['CONTROL'];?></th>
    </tr>
    </thead>
    <tbody>
    <?php foreach($products as $product):?>
    <tr>
        <th scope="row">
        <img style="height: 15vh;" src="public\image\uploads\products\<?= $product['product_img']?>" alt="<?= $product['product_img']?>">
        </th>
        <th scope="row"><?= $product['product_name']?></th>
        <td><?= $product['cat_name']?></td>
        <td><?= $product['product_price']?></td>
        <td><?= $product['created_at']?></td>
        <td>
            <a class="btn btn-info m-1" href="?do=show&productID=<?= $product['product_id']?>" title="<?php echo $lang['SHOW'];?>">
                <i class="fas fa-eye"></i>
            </a>
            <?php if($_SESSION['GROUP_ID'] == 1):?>
            <a class="btn btn-warning m-1" href="?do=edit&productID=<?= $product['product_id']?>" title="<?php echo $lang['EDIT'];?>">
                <i class="fas fa-edit"></i></a>
            </a>
            <a class="btn btn-danger m-1" href="?do=delete&productID=<?= $product['product_id']?>" title="<?php echo $lang['Delete'];?>">
                <i class="fas fa-trash"></i>
            </a>
            <?php endif?>
        </td>
    </tr>
    <?php endforeach?>
    </tbody>
</table>
<?php 
    $stmt = $con -> prepare("SELECT * FROM products WHERE product_discount=10 ORDER BY product_id DESC");
    $stmt -> execute();
    $products_recorded = $stmt -> rowCount();
    $total_page = ceil($products_recorded / $show_per_page);
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
        </div>

        <?php elseif($do == "add"):?>
            <div class="container">
                <h1 class="text-center"><?php echo $lang['AddProducts'];?></h1>

    <form method="post" action="?do=insert" enctype="multipart/form-data">
    <div class="mb-3">
        <label for="exampleInputEmail1" class="form-label"><?php echo $lang['ProductName'];?></label>
        <input type="text" class="form-control" name="productName">
    </div>
    <?php 
        $stmt = $con -> prepare("SELECT * FROM categories");
        $stmt -> execute();
        $products = $stmt -> fetchAll();
    ?>
    <div class="mb-3">
        <label class="form-label"><?php echo $lang['ProductDiscount'];?></label>
        <input type="text" class="form-control" name="productDiscount">
    </div>
    <div class="mb-3">
        <label class="form-label"><?php echo $lang['ProductPrice']?></label>
        <input type="text" class="form-control" name="productPrice">
    </div>
    <div class="mb-3">
    <select class="form-select" aria-label="Default select example" name="productCategory">
        <option selected>Select Category</option>
        <?php foreach($products as $product):?>
        <option value="<?= $product['cat_id']?>"><?= $product['cat_name']?></option>
        <?php endforeach?>
    </select>
    </div>
    <div class="mb-3">
        <label for="formFile" class="form-label">Upload Product Image</label>
        <input class="form-control" type="file" id="formFile" name="avatar">
    </div>
    <button type="submit" class="btn btn-primary"><?php echo $lang['SUBMIT'];?></button>
</form>
</div>

        <?php elseif($do == "insert"):?>
        <?php 
            if($_SERVER['REQUEST_METHOD']=="POST")
            {
                $ImageName = $_FILES['avatar']['name'];
                $ImageType = $_FILES['avatar']['type'];
                $ImageTmpName = $_FILES['avatar']['tmp_name'];
                $ImageError = $_FILES['avatar']['error'];
                $ImageSize = $_FILES['avatar']['size'];
                $imageAllowedExtension = array("image/jpg" , "image/jpeg" , "image/png");
                if(in_array($ImageType , $imageAllowedExtension))
                {
                    $avatar = rand(0 , 1000)."_".$ImageName;
                    $destination = "public\image\uploads\products\\".$avatar;
                    move_uploaded_file($ImageTmpName , $destination);
                }
                $productName = $_POST['productName'];
                $productCategory = $_POST['productCategory'];
                $productDiscount = $_POST['productDiscount'];
                $productPrice = $_POST['productPrice'];
                $formErrors = array();
                if(empty($productName))
                {
                    $formErrors[] = "<h1>" . "Please enter the product name" . "</h1>";
                }
                elseif(empty($productCategory))
                {
                    $formErrors[] = "<h1>" . "Please enter the product category" . "</h1>";
                }
                elseif(empty($productDiscount))
                {
                    $formErrors[] = "<h1>" . "Please enter the product discount" . "</h1>";
                }
                elseif(empty($productPrice))
                {
                    $formErrors[] = "<h1>" . "Please enter the product price" . "</h1>";
                }
                elseif(empty($avatar))
                {
                    $formErrors[] = "<h1>" . "Please upload the product image" . "</h1>";
                }
                if(empty($formErrors))
                {
                    $stmt = $con -> prepare("INSERT INTO products (product_name,cat_id,product_discount,product_price,created_at,product_img) VALUES (?,?,?,?,now(),?)");
                    $stmt -> execute(array($productName,$productCategory,$productDiscount,$productPrice,$avatar));
                    header("location:products.php?do=add");
                }
                else
                {
                    foreach($formErrors as $error)
                    {
                        echo $error . "<br>";
                        exit();
                    }
                }
                
            }
            else
            {
                header("location:products.php");
            }
        ?>

        <?php elseif($do == "edit"):?>
        <?php 
            $productID = isset($_GET['productID']) && is_numeric($_GET['productID']) ? intval($_GET['productID']) : 0;
            $stmt = $con -> prepare("SELECT * FROM products WHERE product_id = ?");
            $stmt -> execute(array($productID));
            $product = $stmt->fetch();
            $count = $stmt -> rowCount();
        ?>
        <?php if($count == 1):?>
            <div class="container">

            <h1 class="text-center"><?php echo $lang['EditProduct']?></h1>

            <form method="post" action="?do=update" enctype="multipart/form-data">
    <div class="mb-3">
    <input type="hidden" class="form-control" value="<?= $product['product_id']?>" name="productID">
    <label for="exampleInputEmail1" class="form-label"><?php echo $lang['ProductName'];?></label>
    <input type="text" class="form-control" value="<?= $product['product_name']?>" name="productName">
</div>
<div class="mb-3">
    <label for="exampleInputEmail1" class="form-label"><?php echo $lang['ProductCategory'];?></label> 
    <input type="text" class="form-control" value="<?= $product['cat_id']?>" name="productcategory">
</div>
<div class="mb-3">
    <label for="exampleInputEmail1" class="form-label"><?php echo $lang['ProductPrice']?></label> 
    <input type="number" class="form-control" value="<?= $product['product_price']?>" name="productprice">
</div>
<div class="mb-3">
    <label for="exampleInputPassword1" class="form-label"><?php echo $lang['ProductDiscount'];?></label>
    <input type="number" class="form-control" id="exampleInputPassword1" name="newdiscount">
    <input type="hidden" class="form-control" id="exampleInputPassword1" value="<?= $product['product_discount']?>" name="olddiscount">
</div>
<div class="mb-3">
    <label for="formFile" class="form-label"><?php echo $lang['UPLOAD']?></label>
    <input class="form-control" type="file" id="formFile" name="avatar">
</div>
<button type="submit" class="btn btn-primary">Update</button>
</form>
</div>
        <?php endif?>

        <?php elseif($do == "update"):?>
            <?php 
                if($_SERVER['REQUEST_METHOD'] == "POST")
                {
                    $productID =$_POST['productID'];
                    $productName =$_POST['productName'];
                    $productCategory =$_POST['productcategory'];
                    $productPrice =$_POST['productprice'];
                    $ImageName = $_FILES['avatar']['name'];
                    $ImageType = $_FILES['avatar']['type'];
                    $ImageTmpName = $_FILES['avatar']['tmp_name'];
                    $imageAllowedExtension = array("image/jpg" , "image/jpeg" , "image/png");
                if(in_array($ImageType , $imageAllowedExtension))
                {
                    $avatar = rand(0 , 1000)."_".$ImageName;
                    $destination = "public\image\uploads\products\\".$avatar;
                    move_uploaded_file($ImageTmpName , $destination);
                }
                    $productDiscount =empty($_POST['newdiscount']) ? $_POST['olddiscount'] : $_POST['newdiscount'];
                    $stmt = $con -> prepare("UPDATE products SET product_name=? , cat_id=? , product_discount=? , product_price=?, product_img=? WHERE product_id=?");
                    $stmt -> execute(array($productName , $productCategory , $productDiscount , $productPrice , $avatar , $productID));
                    header("location:products.php");
                }
            ?>

        <?php elseif($do == "delete"):?>
            <?php
                $productID = $_GET["productID"];
                $stmt = $con -> prepare("DELETE FROM products WHERE product_id=?");
                $stmt -> execute(array($productID));
                header("location:products.php");
            ?>
        <?php elseif($do == "show"):?>
            <?php 
                $productID = $_GET["productID"];
                $stmt = $con -> prepare("SELECT * FROM products WHERE product_id=?");
                $stmt -> execute(array($productID));
                $product = $stmt->fetch();
                echo"<pre>";
                print_r($product);
                echo"</pre>";
            ?>
            <a href="products.php" class="btn btn-dark m-2"><?php echo $lang['BACK'];?></a>
        <?php endif?>

        <?php include "resources/includes/footer.inc"?>
        <?php else:?>
            <?php header("location:index.php")?>
        <?php endif?>
