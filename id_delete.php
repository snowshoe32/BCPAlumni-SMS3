<?php
session_start();
if (!isset($_SESSION['admin_name']) && !isset($_SESSION['super_admin_name'])) {
    header('Location: index.php');
    exit();
}

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

<aside id="sidebar" class="sidebar">
  <ul class="sidebar-nav" id="sidebar-nav">
    <hr class="sidebar-divider">
    <li class="nav-item">
      <a class="nav-link collapsed" data-bs-target="#alumnidata-nav" data-bs-toggle="collapse" href="#">
        <i class="bi bi-layout-text-window-reverse"></i><span>Alumni Data</span><i class="bi bi-chevron-down ms-auto"></i>
      </a>
      <ul id="alumnidata-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
        <li>
          <a href="student-data.php">
            <i class="bi bi-circle"></i><span>Manage Alumni Data</span>
          </a>
        </li> 
        <li>
          <a href="add.php">
            <i class="bi bi-circle"></i><span>Add new Alumni</span>
          </a>
        </li>
      </ul>
    </li><!-- End System Nav -->
    <hr class="sidebar-divider">
    <li class="nav-item">
      <a class="nav-link collapsed" data-bs-target="#careers-nav" data-bs-toggle="collapse" href="#">
        <i class="bi bi-layout-text-window-reverse"></i><span>Career Opportunities</span><i class="bi bi-chevron-down ms-auto"></i>
      </a>
      <ul id="careers-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
        <li>
          <a href="job-post-manage.php">
            <i class="bi bi-circle"></i><span>Manage Job Posting</span>
          </a>
        </li>
        <li>
          <a href="job-post-add.php">
            <i class="bi bi-circle"></i><span>Add Job Posting</span>
          </a>
        </li>
      </ul>
    </li><!-- Career Opportunities -->
    <hr class="sidebar-divider">
    <li class="nav-item">
      <a class="nav-link collapsed" data-bs-target="#students-nav" data-bs-toggle="collapse" href="#">
        <i class="bi bi-layout-text-window-reverse"></i><span>Student Alumni Services</span><i class="bi bi-chevron-down ms-auto"></i>
      </a>
      <ul id="students-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
        <li>
          <a href="id_manage.php">
            <i class="bi bi-circle"></i><span>Manage Alumni ID Applications</span>
          </a>
        </li>
        <li>
          <a href="admin_tracer.php">
            <i class="bi bi-circle"></i><span>News & Announcements</span>
          </a>
        </li>
      </ul>
    </li>
  </ul>
</aside>