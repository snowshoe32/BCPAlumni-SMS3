<?php
include "db_conn.php";
$id = $_GET['id'];
$sql = "DELETE FROM `crud` WHERE id = $id";
$result = mysqli_query($conn, $sql);
if($result){
    header("Location: student-data.php?msg=Record deleted Successfuly");

} 
else{
    echo "Failed:" . mysqli_error($conn);
}
?>