<?php
include "db_conn.php";
$id = $_GET['id'];
$sql = "DELETE FROM `past_events` WHERE id = $id";
$result = mysqli_query($conn, $sql);
if($result){
    header("Location: past_events.php?msg=Record deleted Successfuly");

} 
else{
    echo "Failed:" . mysqli_error($conn);
}
?>