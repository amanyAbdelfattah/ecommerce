<?php
    // function countItem($id , $table)
    // {
    //     global $con;
    //     $stmt2 = $con -> prepare("SELECT COUNT($id) FROM $table WHERE groupid=0");
    //     $stmt2 -> execute();
    //     $count = $stmt2->fetchColumn();
    //     return $count;

    // }
    // function Discounts($id , $table)
    // {
    //     global $con;
    //     $stmt3 = $con -> prepare("SELECT COUNT($id) FROM $table WHERE product_discount=10");
    //     $stmt3 -> execute();
    //     $count = $stmt3->fetchColumn();
    //     return $count;
    // }
    function countItem()
    {
        global $con;
        $stmt2 = $con -> prepare("SELECT (select count(*) from users where user_id = '0') + (select count(*) from products where product_discount = '10')") ;
        $stmt2 -> execute();
        $count = $stmt2->fetchColumn();
        return $count;
        // $stmt2 -> execute();
        // $count = $stmt2->fetchColumn()
    }

?>
