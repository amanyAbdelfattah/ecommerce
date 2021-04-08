<?php
    function countItem($id , $table)
    {
        global $con;
        $users = "WHERE groupid=0";
        $products = "";
        // $tableName = array($users , $products);
        if($users == "WHERE groupid=0")
        {
            $stmt2 = $con -> prepare("SELECT COUNT($id) FROM $table $users");
            $stmt2 -> execute(array($users));
            $count = $stmt2->fetchColumn();
            return $count;
        }
        elseif($products == "")
        {
            $stmt2 = $con -> prepare("SELECT COUNT($id) FROM $table $products");
            $stmt2 -> execute();
            $count = $stmt2->fetchColumn();
            return $count;
        }
    }
?>