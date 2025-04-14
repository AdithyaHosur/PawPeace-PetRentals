<?php
session_start(); // Start the session at the very beginning
require_once 'db_connect.php'; // Include database connection

$login_error = ''; // Variable to hold login error messages

// --- SECURITY WARNING ---
// Storing and comparing plain text passwords is extremely insecure.
// This code is provided ONLY because you explicitly requested the removal of hashing.
// For any real application, PLEASE use password_hash() and password_verify().
// --- END SECURITY WARNING ---


// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? ''); // Plain text password from form
    $user_type_selected = trim($_POST['user_type'] ?? ''); // 'user' or 'admin' selected by the person logging in

    if (empty($username) || empty($password) || empty($user_type_selected)) {
        $login_error = "Please enter username, password, and select user type.";
    } else {
        // Prepare SQL statement to prevent SQL injection on username
        $sql = "SELECT id, username, password, user_type FROM users WHERE username = ?";
        $stmt = mysqli_prepare($conn, $sql);

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "s", $username);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            if ($result && mysqli_num_rows($result) == 1) {
                $user = mysqli_fetch_assoc($result);

                // !!! INSECURE: Direct password comparison !!!
                if ($password === $user['password']) {
                    // Password is correct (plain text match), now check if the selected user type matches the actual user type
                    if ($user_type_selected === $user['user_type']) {
                        // Login successful! Regenerate session ID for security
                        session_regenerate_id(true);

                        // Store user data in session variables
                        $_SESSION['loggedin'] = true;
                        $_SESSION['user_id'] = $user['id'];
                        $_SESSION['username'] = $user['username'];
                        $_SESSION['user_type'] = $user['user_type']; // Store the ACTUAL type from DB

                        // Redirect based on the ACTUAL user type stored in the database
                        if ($_SESSION['user_type'] == 'admin') {
                            // Admin goes to the admin page first
                            header("Location: admin_manage_pets.php");
                            exit; // Important: stop script execution after redirect
                        } else {
                            // Regular user goes to the homepage
                            header("Location: another-index.php");
                            exit; // Important: stop script execution after redirect
                        }
                    } else {
                        // User selected the wrong type (e.g., tried to log in as admin with a user account)
                        $login_error = "Invalid username, password, or user type selected.";
                    }
                } else {
                    // Invalid password (plain text didn't match)
                    $login_error = "Invalid username, password, or user type selected.";
                }
            } else {
                // Invalid username
                $login_error = "Invalid username, password, or user type selected.";
            }
            mysqli_stmt_close($stmt);
        } else {
            // SQL prepare error
            error_log("Login SQL Prepare Error: " . mysqli_error($conn));
            $login_error = "An error occurred. Please try again later.";
        }
        mysqli_close($conn);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PawPeace - Login</title>
    <link rel="icon" href="./images/file.png" type="image/icon type">
    <!-- Link the SAME CSS file used by other pages -->
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
            min-height: 100vh; /* Full viewport height */
            background: linear-gradient(00deg, rgba(135,206,235,0.8) ,rgba(152,255,152,0.8), rgba(135,206,235,0.8));
            background-size: 180% 180%;
            animation: gradient-animation 8s ease infinite;
            margin: 0; /* Remove default body margin */
             overflow-x: hidden;
        }
        @keyframes gradient-animation {
          0% { background-position: 0% 50%; } 50% { background-position: 100% 50%; } 100% { background-position: 0% 50%; }
        }

        /* Login Container Styling (Keep styles from previous answer) */
        .login-container {
            max-width: 450px;
            width: 90%;
            padding: 40px 35px;
            background-color: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            text-align: center;
        }
        .login-container h2 {
            color: #1a2e3b; margin-bottom: 30px; font-weight: 600;
        }
        .login-container .logo {
             width: 60px; height: 60px; margin-bottom: 20px;
        }
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
        .user-type-options {
            display: flex; justify-content: center; gap: 25px; margin-bottom: 25px;
        }
        .user-type-options label {
            font-weight: 500; color: #333; cursor: pointer; display: flex; align-items: center; gap: 8px;
        }
         .user-type-options input[type="radio"] {
            cursor: pointer; accent-color: #00bfff; width: 16px; height: 16px;
         }
        .login-btn {
            display: block; width: 100%; padding: 14px; background: linear-gradient(to right, #4a90e2, #6c5ce7);
            color: white; border: none; border-radius: 8px; font-size: 1.1rem; font-weight: 600;
            cursor: pointer; transition: all 0.3s ease; margin-top: 10px;
        }
        .login-btn:hover {
            opacity: 0.9; box-shadow: 0 5px 15px rgba(0, 0, 0, 0.15); transform: translateY(-2px);
        }
        .error-message {
            color: #a91e2c; background-color: #fdecea; border: 1px solid #f9c6ca;
            padding: 10px 15px; border-radius: 6px; margin-bottom: 20px;
            font-size: 0.9rem; text-align: center;
        }
         .extra-links { margin-top: 25px; font-size: 0.9em; }
         .extra-links a { color: #007bff; text-decoration: none; }
          .extra-links a:hover { text-decoration: underline; }
         ::selection { background-color: #7dd8ea; color: #fff; text-shadow: 1px 1px 2px black; }

    </style>
</head>
<body>

    <div class="login-container">
        <img src="./images/file.png" alt="PawPeace Logo" class="logo">
        <h2>Login to PawPeace</h2>

        <!-- Display Security Warning -->
        


        <?php if (!empty($login_error)): ?>
            <div class="error-message">
                <?= htmlspecialchars($login_error) ?>
            </div>
        <?php endif; ?>

        <form action="<?= htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required value="<?= isset($_POST['username']) ? htmlspecialchars($_POST['username']) : '' ?>">
            </div>

            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>

            <div class="form-group user-type-options">
                 <label>
                    <input type="radio" name="user_type" value="user" checked> <!-- Default to user -->
                    User Login
                </label>
                <label>
                    <input type="radio" name="user_type" value="admin">
                    Admin Login
                </label>
            </div>

            <button type="submit" class="login-btn">Login</button>

             <div class="extra-links">
                <a href="signup.php">Create Account</a> | <a href="forgot_password.php">Forgot Password?</a>
            </div>
        </form>
    </div>

</body>
</html>