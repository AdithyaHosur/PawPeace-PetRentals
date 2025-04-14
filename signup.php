<?php
session_start(); // Start session
require_once 'db_connect.php'; // Include database connection

$message = ''; // For success/error messages
$message_type = ''; // 'success' or 'error' for styling

// --- SECURITY WARNING ---
// Storing plain text passwords is extremely insecure.
// This code is provided ONLY because you explicitly requested the removal of hashing.
// PLEASE use password_hash() and password_verify() in any real application.
// --- END SECURITY WARNING ---


// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? ''); // Plain text password
    $confirm_password = trim($_POST['confirm_password'] ?? '');
    $user_type = 'user'; // Default new signups to 'user'

    // --- Validation ---
    if (empty($username) || empty($password) || empty($confirm_password)) {
        $message = "Please fill in all fields.";
        $message_type = "error";
    } elseif ($password !== $confirm_password) {
        $message = "Passwords do not match.";
        $message_type = "error";
    } elseif (strlen($password) < 6) { // Basic password length check
         $message = "Password must be at least 6 characters long.";
         $message_type = "error";
    } else {
        // --- Check if username already exists ---
        $sql_check = "SELECT id FROM users WHERE username = ?";
        $stmt_check = mysqli_prepare($conn, $sql_check);
        if ($stmt_check) {
            mysqli_stmt_bind_param($stmt_check, "s", $username);
            mysqli_stmt_execute($stmt_check);
            mysqli_stmt_store_result($stmt_check); // Store result to check num_rows

            if (mysqli_stmt_num_rows($stmt_check) > 0) {
                // Username already exists
                $message = "Username already taken. Please choose another.";
                $message_type = "error";
            } else {
                // Username is available, proceed with insertion
                mysqli_stmt_close($stmt_check); // Close check statement before preparing insert

                // --- Prepare SQL Statement for Insertion ---
                $sql_insert = "INSERT INTO users (username, password, user_type) VALUES (?, ?, ?)";
                $stmt_insert = mysqli_prepare($conn, $sql_insert);

                if ($stmt_insert) {
                    // Bind parameters (using the plain text password)
                    mysqli_stmt_bind_param($stmt_insert, "sss",
                        $username,
                        $password, // Storing plain text password - INSECURE
                        $user_type
                    );

                    // Execute the statement
                    if (mysqli_stmt_execute($stmt_insert)) {
                        $message = "Signup successful! You can now log in.";
                        $message_type = "success";
                        // Optionally clear variables here if needed, but form is usually not redisplayed on success
                    } else {
                        error_log("SQL Insert Execute Error: " . mysqli_stmt_error($stmt_insert));
                        $message = "An error occurred during signup. Please try again.";
                        $message_type = "error";
                    }
                    mysqli_stmt_close($stmt_insert);
                } else {
                     error_log("SQL Insert Prepare Error: " . mysqli_error($conn));
                     $message = "Database error preparing signup. Please contact support.";
                     $message_type = "error";
                }
            }
             // Ensure check statement is closed if it wasn't closed above (e.g., if username existed)
             if ($stmt_check && mysqli_stmt_num_rows($stmt_check) > 0) {
                 mysqli_stmt_close($stmt_check);
             }

        } else {
             error_log("SQL Check Prepare Error: " . mysqli_error($conn));
             $message = "Database error checking username. Please contact support.";
             $message_type = "error";
        }

        mysqli_close($conn); // Close connection after operations
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PawPeace - Sign Up</title>
    <link rel="icon" href="./images/file.png" type="image/icon type">
    <!-- Link the SAME CSS file -->
    <link rel="stylesheet" href="./another-snake.css"> <!-- Adjust path if needed -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link
    href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@200;300;400;500;600;700;800&family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap"
    rel="stylesheet" />
    <style>
        /* Use the same gradient background */
        body {
            font-family: 'Poppins', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: linear-gradient(00deg, rgba(135,206,235,0.8) ,rgba(152,255,152,0.8), rgba(135,206,235,0.8));
            background-size: 180% 180%;
            animation: gradient-animation 8s ease infinite;
            margin: 0;
             overflow-x: hidden;
             padding: 20px 0; /* Add some padding */
        }
        @keyframes gradient-animation {
          0% { background-position: 0% 50%; } 50% { background-position: 100% 50%; } 100% { background-position: 0% 50%; }
        }

        /* Signup Container Styling - similar to login */
        .signup-container {
            max-width: 480px; /* Slightly wider for more fields */
            width: 90%;
            padding: 40px 35px;
            background-color: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            text-align: center;
        }

        .signup-container h2 {
            color: #1a2e3b;
            margin-bottom: 30px;
            font-weight: 600;
        }
        .signup-container .logo {
             width: 60px; height: 60px; margin-bottom: 20px;
        }

        /* Form Styling - reuse login styles */
        .form-group {
            margin-bottom: 20px; text-align: left;
        }
        .form-group label {
            display: block; margin-bottom: 8px; font-weight: 500; color: #444;
        }
        .form-group input[type="text"],
        .form-group input[type="password"] {
            width: 100%; padding: 12px 15px; border: 1px solid #ccc; border-radius: 6px;
            font-family: 'Poppins', sans-serif; font-size: 1rem; box-sizing: border-box;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }
        .form-group input[type="text"]:focus,
        .form-group input[type="password"]:focus {
            border-color: #00bfff; outline: none; box-shadow: 0 0 0 2px rgba(0, 191, 255, 0.2);
        }

        /* Submit Button Styling */
        .signup-btn {
            display: block; width: 100%; padding: 14px; background: linear-gradient(to right, #6c5ce7, #4a90e2); /* Reversed gradient */
            color: white; border: none; border-radius: 8px; font-size: 1.1rem; font-weight: 600;
            cursor: pointer; transition: all 0.3s ease; margin-top: 10px;
        }
        .signup-btn:hover {
            opacity: 0.9; box-shadow: 0 5px 15px rgba(0, 0, 0, 0.15); transform: translateY(-2px);
        }

        /* Message Styling */
        .message {
            padding: 12px 15px; margin-bottom: 20px; border-radius: 6px;
            font-size: 0.95rem; text-align: center; border: 1px solid transparent;
        }
        .message.success {
            background-color: #e0f8e9; color: #1d7a3d; border-color: #b8eecd;
        }
        .message.error {
            color: #a91e2c; background-color: #fdecea; border-color: #f9c6ca;
        }

         /* Link to Login Page */
         .login-link {
             margin-top: 25px;
             font-size: 0.95em;
             color: #555;
         }
         .login-link a {
             color: #007bff;
             text-decoration: none;
             font-weight: 500;
         }
          .login-link a:hover {
              text-decoration: underline;
          }

         ::selection { background-color: #7dd8ea; color: #fff; text-shadow: 1px 1px 2px black; }

    </style>
</head>
<body>

    <div class="signup-container">
        <img src="./images/file.png" alt="PawPeace Logo" class="logo">
        <h2>Create Your PawPeace Account</h2>



        <?php if (!empty($message)): ?>
            <div class="message <?= htmlspecialchars($message_type) ?>">
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>

        <?php // Only show form if signup was not successful ?>
        <?php if ($message_type !== 'success'): ?>
            <form action="<?= htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="form-group">
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" required value="<?= isset($_POST['username']) ? htmlspecialchars($_POST['username']) : '' ?>">
                </div>

                <div class="form-group">
                    <label for="password">Password (min 6 characters):</label>
                    <input type="password" id="password" name="password" required>
                </div>

                <div class="form-group">
                    <label for="confirm_password">Confirm Password:</label>
                    <input type="password" id="confirm_password" name="confirm_password" required>
                </div>

                <button type="submit" class="signup-btn">Sign Up</button>
            </form>
        <?php endif; ?>

         <div class="login-link">
            <?php if ($message_type === 'success'): ?>
                 Click here to <a href="login.php">Login</a>
            <?php else: ?>
                 Already have an account? <a href="login.php">Login here</a>
            <?php endif; ?>
         </div>
    </div>

</body>
</html>