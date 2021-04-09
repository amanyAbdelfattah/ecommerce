<?php session_start()?>
<?php if(isset($_SESSION['USER_NAME'])):?>
    <?php include "resources/functions/function.php"?>
    <?php include "resources/includes/header.inc"?>
    <?php require "config.php"?>
    <?php include "resources/includes/navbar.inc"?>
    <div class="container">
        <div class="row justify-content-center mt-3">
            <div class="col-lg-4">
                <div class="members">
                    <a href="members.php" title="Number of Members">
                    <i class="fas fa-users"></i>
                    <?php echo countItem("user_id","users","groupid = 0")?>
                    </a>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="products">
                    <a href="products.php" title="Number of Products">
                    <i class="fas fa-cart-plus"></i>
                    <?php echo countItem("product_discount","products")?>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <?php include "resources/includes/footer.inc"?>
<?php else:?>
<?php header("location:index.php")?>
<?php endif?> 