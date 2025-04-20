<?php
session_start();
require_once 'db_connect.php';

// --- 1. Authentication Check ---
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['user_type'] !== 'user') {
    $_SESSION['error_message'] = "You must be logged in as a user to rent a pet.";
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$message = ''; // For success/error messages after POST
$message_type = ''; // 'success' or 'error'

// --- Define Allowed ACTUAL Table Names ---
$allowed_tables = ['showcase_pets_snakes', 'other_pets', 'dogs']; // <-- UPDATED

// --- 2. Handle Form Submission (POST Request) ---
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $selected_pet_value = trim($_POST['selected_pet'] ?? '');

    if (empty($selected_pet_value)) {
        $message = "Please select a pet to rent.";
        $message_type = "error";
    } else {
        // Parse the combined value "table-id"
        list($pet_table, $pet_id) = explode('-', $selected_pet_value, 2);
        $pet_id = filter_var($pet_id, FILTER_VALIDATE_INT);

        // Validate parsed values using the ACTUAL allowed table names
        if (!$pet_id || !$pet_table || !in_array($pet_table, $allowed_tables)) { // <-- Check against updated array
            $message = "Invalid pet selection.";
            $message_type = "error";
        } else {
            // --- Database Transaction ---
            mysqli_begin_transaction($conn);
            try {
                // Lock the row and check availability (Uses $pet_table dynamically)
                $sql_check = "SELECT availability_status FROM `" . $pet_table . "` WHERE id = ? FOR UPDATE";
                $stmt_check = mysqli_prepare($conn, $sql_check);
                mysqli_stmt_bind_param($stmt_check, "i", $pet_id);
                mysqli_stmt_execute($stmt_check);
                $result_check = mysqli_stmt_get_result($stmt_check);
                $pet_status_data = mysqli_fetch_assoc($result_check);
                mysqli_stmt_close($stmt_check);

                if (!$pet_status_data || $pet_status_data['availability_status'] !== 'available') {
                    throw new Exception("Sorry, the selected pet is no longer available.");
                }

                // Update Pet Status to 'rented' (Uses $pet_table dynamically)
                $sql_update = "UPDATE `" . $pet_table . "` SET availability_status = 'rented' WHERE id = ?";
                $stmt_update = mysqli_prepare($conn, $sql_update);
                mysqli_stmt_bind_param($stmt_update, "i", $pet_id);
                if (!mysqli_stmt_execute($stmt_update)) { throw new Exception("Failed to update pet status."); }
                mysqli_stmt_close($stmt_update);

                // Insert into Rentals Table (Stores the correct $pet_table name)
                $sql_insert = "INSERT INTO rentals (user_id, pet_id, pet_table, rental_start_date, rental_status) VALUES (?, ?, ?, NOW(), 'active')";
                $stmt_insert = mysqli_prepare($conn, $sql_insert);
                // Make sure $pet_table being inserted matches one of the allowed tables
                if(in_array($pet_table, $allowed_tables)){
                    mysqli_stmt_bind_param($stmt_insert, "iis", $user_id, $pet_id, $pet_table);
                    if (!mysqli_stmt_execute($stmt_insert)) { throw new Exception("Failed to record rental information."); }
                } else {
                     throw new Exception("Invalid table name detected during rental recording."); // Safety check
                }
                mysqli_stmt_close($stmt_insert);

                // Commit Transaction
                mysqli_commit($conn);

                // Set Success Message for display on reload
                $_SESSION['success_message_rental'] = "Rental successful! The pet is now marked as rented.";

            } catch (Exception $e) {
                mysqli_rollback($conn); // Rollback on error
                error_log("Rental Form Error: " . $e->getMessage() . " for user $user_id, pet $pet_id ($pet_table)");
                // Set Error Message for display on reload
                $_SESSION['error_message_rental'] = "Rental failed: " . $e->getMessage();
            }

             // Redirect back to the form page itself to display message
             header("Location: rental_form.php");
             exit;

        } // End validation else
    } // End empty check else
} // End POST handling

// --- 3. Handle Pre-selection (GET Request) & Fetch Available Pets ---

$preselect_pet_id = filter_input(INPUT_GET, 'pet_id', FILTER_VALIDATE_INT);
// Sanitize pet_table from GET, ensuring it's one of the allowed names
$preselect_pet_table_input = filter_input(INPUT_GET, 'pet_table', FILTER_SANITIZE_STRING);
$preselect_pet_table = null; // Default to null
if(in_array($preselect_pet_table_input, $allowed_tables)){
    $preselect_pet_table = $preselect_pet_table_input; // Use only if it's an allowed table name
}

$preselect_value = null;
if ($preselect_pet_id && $preselect_pet_table) {
    $preselect_value = $preselect_pet_table . '-' . $preselect_pet_id;
}

// Fetch ALL available pets from all ACTUAL tables using UNION ALL
$available_pets = [];
$sql_fetch_pets = "
    (SELECT id, name, 'showcase_pets_snakes' as table_name FROM showcase_pets_snakes WHERE availability_status = 'available') -- <-- UPDATED table name
    UNION ALL
    (SELECT id, name, 'other_pets' as table_name FROM other_pets WHERE availability_status = 'available')
    UNION ALL
    (SELECT id, name, 'dogs' as table_name FROM dogs WHERE availability_status = 'available')
    ORDER BY name ASC
"; // <-- UPDATED query with correct table names

$result_fetch = mysqli_query($conn, $sql_fetch_pets);
if ($result_fetch) {
    while ($row = mysqli_fetch_assoc($result_fetch)) {
        $available_pets[] = $row;
    }
} else {
     error_log("Error fetching available pets: " . mysqli_error($conn));
     $message = "Error fetching available pets list."; // Inform user
     $message_type = "error";
}

// --- 4. Get and Clear Session Messages ---
if (isset($_SESSION['success_message_rental'])) {
    $message = $_SESSION['success_message_rental'];
    $message_type = 'success';
    unset($_SESSION['success_message_rental']);
} elseif (isset($_SESSION['error_message_rental'])) {
    $message = $_SESSION['error_message_rental'];
    $message_type = 'error';
    unset($_SESSION['error_message_rental']);
}


mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PawPeace - Rent a Pet</title>
    <link rel="icon" href="./images/file.png" type="image/icon type">
    <!-- Link the SAME CSS file -->
    <link rel="stylesheet" href="./another-snake.css"> <!-- Adjust path if needed -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link
    href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@200;300;400;500;600;700;800&family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap"
    rel="stylesheet" />
     <style>
        /* Paste the CSS styles from the previous rental_form.php example here */
        /* Including body, @keyframes, .form-container, .form-group, etc. */
        /* Use the same gradient background */
        body {
            font-family: 'Poppins', sans-serif;
            min-height: 100vh;
            background: linear-gradient(00deg, rgba(135,206,235,0.8) ,rgba(152,255,152,0.8), rgba(135,206,235,0.8));
            background-size: 180% 180%;
            animation: gradient-animation 8s ease infinite;
            margin: 0;
            padding-top: 80px; /* Space for fixed navbar */
             overflow-x: hidden;
        }
        @keyframes gradient-animation {
          0% { background-position: 0% 50%; } 50% { background-position: 100% 50%; } 100% { background-position: 0% 50%; }
        }
        .form-container {
            max-width: 650px; margin: 30px auto; padding: 35px 45px;
            background-color: rgba(255, 255, 255, 0.85); backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px); border-radius: 15px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1); color: #333;
        }
        .form-container h2 { text-align: center; color: #1a2e3b; margin-bottom: 30px; font-weight: 600; }
        .form-group { margin-bottom: 25px; }
        .form-group label { display: block; margin-bottom: 8px; font-weight: 600; color: #444; }
        .form-group select {
            width: 100%; padding: 12px 15px; border: 1px solid #ccc; border-radius: 6px;
            font-family: 'Poppins', sans-serif; font-size: 1rem; box-sizing: border-box;
            transition: border-color 0.3s ease, box-shadow 0.3s ease; background-color: white;
        }
        .form-group select:focus {
            border-color: #00bfff; outline: none; box-shadow: 0 0 0 2px rgba(0, 191, 255, 0.2);
        }
        .submit-btn {
            display: block; width: 100%; padding: 14px; background: linear-gradient(to right, #4a90e2, #6c5ce7);
            color: white; border: none; border-radius: 8px; font-size: 1.1rem; font-weight: 600;
            cursor: pointer; transition: all 0.3s ease; margin-top: 15px;
        }
        .submit-btn:hover { opacity: 0.9; box-shadow: 0 5px 15px rgba(0, 0, 0, 0.15); transform: translateY(-2px); }
        .message {
            padding: 12px 15px; margin-bottom: 20px; border-radius: 6px;
            font-size: 0.95rem; text-align: center; border: 1px solid transparent;
        }
        .message.success { background-color: #e0f8e9; color: #1d7a3d; border-color: #b8eecd; }
        .message.error { color: #a91e2c; background-color: #fdecea; border-color: #f9c6ca; }
        .no-pets-available {
             text-align: center; color: #777; font-style: italic; margin-top: 20px;
             padding: 20px; background-color: #f9f9f9; border-radius: 8px;
         }
        ::selection { background-color: #7dd8ea; color: #fff; text-shadow: 1px 1px 2px black; }

        /* Navbar Styles */
        .navbar {
          display: flex;
          justify-content: space-between;
          align-items: center;
          padding: 15px 30px;
          z-index: 1000;
          box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
          position: fixed; /* Changed from sticky to fixed */
          top: 0;
          left: 0;
          right: 0;
          z-index: 1000;
          background-color: rgba(255, 255, 255, 0.6); /* Added transparency */
          backdrop-filter: blur(10px); /* Adds blur effect behind the navbar */
          -webkit-backdrop-filter: blur(10px); /* For Safari support */
        }

        .logo-container {
          display: flex;
          align-items: center;
          margin-left: 100px;
        }

        .paw-logo {
          width: 40px;
          height: 40px;
          margin-right: 10px;
        }

        .logo-text {
          font-family: 'Poppins', sans-serif;
          font-weight: 600;
          font-size: 24px;
          color: #333;
          text-decoration: none;
        }

        .nav-links {
          display: flex;
          gap: 25px;
          margin-left: -300px;
        }

        .nav-links a {
          text-decoration: none;
          color: #333;
          font-weight: 500;
          transition: color 0.3s;
        }

        .nav-links a:hover, .nav-links a.active {
          color: #00bfff;
        }

        .nav-link {
            font-weight: 500;
            transition: all 0.3s ease;
            position: relative;
          }

          .nav-link:hover {
            color: #00bfff !important;
          }

        .nav-link:after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            background: #00bfff;
            bottom: -5px;
            left: 50%;
            transform: translateX(-50%);
            transition: width 0.3s;
          }

          .nav-link:hover:after {
            width: 100%;
          }

        .search-account {
          display: flex;
          align-items: center;
          gap: 20px;
        }

        .search-container {
          position: relative;
        }

        .search-input {
          padding: 8px 15px;
          border: 1px solid #ddd;
          border-radius: 20px;
          width: 200px;
          font-family: 'Poppins', sans-serif;
        }

        .search-btn {
          position: absolute;
          right: 10px;
          top: 50%;
          transform: translateY(-50%);
          background: none;
          border: none;
          cursor: pointer;
          color: #777;
        }

        .account-dropdown {
          position: relative;
        }

        .account-btn {
          display: flex;
          align-items: center;
          gap: 8px;
          background: none;
          border: none;
          cursor: pointer;
          font-family: 'Poppins', sans-serif;
        }

        .profile-img {
          width: 30px;
          height: 30px;
          border-radius: 50%;
        }

        .dropdown-content {
          display: none;
          position: absolute;
          right: 0;
          background-color: white;
          min-width: 160px;
          box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
          border-radius: 5px;
          z-index: 1;
        }

        .dropdown-content a {
          display: block;
          padding: 10px 15px;
          text-decoration: none;
          color: #333;
          transition: background-color 0.3s;
        }

        .dropdown-content a:hover {
          background-color: #f5f5f5;
        }

        .dropdown-content.show {
          display: block;
        }
    </style>
</head>
<body>
        <!-- Navbar -->
    <nav class="navbar">
        <div class="logo-container">
            <img src="./images/file.png" alt="Paw Logo" class="paw-logo">
            <a href="another-index.php" class="logo-text">PawPeace</a>
        </div>
        <div class="nav-links">
            <a href="another-index.php" class="nav-link" >Home</a>
            <a href="another-random.php" class="active nav-link">Rent-a-pet</a>
            <a href="past-rentals.php"  class="nav-link" >Past rentals</a>
            <a href="book-visit.php" class="nav-link" >Book a visit</a>
            <?php if (isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'admin'): ?>
                    <li><a href="./admin_manage_pets.php" class="nav-link px-3 link-warning">Manage Pets</a></li>
                 <?php endif; ?>
        </div>
        <div class="search-account">
            <div class="search-container">
                <input type="text" id="petSearch" placeholder="Search pets..." class="search-input">
                <button class="search-btn"><i class="fas fa-search"></i></button>
            </div>
            <div class="account-dropdown">
                <button class="account-btn">
                    <img src="./images/profile.png" alt="Profile" class="profile-img">
                    <span>My Account</span>
                    <i class="fas fa-caret-down"></i>
                </button>
                <div class="dropdown-content">
                    <a href="profile.php">Profile</a>
                    <a href="settings.html">Settings</a>
                    <a href="favorites.html">Favorites</a>
                    <a href="logout.html">Logout</a>
                </div>
            </div>
        </div>
    </nav>
   

    <!-- Main Rental Form Content -->
    <div class="form-container">
        <h2>Rent Your Companion</h2>

         <!-- Display Messages -->
         <?php if (!empty($message)): ?>
            <div class="message <?= htmlspecialchars($message_type) ?>">
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($available_pets)): ?>
            <form action="<?= htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="form-group">
                    <label for="selected_pet">Select Available Pet:</label>
                    <select id="selected_pet" name="selected_pet" required>
                        <option value="" disabled <?= !$preselect_value ? 'selected' : '' ?>>-- Choose a Pet --</option>
                        <?php foreach ($available_pets as $pet): ?>
                            <?php
                                // Construct the value as table_name-id
                                $option_value = htmlspecialchars($pet['table_name'] . '-' . $pet['id']);
                                // Check if this option should be pre-selected
                                $selected_attr = ($preselect_value === $option_value) ? 'selected' : '';
                            ?>
                            <option value="<?= $option_value ?>" <?= $selected_attr ?>>
                                <?= htmlspecialchars($pet['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <p style="font-size: 0.9em; color: #555;">
                        By clicking "Confirm Rental", you agree to our rental terms and responsible pet care guidelines.
                        The rental starts immediately.
                    </p>
                </div>

                <button type="submit" class="submit-btn">Confirm Rental</button>
            </form>
        <?php else: ?>
             <p class="no-pets-available">We're sorry, but there are currently no pets available for rent.</p>
             <!-- Optional: Link back to main pet page -->
              <p style="text-align:center; margin-top: 15px;"><a href="another-random.php" style="text-decoration:none; color: #007bff; font-weight:500;">← Back to Pet Guide</a></p>
        <?php endif; ?>

    </div><!-- /.form-container -->

    <!-- Bootstrap JS (Needed for Navbar Dropdown) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe"
        crossorigin="anonymous"></script>

</body>
</html>