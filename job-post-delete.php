<?php
include "db_conn.php";
$id = $_GET['id'];
$sql = "DELETE FROM `bcp-sms3_job` WHERE id = $id";
$result = mysqli_query($conn, $sql);
if($result){
    header("Location: job-post-manage.php?msg=Record deleted Successfuly");

} 
else{
    echo "Failed:" . mysqli_error($conn);
}
?>