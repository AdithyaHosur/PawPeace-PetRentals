<?php
// Include the database connection file
require_once 'db_connect.php'; // Make sure the path is correct

// Define the category we want to display on this page
$category_to_display = 'Reptile';

// --- Prepare and Execute SQL Query ---
// We select only the necessary columns for the cards
// We filter by the desired category and only show 'available' pets (optional, but good practice)
// Ordering by ID is good for consistency
$sql = "SELECT id, name, description, image_class FROM showcase_pets_snakes WHERE category = ? AND availability_status = 'available' ORDER BY id ASC";

$stmt = mysqli_prepare($conn, $sql);

if ($stmt) {
    // Bind the category parameter
    mysqli_stmt_bind_param($stmt, "s", $category_to_display);

    // Execute the statement
    mysqli_stmt_execute($stmt);

    // Get the result set
    $result = mysqli_stmt_get_result($stmt);

} else {
    // Handle error if statement preparation fails
    // In a real application, you might log this error or show a user-friendly message
    die("Error preparing database query: " . mysqli_error($conn));
    $result = false; // Set result to false to indicate failure
}

?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PawPeace - Rent Your Perfect Pet Companion</title>
    <link rel="icon" href="./images/file.png" type="image/icon type">
    <link rel="stylesheet" href="./another-snake.css">
    <link rel="icon" href="./images/file.png" type="image/icon type">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link
    href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@200;300;400;500;600;700;800&family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap"
    rel="stylesheet" />

    <style>
        /* Add a little style for the "no pets" message */
        .no-pets-message {
            text-align: center;
            font-size: 1.2em;
            color: #555;
            margin-top: 50px;
            width: 100%; /* Ensure it takes full width within the flex container */
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar">
        <div class="logo-container">
            <img src="./images/file.png" alt="Paw Logo" class="paw-logo">
            <a href="another-index.html" class="logo-text">PawPeace</a>
        </div>
        <div class="nav-links">
            <a href="another-index.html" class="nav-link" >Home</a>
            <a href="another-random.html" class="active nav-link">Rent-a-pet</a>
            <a href="past-rentals.html"  class="nav-link" >Past rentals</a>
            <a href="book-visit.html" class="nav-link" >Book a visit</a>
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
                    <a href="profile.html">Profile</a>
                    <a href="settings.html">Settings</a>
                    <a href="favorites.html">Favorites</a>
                    <a href="logout.html">Logout</a>
                </div>
            </div>
        </div>
    </nav>

    <main class="pet-gallery">
        <!-- Dynamically display the category title -->
        <h2 class="category-title"><?= htmlspecialchars($category_to_display) ?>s & Similar</h2>

        <div class="pet-row">  <!-- Start the first row -->
        <?php
        // Check if the query was successful and returned rows
        if ($result && mysqli_num_rows($result) > 0) {
            $pet_count = 0; // Initialize counter for row breaks
            // Loop through each row (pet) from the database result
            while ($pet = mysqli_fetch_assoc($result)) {
                // Every 3 pets, close the current row and start a new one
                if ($pet_count > 0 && $pet_count % 3 == 0) {
                    echo '</div>'; // Close previous row
                    echo '<div class="pet-row">'; // Start new row
                }
        ?>
                <!-- Start: HTML structure for ONE pet -->
                <div class="container" id="pet-<?= htmlspecialchars($pet['id']) ?>"> <!-- Add unique ID -->
                    <div class="wrapper">
                        <!-- Use the image_class from the database -->
                        <div class="<?= htmlspecialchars($pet['image_class']) ?>"></div>
                        <!-- Use the name from the database -->
                        <h2><?= htmlspecialchars($pet['name']) ?></h2>
                        <!-- Use the description from the database -->
                        <p><?= htmlspecialchars($pet['description']) ?></p>
                    </div>
                    <div class="button-wrapper">
                        <!-- Links can potentially go to a detail page with pet ID -->
                        <a href="./pet_details.php?id=<?= htmlspecialchars($pet['id']) ?>"><button class="btn outline">DETAILS</button></a>
                        <!-- Links can potentially go to a form page with pet ID -->
                        <a href="./form.php?pet_id=<?= htmlspecialchars($pet['id']) ?>"><button class="btn fill">RENT NOW</button></a>
                    </div>
                </div>
                <!-- End: HTML structure for ONE pet -->
        <?php
                $pet_count++; // Increment pet counter
            } // End while loop

            // Close the last row if it wasn't closed inside the loop (i.e., total pets not a multiple of 3)
            // This closing div is already outside the loop, handling the final row correctly.

        } else {
            // Display a message if no pets were found in this category
            // Ensure the wrapping row div is closed if it was opened
             echo '<p class="no-pets-message">No ' . htmlspecialchars($category_to_display) . 's currently available for rent.</p>';
        }

        // Close the statement if it was prepared successfully
        if ($stmt) {
            mysqli_stmt_close($stmt);
        }
        // Close the database connection
        mysqli_close($conn);
        ?>
        </div> <!-- Close the final pet-row -->
    </main>


    <!-- JavaScript (Remains the same, but search might need adjustment later for dynamic content) -->
    <script>
        // Search functionality (Basic - may need enhancement for dynamic content loading/filtering)
        document.getElementById('petSearch').addEventListener('keyup', function(event) {
            if (event.key === 'Enter') {
                const searchTerm = this.value.toLowerCase().trim();
                const petContainers = document.querySelectorAll('.pet-gallery .container');
                let found = false;

                petContainers.forEach(container => {
                    const petName = container.querySelector('h2').textContent.toLowerCase();
                    if (petName.includes(searchTerm)) {
                        // Option 1: Scroll to the first match
                        if (!found) {
                             container.scrollIntoView({ behavior: 'smooth', block: 'center' });
                             // Highlight effect (optional)
                             container.style.transition = 'transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out';
                             container.style.transform = 'scale(1.03)';
                             container.style.boxShadow = '0 0 20px rgba(0, 212, 255, 0.7)';
                             setTimeout(() => {
                                container.style.transform = 'scale(1)';
                                container.style.boxShadow = 'none';
                             }, 1500);
                        }
                        found = true;
                        // Option 2: Hide non-matching pets (more complex, usually needs JS filtering)
                    }
                });

                if (!found && searchTerm !== '') {
                    alert('Pet not found matching "' + searchTerm + '".');
                } else if (!found && searchTerm === '') {
                     // Maybe reset view or do nothing if search is empty
                }
            }
        });


        // Dropdown functionality (Remains the same)
        document.querySelector('.account-btn').addEventListener('click', function() {
            document.querySelector('.dropdown-content').classList.toggle('show');
        });

        // Close dropdown when clicking outside (Remains the same)
        window.addEventListener('click', function(event) {
            if (!event.target.matches('.account-btn') &&
                !event.target.closest('.account-btn') && /* More robust check */
                !event.target.closest('.dropdown-content')) { /* Check if click is inside dropdown */
                const dropdowns = document.getElementsByClassName('dropdown-content');
                for (let i = 0; i < dropdowns.length; i++) {
                    if (dropdowns[i].classList.contains('show')) {
                        dropdowns[i].classList.remove('show');
                    }
                }
            }
        });
    </script>
</body>
</html>