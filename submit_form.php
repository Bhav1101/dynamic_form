<?php
include("db_connect.php");

// Start new submission
mysqli_query($conn, "INSERT INTO submissions () VALUES ()");
$submission_id = mysqli_insert_id($conn);

// Loop through posted form data
foreach ($_POST as $key => $value) {
    if (strpos($key, 'field_') === 0) {
        $field_id = str_replace('field_', '', $key);

        // Handle checkbox values (arrays)
        if (is_array($value)) {
            foreach ($value as $v) {
                $stmt = $conn->prepare("INSERT INTO form_responses (field_id, response, submission_id) VALUES (?, ?, ?)");
                $stmt->bind_param("isi", $field_id, $v, $submission_id);
                $stmt->execute();
            }
        } else {
            $stmt = $conn->prepare("INSERT INTO form_responses (field_id, response, submission_id) VALUES (?, ?, ?)");
            $stmt->bind_param("isi", $field_id, $value, $submission_id);
            $stmt->execute();
        }
    }
}

echo "<h2 style='text-align:center; color:green;'>✅ Your order has been submitted successfully!</h2>";
echo "<p style='text-align:center;'><a href='index.php'>Go back to form</a></p>";
?>
