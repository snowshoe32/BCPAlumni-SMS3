<?php
include "db_conn.php";
$id = $_GET['id'];
$sql = "DELETE FROM `bcp_sms3_idapprove` WHERE id = $id";
$result = mysqli_query($conn, $sql);
if($result){
    header("Location: id_manage.php?msg=Record deleted Successfuly");

} 
else{
    echo "Failed:" . mysqli_error($conn);
}
?>