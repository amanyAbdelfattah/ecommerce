<?php
    function countItem($id , $table , $condition=null)
    {
            global $con;
            if($condition == "groupid = 0")
            {
                $stmt2 = $con -> prepare("SELECT COUNT($id) FROM $table WHERE $condition");
                $stmt2 -> execute();
                $count = $stmt2->fetchColumn();
                return $count;
            }
            else
            {
                $stmt2 = $con -> prepare("SELECT COUNT($id) FROM $table"); 
                //We want to count all products not a specific condition
                $stmt2 -> execute();
                $count = $stmt2->fetchColumn();
                return $count;
            }
    }
?>