<?php
// process_visit_request.php

// Include the database connection file
require 'db_connect.php'; // Script will stop if connection fails

$message = ""; // Variable to store success or error messages
$message_type = "danger"; // For Bootstrap alert styling (danger or success)

// Check if the form was submitted using POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // --- Get Data and Basic Sanitization ---
    // Use trim() to remove leading/trailing whitespace
    // Use isset() to ensure the variable exists in the POST array
    $full_name = isset($_POST['fullName']) ? trim($_POST['fullName']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';
    $visit_date = isset($_POST['visitDate']) ? trim($_POST['visitDate']) : '';
    $reason = isset($_POST['reason']) ? trim($_POST['reason']) : '';
    $pets_interest = isset($_POST['petsInterest']) ? trim($_POST['petsInterest']) : '';

    // --- Basic Server-Side Validation ---
    $errors = []; // Array to hold validation errors
    if (empty($full_name)) {
        $errors[] = "Full Name is required.";
    }
    if (empty($email)) {
        $errors[] = "Email is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) { // Validate email format
        $errors[] = "Invalid email format.";
    }
    if (empty($phone)) {
        $errors[] = "Phone Number is required.";
    }
    // Basic phone validation (allows digits, spaces, +, -, (, )) - adjust if needed
    // elseif (!preg_match('/^[0-9\s\+\-\(\)]+$/', $phone)) {
    //     $errors[] = "Invalid phone number format.";
    // }
    if (empty($visit_date)) {
        $errors[] = "Preferred Visit Date is required.";
    } else {
        // Optional: Check if date is valid and not in the past
        $today = date("Y-m-d");
        if ($visit_date < $today) {
            $errors[] = "Visit date cannot be in the past.";
        }
        // You could add more robust date validation if needed
    }
    if (empty($reason)) {
        $errors[] = "Reason for Visit is required.";
    }
    if (empty($pets_interest)) {
        $errors[] = "Interested Pet Category is required.";
    }

    // --- If Validation Passes, Insert into Database ---
    if (empty($errors)) {
        // Use Prepared Statements to prevent SQL Injection
        $sql = "INSERT INTO visit_requests (full_name, email, phone, visit_date, reason, pets_interest) VALUES (?, ?, ?, ?, ?, ?)";

        $stmt = mysqli_prepare($conn, $sql);

        if ($stmt) {
            // Bind parameters (s = string, s, s, s, s, s)
            mysqli_stmt_bind_param($stmt, "ssssss",
                $full_name,
                $email,
                $phone,
                $visit_date,
                $reason,
                $pets_interest
            );

            // Execute the statement
            if (mysqli_stmt_execute($stmt)) {
                $message = "Success! Your visit request has been submitted. We will contact you shortly.";
                $message_type = "success"; // Use Bootstrap success class
            } else {
                // Show specific SQL error during development ONLY
                // In production, log the error and show a generic message
                $message = "Error: Could not submit your request. Please try again later. " . mysqli_stmt_error($stmt);
                // Production message: $message = "Error: Could not submit your request. Please try again later.";
                $message_type = "danger";
            }

            // Close the statement
            mysqli_stmt_close($stmt);

        } else {
            // Error preparing the statement
             $message = "Error preparing database operation: " . mysqli_error($conn);
             $message_type = "danger";
        }

    } else {
        // If validation fails, concatenate errors into the message
        $message = "Please correct the following errors: <ul>";
        foreach ($errors as $error) {
            $message .= "<li>" . htmlspecialchars($error) . "</li>"; // Escape error message
        }
        $message .= "</ul>";
        $message_type = "warning"; // Use Bootstrap warning class
    }

    // --- Close the database connection ---
    mysqli_close($conn);

} else {
    // If accessed directly without POST data, redirect back to form or show message
     $message = "Invalid request method. Please submit the form.";
     $message_type = "warning";
     // Optional: Redirect back to the form
     // header('Location: book-a-visit.php');
     // exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visit Request Status - PawPeace</title>
    <!-- Include Bootstrap CSS for styling the message -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css"> <!-- Your base styles -->
    <style>
         /* Keep body gradient, but center content */
        body {
             display: flex;
             justify-content: center;
             align-items: center;
             min-height: 100vh;
             padding: 20px;
        }
        .status-container {
            background: rgba(255, 255, 255, 0.8);
            padding: 30px 40px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            max-width: 600px;
            text-align: center;
        }
        .status-container h1 {
            margin-bottom: 20px;
            color: #333;
        }
        .alert { /* Ensure alert is visible */
           text-align: left; /* Align list items left if using list for errors */
        }
    </style>
</head>
<body>
    <div class="status-container">
        <h1>Booking Status</h1>
        <!-- Display the message using Bootstrap alerts -->
        <div class="alert alert-<?php echo $message_type; ?>" role="alert">
            <?php echo $message; // Message is already escaped or constructed safely ?>
        </div>
        <br>
        <a href="book-a-visit.php" class="btn btn-primary">Back to Booking Form</a>
        <a href="another-index.php" class="btn btn-secondary">Go to Homepage</a> <!-- Adjust link if needed -->
    </div>

    <!-- Optional: Bootstrap JS for potential future components -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
</body>
</html>