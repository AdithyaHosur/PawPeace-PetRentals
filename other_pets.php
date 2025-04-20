<?php
// --- Database Connection ---
require_once 'db_connect.php'; // Make sure the path is correct

// --- Prepare SQL Query for the NEW table ---
// Fetch all available pets directly from the 'other_pets' table
$status_to_fetch = 'available';
$sql = "SELECT id, name, description, image_class
        FROM other_pets
        WHERE availability_status = ? ORDER BY id ASC";

$stmt = mysqli_prepare($conn, $sql);

if ($stmt) {
    // --- Bind Parameters ---
    mysqli_stmt_bind_param($stmt, "s", $status_to_fetch);

    // --- Execute Statement ---
    mysqli_stmt_execute($stmt);

    // --- Get Result Set ---
    $result = mysqli_stmt_get_result($stmt);

} else {
    // Handle error if statement preparation fails
    error_log("SQL Prepare Error (other_pets): " . mysqli_error($conn));
    $result = false;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fishes, Birds and Hamsters</title>
    <link rel="stylesheet" href="./another-snake.css">
    <link rel="icon" href="./images/file.png" type="image/icon type">
</head>
<body class="gradient-background">
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
    <main class="pet-gallery">
        <h2 class="category-title">Other Companions</h2>

        <div class="pet-row"> <!-- Start the first row -->
        <?php
        // Check if the query was successful and returned rows from 'other_pets' table
        if ($result && mysqli_num_rows($result) > 0) {
            $pet_count = 0; // Initialize counter for row breaks

            // Loop through each row (pet) from the 'other_pets' table
            while ($pet = mysqli_fetch_assoc($result)) {
                // Every 3 pets, close the current row and start a new one
                if ($pet_count > 0 && $pet_count % 3 == 0) {
                    echo '</div>'; // Close previous row
                    echo '<div class="pet-row">'; // Start new row
                }
        ?>
                <!-- Start: HTML structure for ONE pet (Matches reptiles.php structure) -->
                <div class="container" id="pet-<?= htmlspecialchars($pet['id']) ?>">
                    <div class="wrapper">
                        <!-- Use the image_class from the database -->
                        <div class="<?= htmlspecialchars($pet['image_class']) ?>"></div>
                        <!-- Use h2 for the name -->
                        <h2><?= htmlspecialchars($pet['name']) ?></h2>
                        <!-- Use the description from the database -->
                        <p><?= htmlspecialchars($pet['description']) ?></p>
                    </div>
                    <div class="button-wrapper">
                         <!-- Update links if needed - points to generic detail/form pages -->
                        <a href="./pet_details.php?id=<?= htmlspecialchars($pet['id']) ?>&type=other"><button class="btn outline">DETAILS</button></a>
                        <a href="rental_form.php?pet_id=<?= htmlspecialchars($pet['id']) ?>&pet_table=other_pets">
                            <button class="btn fill">RENT NOW</button>
                        </a>
                         <!-- Added &type=other in links: optional, helps target pages know which table to query -->
                    </div>
                </div>
                <!-- End: HTML structure for ONE pet -->
        <?php
                $pet_count++; // Increment pet counter
            } // End while loop

        } else {
            // Display a message if no pets were found in the 'other_pets' table
            echo '<p class="no-pets-message">No other companions currently available for rent.</p>';
            // Optional: Show database error if $result was false
             if ($result === false && $conn) { // Check if $conn exists before using mysqli_error
                 echo '<p style="color:red; text-align:center;">Database query failed: ' . htmlspecialchars(mysqli_error($conn)) . '</p>';
             } elseif ($result === false) {
                 echo '<p style="color:red; text-align:center;">Database connection or query preparation failed.</p>';
             }
        }

        // Close the statement if it was prepared successfully
        if (isset($stmt) && $stmt instanceof mysqli_stmt) { // Check if $stmt is a valid statement object
            mysqli_stmt_close($stmt);
        }
        // Close the database connection
        if ($conn) { // Check if $conn is a valid connection resource
            mysqli_close($conn);
        }
        ?>
        </div> <!-- Close the final pet-row -->

    </main>

    <script>
        // Search functionality (Basic)
        const petSearchInput = document.getElementById('petSearch');
        if (petSearchInput) {
            petSearchInput.addEventListener('keyup', function(event) {
                if (event.key === 'Enter') {
                    const searchTerm = this.value.toLowerCase().trim();
                    const petContainers = document.querySelectorAll('.pet-gallery .container');
                    let found = false;

                    petContainers.forEach(container => {
                        const petNameElement = container.querySelector('h2');
                        if (petNameElement) {
                             const petName = petNameElement.textContent.toLowerCase();
                             if (petName.includes(searchTerm)) {
                                if (!found) {
                                     container.scrollIntoView({ behavior: 'smooth', block: 'center' });
                                     // Optional highlight
                                     container.style.transition = 'transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out';
                                     container.style.transform = 'scale(1.03)';
                                     container.style.boxShadow = '0 0 20px rgba(0, 212, 255, 0.7)';
                                     setTimeout(() => {
                                        container.style.transform = 'scale(1)';
                                        container.style.boxShadow = 'none';
                                     }, 1500);
                                }
                                found = true;
                            }
                        }
                    });

                    if (!found && searchTerm !== '') {
                        alert('Pet not found matching "' + searchTerm + '" on this page.');
                    }
                }
            });
        }

        // Dropdown functionality
        const accountBtn = document.querySelector('.account-btn');
        const dropdownContent = document.querySelector('.dropdown-content');

        if(accountBtn && dropdownContent) {
             accountBtn.addEventListener('click', function(event) {
                 event.stopPropagation(); // Prevent click from immediately closing dropdown
                 dropdownContent.classList.toggle('show');
             });

             window.addEventListener('click', function(event) {
                 // Check if the click is outside the button AND outside the dropdown content
                 if (!accountBtn.contains(event.target) && !dropdownContent.contains(event.target)) {
                     if (dropdownContent.classList.contains('show')) {
                         dropdownContent.classList.remove('show');
                     }
                 }
            });
        }
    </script>    
</body>
</html>
