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
            $stmt = $con->prepare("SELECT * FROM products WHERE product_discount=10");
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
        <th scope="col"><?php echo $lang['ProductName'];?></th>
        <th scope="col"><?php echo $lang['ProductCategory'];?></th>
        <th scope="col">Product Price</th>
        <th scope="col"><?php echo $lang['CreatedAT'];?></th>
        <th scope="col"><?php echo $lang['CONTROL'];?></th>
    </tr>
    </thead>
    <tbody>
    <?php foreach($products as $product):?>
    <tr>
        <th scope="row"><?= $product['product_name']?></th>
        <td><?= $product['product_category']?></td>
        <td><?= $product['product_price']?></td>
        <td><?= $product['created_at']?></td>
        <td>
            <a class="btn btn-info m-1" href="?do=show&productID=<?= $product['product_id']?>" title="Show">
                <i class="fas fa-eye"></i>
            </a>
            <a class="btn btn-warning m-1" href="?do=edit&productID=<?= $product['product_id']?>" title="Edit">
                <i class="fas fa-edit"></i></a>
            </a>
            <a class="btn btn-danger m-1" href="?do=delete&productID=<?= $product['product_id']?>" title="Delete">
                <i class="fas fa-trash"></i>
            </a>
        </td>
    </tr>
    <?php endforeach?>
    </tbody>
</table>
        </div>

        <?php elseif($do == "add"):?>
            <div class="container">
                <h1 class="text-center"><?php echo $lang['AddProducts'];?></h1>

    <form method="post" action="?do=insert">
    <div class="mb-3">
        <label for="exampleInputEmail1" class="form-label"><?php echo $lang['ProductName'];?></label>
        <input type="text" class="form-control" name="productName">
    </div>
    <div class="mb-3">
        <label class="form-label"><?php echo $lang['ProductCategory'];?></label>
        <input type="text" class="form-control" name="productCategory">
    </div>
    <div class="mb-3">
        <label class="form-label"><?php echo $lang['ProductDiscount'];?></label>
        <input type="text" class="form-control" name="productDiscount">
    </div>
    <div class="mb-3">
        <label class="form-label">Product Price</label>
        <input type="text" class="form-control" name="productPrice">
    </div>
    <button type="submit" class="btn btn-primary"><?php echo $lang['SUBMIT'];?></button>
</form>
</div>

        <?php elseif($do == "insert"):?>
        <?php 
            if($_SERVER['REQUEST_METHOD']=="POST")
            {
                $productName = $_POST['productName'];
                $productCategory = $_POST['productCategory'];
                $productDiscount = $_POST['productDiscount'];
                $productPrice = $_POST['productPrice'];
                $stmt = $con -> prepare("INSERT INTO products (product_name,product_category,product_discount,product_price,created_at) VALUES (?,?,?,?,now())");
                $stmt -> execute(array($productName,$productCategory,$productDiscount,$productPrice));
                header("location:products.php?do=add");
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

            <h1 class="text-center">Edit Product</h1>

            <form method="post" action="?do=update">
    <div class="mb-3">
    <input type="hidden" class="form-control" value="<?= $product['product_id']?>" name="productID">
    <label for="exampleInputEmail1" class="form-label">Product Name</label>
    <input type="text" class="form-control" value="<?= $product['product_name']?>" name="productName">
</div>
<div class="mb-3">
    <label for="exampleInputEmail1" class="form-label">Product Category</label> 
    <input type="text" class="form-control" value="<?= $product['product_category']?>" name="productcategory">
</div>
<div class="mb-3">
    <label for="exampleInputEmail1" class="form-label">Product Price</label> 
    <input type="text" class="form-control" value="<?= $product['product_price']?>" name="productprice">
</div>
<div class="mb-3">
    <label for="exampleInputPassword1" class="form-label">Product Discount</label>
    <input type="text" class="form-control" id="exampleInputPassword1" name="newdiscount">
    <input type="hidden" class="form-control" id="exampleInputPassword1" value="<?= $product['product_discount']?>" name="olddiscount">
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
                    $productDiscount =empty($_POST['newdiscount']) ? $_POST['olddiscount'] : $_POST['newdiscount'];
                    $stmt = $con -> prepare("UPDATE products SET product_name=? , product_category=? , product_discount=? , product_price=? WHERE product_id=?");
                    $stmt -> execute(array($productName , $productCategory , $productDiscount , $productPrice , $productID));
                    header("location:products.php");
                }
            ?>

        <?php elseif($do == "delete"):?>
            <?php
                $productID = $_GET["productID"];
                $stmt = $con -> prepare("DELETE FROM products WHERE product_id=?");
                $stmt -> execute(array($productID));
                header("location:members.php");
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
