<?php
    function countItem($id , $table)
    {
        global $con;
        $users = "WHERE groupid=0";
        $products = "WHERE product_discount=10";
        $tableName = array($users , $products);
        if($users == "WHERE groupid=0")
        {
            $stmt2 = $con -> prepare("SELECT COUNT($id) FROM $table $users");
            $stmt2 -> execute();
            $count = $stmt2->fetchColumn();
            return $count;
        }
        if($products == "WHERE product_discount=10")
        {
            $stmt2 = $con -> prepare("SELECT COUNT($id) FROM $table $products");
            $stmt2 -> execute();
            $count = $stmt2->fetchColumn();
            return $count;
        }
    }
?>