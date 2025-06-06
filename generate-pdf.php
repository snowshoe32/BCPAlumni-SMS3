<?php
require __DIR__ . '/vendor/autoload.php'; // Ensure Dompdf is autoloaded from the vendor folder

use Dompdf\Dompdf;

if (class_exists(Dompdf::class)) { // Check if Dompdf is loaded
    if (isset($_GET['id'])) {
        include "db_conn.php";
        $id = intval($_GET['id']);
        $sql = "SELECT * FROM `bcp-sms3_job` WHERE id = $id";
        $result = mysqli_query($conn, $sql);

        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);

            // Prepare HTML content for the PDF
            $html = "
            <style>
                body { font-family: Arial, sans-serif; }
                h1 { text-align: center; }
                p { margin: 5px 0; }
            </style>
            <h1>Job Posting Details</h1>
            <p><strong>Job Title:</strong> {$row['jobtitle']}</p>
            <p><strong>Location:</strong> {$row['location']}</p>
            <p><strong>Email:</strong> {$row['email']}</p>
            <p><strong>Date:</strong> {$row['date']}</p>
            <p><strong>Source:</strong> {$row['source']}</p>
            <p><strong>Employer:</strong> {$row['employer']}</p>
            ";

            // Initialize Dompdf
            $dompdf = new Dompdf();
            $dompdf->set_option('isHtml5ParserEnabled', true);
            $dompdf->set_option('isRemoteEnabled', true);
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();

            // Output the generated PDF
            $dompdf->stream("job_posting_$id.pdf", ["Attachment" => true]);
        } else {
            echo "No job posting found with the given ID.";
        }
    } else {
        echo "Invalid request. No ID provided.";
    }
} else {
    echo "Dompdf is not properly installed. Please run 'composer require dompdf/dompdf'.";
}
?>
