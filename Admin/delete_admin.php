<?php
include 'db.php';
if(isset($_GET['deleteid'])){
    $id= $_GET['deleteid'];


    $sql= "delete from admin where id = $id";
    $result= mysqli_query($conn,$sql);
    if($result){
        //echo "Record deleted successfully!"
        header('Location: fetch_admin.php');


    }else{
        die(mysqli_error($con));

    
    }
}

?>