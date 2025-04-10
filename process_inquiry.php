<?php
// process_inquiry.php

// Include database connection
require 'db_connect.php';

$message = "";
$message_type = "danger"; // Default to error styling

// Check if form was submitted via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // --- Get data and sanitize ---
    $firstName = isset($_POST['firstName']) ? trim($_POST['firstName']) : '';
    $lastName = isset($_POST['lastName']) ? trim($_POST['lastName']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';
    $address = isset($_POST['address']) ? trim($_POST['address']) : '';
    $petType = isset($_POST['petType']) ? trim($_POST['petType']) : '';
    $inquiryType = isset($_POST['inquiryType']) ? trim($_POST['inquiryType']) : '';
    $petExperience = isset($_POST['petExperience']) ? trim($_POST['petExperience']) : ''; // 'yes' or 'no'

    // Conditional fields - get them only if experience is 'yes', otherwise set to NULL for DB
    $pastPets = null;
    $careExperience = null;
    if ($petExperience === 'yes') {
        $pastPets = isset($_POST['pastPets']) ? trim($_POST['pastPets']) : null; // Allow empty string if submitted but blank
        $careExperience = isset($_POST['careExperience']) ? trim($_POST['careExperience']) : null; // Allow empty string
        // Treat empty strings from the form as NULL for the database if desired
        if ($pastPets === '') $pastPets = null;
        if ($careExperience === '') $careExperience = null;
    }

    // Optional field
    $additionalInfo = isset($_POST['additionalInfo']) ? trim($_POST['additionalInfo']) : null;
    if ($additionalInfo === '') $additionalInfo = null; // Treat empty string as NULL


    // --- Server-side Validation ---
    $errors = [];
    if (empty($firstName)) $errors[] = "First Name is required.";
    if (empty($lastName)) $errors[] = "Last Name is required.";
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "A valid Email Address is required.";
    if (empty($phone)) $errors[] = "Phone Number is required.";
    if (empty($address)) $errors[] = "Address is required.";
    if (empty($petType)) $errors[] = "Pet Type selection is required.";
    if (empty($inquiryType)) $errors[] = "Rent or Adopt selection is required.";
    if (empty($petExperience)) $errors[] = "Pet Experience selection is required.";

    // If experience is 'yes', check the conditional fields ONLY IF you want them required when visible
    // if ($petExperience === 'yes') {
    //     if (empty($pastPets)) $errors[] = "Past Pets description is required when experience is 'Yes'.";
    //     if (empty($careExperience)) $errors[] = "Care Experience description is required when experience is 'Yes'.";
    // }


    // --- Process if no validation errors ---
    if (empty($errors)) {
        // Prepare SQL statement (Prevents SQL Injection)
        $sql = "INSERT INTO pet_inquiries (first_name, last_name, email, phone, address, pet_type, inquiry_type, has_experience, past_pets, care_experience, additional_info) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = mysqli_prepare($conn, $sql);

        if ($stmt) {
            // Bind parameters to the statement
            // s = string, determines type for binding
            // Order must match the VALUES(?, ?, ...) order
            mysqli_stmt_bind_param($stmt, "sssssssssss", // 11 strings
                $firstName,
                $lastName,
                $email,
                $phone,
                $address,
                $petType,
                $inquiryType,
                $petExperience, // Storing 'yes' or 'no'
                $pastPets,       // Bound as string, handles NULL
                $careExperience, // Bound as string, handles NULL
                $additionalInfo  // Bound as string, handles NULL
            );

            // Execute the statement
            if (mysqli_stmt_execute($stmt)) {
                $message = "Success! Your inquiry has been submitted. We will contact you soon.";
                $message_type = "success";
            } else {
                $message = "Error: Could not submit your inquiry. Please try again later. " . mysqli_stmt_error($stmt);
                // In production, log the mysqli_stmt_error($stmt) instead of showing it.
                $message_type = "danger";
            }

            // Close the statement
            mysqli_stmt_close($stmt);

        } else {
            $message = "Error preparing database operation: " . mysqli_error($conn);
             // In production, log the mysqli_error($conn) instead of showing it.
            $message_type = "danger";
        }

    } else {
        // Validation failed - build error message
        $message = "Please correct the following errors: <ul>";
        foreach ($errors as $error) {
            $message .= "<li>" . htmlspecialchars($error) . "</li>";
        }
        $message .= "</ul>";
        $message_type = "warning"; // Use warning for validation errors
    }

    // Close the database connection
    mysqli_close($conn);

} else {
    // Not a POST request, redirect or show error
    $message = "Invalid request method. Please submit the form.";
    $message_type = "warning";
    // Optional: Redirect back
    // header('Location: contact.php');
    // exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inquiry Status - PawPeace</title>
    <!-- Bootstrap CSS for styling -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <!-- You might want to link your main stylesheet too for consistency -->
    <!-- <link rel="stylesheet" href="style.css"> -->
    <style>
        /* Simple styling for the status page */
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
            background: linear-gradient(300deg, rgba(135,206,235,0.8), rgba(152,255,152,0.8), rgba(135,206,235,0.8));
            background-size: 180% 180%;
            animation: gradient-animation 8s ease infinite;
            font-family: Poppins, sans-serif;
        }
        @keyframes gradient-animation { 0% { background-position: 0% 50%; } 50% { background-position: 100% 50%; } 100% { background-position: 0% 50%; } }
        .status-container {
            background: rgba(255, 255, 255, 0.9);
            padding: 30px 40px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            max-width: 600px;
            text-align: center;
            color: #333; /* Dark text for readability on light background */
        }
        .status-container h1 {
            margin-bottom: 20px;
            color: #333;
        }
         .alert ul {
            text-align: left;
            margin-bottom: 0; /* Remove extra space below list in alert */
            padding-left: 20px; /* Indent list items */
        }
        .alert li {
             margin-bottom: 5px;
        }
    </style>
</head>
<body>
    <div class="status-container">
        <h1>Inquiry Submission Status</h1>
        <div class="alert alert-<?php echo $message_type; ?>" role="alert">
            <?php echo $message; // Output the success/error message ?>
        </div>
        <br>
        <a href="contact.php" class="btn btn-primary">Back to Contact Form</a>
        <a href="another-index.html" class="btn btn-secondary">Go to Homepage</a> <!-- Adjust link if needed -->
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
</body>
</html>