<?php
include 'db_conn.php'; // Include database connection

// 1. Check if the request method is POST and the correct data is set
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['selected_students'])) {

    // 2. Retrieve the comma-separated string
    $selected_students_str = $_POST['selected_students'];

    // Check if the string is not empty
    if (!empty($selected_students_str)) {
        // 3. Process the data: Split into an array
        $selected_students_array = explode(',', $selected_students_str);

        // 4. Sanitize the data and prepare for the IN clause
        $sanitized_students = [];
        foreach ($selected_students_array as $student_no) {
            // Trim whitespace and ensure it's not empty before escaping
            $trimmed_student_no = trim($student_no);
            if (!empty($trimmed_student_no)) {
                // Escape the string for SQL safety and add quotes for the IN clause
                $sanitized_students[] = "'" . mysqli_real_escape_string($conn, $trimmed_student_no) . "'";
            }
        }

        // Proceed only if there are valid, sanitized student numbers
        if (!empty($sanitized_students)) {
            // 5. Construct the SQL query using IN clause
            $in_clause = implode(',', $sanitized_students);
            $sql = "DELETE FROM `bcp_sms3_alumnidata1` WHERE `student_no` IN ($in_clause)";

            // 6. Execute the query
            if (mysqli_query($conn, $sql)) {
                // Check how many rows were affected (optional but good practice)
                $affected_rows = mysqli_affected_rows($conn);
                header("Location: student-data.php?msg=$affected_rows record(s) deleted successfully");
            } else {
                // Provide more specific error if possible
                header("Location: student-data.php?msg=Error deleting records: " . urlencode(mysqli_error($conn)));
            }
        } else {
            // No valid student numbers were found after sanitization
            header("Location: student-data.php?msg=No valid student numbers provided for deletion.");
        }
    } else {
        // The 'selected_students' input was empty
        header("Location: student-data.php?msg=No students selected for deletion.");
    }

    $conn->close(); // Close connection
    exit(); // Stop script execution after processing

} else {
    // Redirect if accessed directly or without the correct POST data
    header("Location: student-data.php?msg=Invalid request method or missing data.");
    exit();
}
?>
