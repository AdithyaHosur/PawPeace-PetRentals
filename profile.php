<?php
session_start(); // Start the session
require_once 'db_connect.php'; // Include database connection

// --- Authentication Check: Ensure user is logged in ---
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php"); // Redirect to login page if not logged in
    exit;
}

// --- Get User Information ---
$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];
$user_type = $_SESSION['user_type'];

// --- Fetch User Details (e.g., join date) - Optional ---
$user_details = null;
$sql_user = "SELECT created_at FROM users WHERE id = ?";
$stmt_user = mysqli_prepare($conn, $sql_user);
if ($stmt_user) {
    mysqli_stmt_bind_param($stmt_user, "i", $user_id);
    mysqli_stmt_execute($stmt_user);
    $result_user = mysqli_stmt_get_result($stmt_user);
    if ($result_user && mysqli_num_rows($result_user) === 1) {
        $user_details = mysqli_fetch_assoc($result_user);
    }
    mysqli_stmt_close($stmt_user);
}

// --- Fetch Rental History (Only for 'user' type) ---
$rentals = [];
$rental_count = 0;
if ($user_type === 'user') {
    // --- Fetch basic rental info ---
    $sql_rentals = "SELECT rental_id, pet_id, pet_table, rental_start_date, rental_end_date, rental_status
                    FROM rentals
                    WHERE user_id = ?
                    ORDER BY rental_start_date DESC";
    $stmt_rentals = mysqli_prepare($conn, $sql_rentals);

    if ($stmt_rentals) {
        mysqli_stmt_bind_param($stmt_rentals, "i", $user_id);
        mysqli_stmt_execute($stmt_rentals);
        $result_rentals = mysqli_stmt_get_result($stmt_rentals);
        $rental_count = mysqli_num_rows($result_rentals);

        if ($rental_count > 0) {
            // --- Fetch pet names based on rental info ---
            // Note: This approach (looping queries) can be inefficient (N+1 problem).
            // For larger scale, consider UNIONs or fetching all needed pet IDs first.
            while ($rental = mysqli_fetch_assoc($result_rentals)) {
                $pet_name = "Unknown Pet"; // Default
                $pet_table = $rental['pet_table']; // Which table to query
                $pet_id = $rental['pet_id'];

                // Basic validation for table name to prevent SQL injection possibility
                if (in_array($pet_table, ['pets', 'other_pets', 'dogs'])) {
                    $sql_pet = "SELECT name FROM `" . $pet_table . "` WHERE id = ?";
                    $stmt_pet = mysqli_prepare($conn, $sql_pet);
                    if ($stmt_pet) {
                        mysqli_stmt_bind_param($stmt_pet, "i", $pet_id);
                        mysqli_stmt_execute($stmt_pet);
                        $result_pet = mysqli_stmt_get_result($stmt_pet);
                        if ($pet_data = mysqli_fetch_assoc($result_pet)) {
                            $pet_name = $pet_data['name'];
                        }
                        mysqli_stmt_close($stmt_pet);
                    } else {
                        error_log("Error preparing pet name query for table $pet_table: " . mysqli_error($conn));
                    }
                } else {
                     error_log("Invalid pet_table value encountered: " . $pet_table);
                }

                // Add pet name to the rental data
                $rental['pet_name'] = $pet_name;
                $rentals[] = $rental; // Add to the final rentals array
            }
        }
        mysqli_stmt_close($stmt_rentals);
    } else {
        error_log("Error preparing rentals query: " . mysqli_error($conn));
    }
}

mysqli_close($conn); // Close the connection
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PawPeace - User Profile</title>
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
            /* display: flex; */ /* Remove flex if using standard page layout */
            /* justify-content: center; */
            /* align-items: center; */
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

        /* Profile Container Styling */
        .profile-container {
            max-width: 900px; /* Wider for profile info */
            margin: 30px auto; /* Center with margin */
            padding: 30px 40px;
            background-color: rgba(255, 255, 255, 0.85); /* Slightly less transparent */
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            border-radius: 15px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            color: #333;
        }

        .profile-header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid #eee;
        }
        .profile-header img { /* Style for avatar */
            width: 100px;
            height: 100px;
            border-radius: 50%;
            margin-bottom: 15px;
            border: 3px solid #fff;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        .profile-header h2 {
            color: #1a2e3b;
            margin-bottom: 5px;
            font-weight: 600;
        }
        .profile-header .user-type-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 15px;
            font-size: 0.85em;
            font-weight: 500;
            background-color: #e0e0e0;
            color: #555;
            text-transform: capitalize;
        }
        .profile-header .user-type-badge.admin {
             background-color: #ffc107; /* Yellow for admin */
             color: #333;
        }
         .profile-header .user-type-badge.user {
             background-color: #4a90e2; /* Blue for user */
             color: white;
        }

        /* Profile Details Section */
        .profile-details, .admin-section, .rental-history {
            margin-bottom: 30px;
        }
        .profile-details h3, .admin-section h3, .rental-history h3 {
             font-size: 1.4rem;
             font-weight: 600;
             color: #333;
             margin-bottom: 15px;
             padding-bottom: 10px;
             border-bottom: 1px solid #eee;
        }
        .profile-details p, .admin-section p {
            font-size: 1rem;
            color: #555;
            line-height: 1.6;
            margin-bottom: 10px;
        }
        .profile-details strong, .admin-section strong {
            color: #333;
            margin-right: 8px;
        }

        /* Rental History Table */
        .rental-table-container {
            overflow-x: auto; /* Allow horizontal scrolling on small screens */
        }
        .rental-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        .rental-table th, .rental-table td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
            font-size: 0.95rem;
            vertical-align: middle;
        }
        .rental-table th {
            background-color: #f8f9fa; /* Light background for header */
            font-weight: 600;
            color: #333;
        }
        .rental-table tbody tr:hover {
            background-color: #f1f1f1; /* Subtle hover effect */
        }
        .rental-table .status { font-weight: 500; text-transform: capitalize; }
        .rental-table .status.active { color: #28a745; } /* Green */
        .rental-table .status.completed { color: #6c757d; } /* Grey */
        .rental-table .status.cancelled { color: #dc3545; } /* Red */

         .no-rentals {
             text-align: center;
             color: #777;
             font-style: italic;
             margin-top: 20px;
             padding: 20px;
             background-color: #f9f9f9;
             border-radius: 8px;
         }

         /* Admin Links */
          .admin-links a {
              display: inline-block;
              margin: 5px 10px 5px 0;
              padding: 8px 15px;
              background-color: #007bff;
              color: white;
              text-decoration: none;
              border-radius: 5px;
              font-size: 0.9rem;
              transition: background-color 0.3s ease;
          }
          .admin-links a:hover {
              background-color: #0056b3;
          }


         ::selection { background-color: #7dd8ea; color: #fff; text-shadow: 1px 1px 2px black; }

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
                    <a href="./admin_manage_pets.php" class="nav-link px-3 link-warning">Manage Pets</a>
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


    <!-- Main Profile Content -->
    <div class="profile-container">
        <div class="profile-header">
             <img src="https://api.dicebear.com/6.x/initials/svg?seed=<?= htmlspecialchars($username) ?>&backgroundColor=4a90e2" alt="User Avatar"> <!-- Or use a default/uploaded image -->
             <h2><?= htmlspecialchars($username) ?></h2>
             <span class="user-type-badge <?= $user_type ?>">
                 <?= htmlspecialchars($user_type) ?>
             </span>
        </div>

        <!-- === ADMIN VIEW === -->
        <?php if ($user_type === 'admin'): ?>
            <div class="admin-section">
                <h3>Admin Information</h3>
                <p>Welcome, Administrator!</p>
                <p>Use this area to manage the platform.</p>
                 <?php if ($user_details && isset($user_details['created_at'])): ?>
                      <p><strong>Account Created:</strong> <?= htmlspecialchars(date('F j, Y', strtotime($user_details['created_at']))) ?></p>
                 <?php endif; ?>
                <div class="admin-links">
                     <a href="admin_manage_pets.php">Manage Pets</a>
                     <!-- Add links to other admin functions here -->
                     <!-- <a href="admin_manage_users.php">Manage Users</a> -->
                     <!-- <a href="admin_view_rentals.php">View All Rentals</a> -->
                </div>
            </div>

        <!-- === USER VIEW === -->
        <?php else: ?>
            <div class="profile-details">
                <h3>Profile Details</h3>
                <p><strong>Username:</strong> <?= htmlspecialchars($username) ?></p>
                <?php if ($user_details && isset($user_details['created_at'])): ?>
                     <p><strong>Member Since:</strong> <?= htmlspecialchars(date('F j, Y', strtotime($user_details['created_at']))) ?></p>
                <?php endif; ?>
                <p><strong>Total Rentals:</strong> <?= $rental_count ?></p>
                <!-- Add more profile fields here if needed (e.g., email, edit profile link) -->
            </div>

            <div class="rental-history">
                <h3>Rental History</h3>
                <?php if ($rental_count > 0): ?>
                    <div class="rental-table-container">
                        <table class="rental-table">
                            <thead>
                                <tr>
                                    <th>Pet Name</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($rentals as $rental): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($rental['pet_name']) ?></td>
                                        <td><?= htmlspecialchars(date('M j, Y - g:i A', strtotime($rental['rental_start_date']))) ?></td>
                                        <td><?= $rental['rental_end_date'] ? htmlspecialchars(date('M j, Y - g:i A', strtotime($rental['rental_end_date']))) : '<i>Ongoing</i>' ?></td>
                                        <td><span class="status <?= htmlspecialchars($rental['rental_status']) ?>"><?= htmlspecialchars($rental['rental_status']) ?></span></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p class="no-rentals">You haven't rented any pets yet!</p>
                    <!-- Optional: Add a link to browse pets -->
                     <p style="text-align:center; margin-top: 15px;"><a href="another-random.php" style="text-decoration:none; color: #007bff; font-weight:500;">Browse Pets to Rent →</a></p>
                <?php endif; ?>
            </div>
        <?php endif; ?>

    </div><!-- /.profile-container -->

    <!-- Bootstrap JS (Needed for Navbar Dropdown) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe"
        crossorigin="anonymous"></script>

</body>
</html>