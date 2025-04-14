<?php
session_start(); // Optional: For potential future admin login
require_once 'db_connect.php'; // Ensure path is correct

// --- Initialize Variables ---
$name = "";
$description = "";
$image_class = "";
$category = ""; // This will determine the target table
$availability_status = "available";
$message = "";
$message_type = "";

// --- Form Processing Logic ---
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // --- Retrieve and Sanitize Form Data ---
    $name = trim($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $image_class = trim($_POST['image_class'] ?? '');
    $category = trim($_POST['category'] ?? ''); // Crucial for table selection
    $availability_status = trim($_POST['availability_status'] ?? 'available');

    // --- Basic Validation ---
    if (empty($name) || empty($description) || empty($image_class) || empty($category) || empty($availability_status)) {
        $message = "Error: All fields are required.";
        $message_type = "error";
    } else {
        // --- Determine Target Table Based on Category ---
        $target_table = null; // Initialize target table name
        switch ($category) {
            case 'Reptile':
                $target_table = 'pets'; // Reptiles go into the original 'pets' table
                break;

            case 'Aquatic':
            case 'Bird':
            case 'Mammal': // Assuming non-dog/cat mammals go here
            case 'Other':
                $target_table = 'other_pets'; // Fish, Birds, Hamsters etc.
                break;

            case 'Dog':
            case 'Cat':
            case 'Pair': // For the Doggo vs Kitty pair
                $target_table = 'dogs'; // Dogs, Cats, Pairs
                break;

            default:
                $message = "Error: Invalid pet category selected. Cannot determine target table.";
                $message_type = "error";
                break; // Stop processing if category is invalid
        }

        // --- Proceed only if a valid target table was determined ---
        if ($target_table) {
            // --- Prepare SQL Statement (Prevents SQL Injection) ---
            // Table name comes from our validated logic, not directly from user input
            // Column names are assumed to be the same across tables
            $sql = "INSERT INTO `" . $target_table . "` (name, description, image_class, category, availability_status) VALUES (?, ?, ?, ?, ?)";
            $stmt = mysqli_prepare($conn, $sql);

            if ($stmt) {
                // --- Bind Parameters ---
                mysqli_stmt_bind_param($stmt, "sssss",
                    $name,
                    $description,
                    $image_class,
                    $category,
                    $availability_status
                );

                // --- Execute Statement ---
                if (mysqli_stmt_execute($stmt)) {
                    $message = "Success: New pet ('" . htmlspecialchars($name) . "') added successfully to the '" . $target_table . "' table!";
                    $message_type = "success";
                    // Clear form fields
                    $name = ""; $description = ""; $image_class = ""; $category = ""; $availability_status = "available";
                } else {
                    error_log("SQL Execute Error (Table: $target_table): " . mysqli_stmt_error($stmt));
                    $message = "Error: Could not add pet to the database. Please check logs or try again.";
                    $message_type = "error";
                }
                // --- Close Statement ---
                mysqli_stmt_close($stmt);
            } else {
                 error_log("SQL Prepare Error (Table: $target_table): " . mysqli_error($conn));
                 $message = "Error: Database error preparing the statement. Please contact support.";
                 $message_type = "error";
            }
        } // End if ($target_table)

    } // End basic validation else
    // --- Close Connection ---
     mysqli_close($conn);
} // End of POST processing
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PawPeace Admin - Add Pet</title>
    <link rel="icon" href="./images/file.png" type="image/icon type">
    <!-- Link the SAME CSS file used by other pages -->
    <link rel="stylesheet" href="./another-snake.css"> <!-- Adjust path if needed -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link
    href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@200;300;400;500;600;700;800&family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap"
    rel="stylesheet" />
    <style>
        /* Add specific styles for the admin form container */
        body { /* Ensure gradient background is applied if not default */
            background: linear-gradient(00deg, rgba(135,206,235,0.8) ,rgba(152,255,152,0.8), rgba(135,206,235,0.8));
            background-size: 180% 180%;
            animation: gradient-animation 8s ease infinite;
            min-height: 100vh; /* Ensure body takes full height */
            display: flex; /* Needed for flex column */
            flex-direction: column; /* Stack navbar and content */
             overflow-x: hidden; /* Prevent horizontal scroll */
        }
        @keyframes gradient-animation { /* Make sure animation is defined */
          0% { background-position: 0% 50%; } 50% { background-position: 100% 50%; } 100% { background-position: 0% 50%; }
        }
        .admin-form-container {
            max-width: 750px;
            margin: 100px auto 50px auto; /* Increased top margin */
            padding: 35px 45px;
            background-color: rgba(255, 255, 255, 0.75);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            border-radius: 15px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
            color: #333;
        }
        .admin-form-container h2 {
            text-align: center;
            margin-bottom: 35px;
            color: #1a2e3b;
            font-size: 2.2rem;
            font-weight: 600;
             text-transform: none;
        }
        .form-group { margin-bottom: 22px; }
        .form-group label { display: block; margin-bottom: 8px; font-weight: 600; color: #444; }
        .form-group input[type="text"],
        .form-group textarea,
        .form-group select {
            width: 100%; padding: 12px 15px; border: 1px solid #ccc; border-radius: 6px;
            font-family: 'Poppins', sans-serif; font-size: 1rem; box-sizing: border-box;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }
        .form-group input[type="text"]:focus,
        .form-group textarea:focus,
        .form-group select:focus {
            border-color: #00bfff; outline: none; box-shadow: 0 0 0 2px rgba(0, 191, 255, 0.2);
        }
        .form-group textarea { min-height: 100px; resize: vertical; }
        .form-group .hint { font-size: 0.85em; color: #666; margin-top: 6px; }
        .submit-btn {
            display: block; width: 100%; padding: 15px; background-color: #00aae0; /* Slightly adjusted blue */
            color: white; border: none; border-radius: 8px; font-size: 1.15rem; font-weight: 600;
            cursor: pointer; transition: background-color 0.3s ease, transform 0.2s ease;
            font-family: 'Poppins', sans-serif; margin-top: 15px;
        }
        .submit-btn:hover { background-color: #008cbf; transform: translateY(-2px); }
        .message { padding: 15px 20px; margin-bottom: 25px; border-radius: 8px; font-weight: 500; text-align: center; border: 1px solid transparent; }
        .message.success { background-color: #e0f8e9; color: #1d7a3d; border-color: #b8eecd; }
        .message.error { background-color: #fdecea; color: #a91e2c; border-color: #f9c6ca; }

        .verification-links {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            text-align: center;
        }
        .verification-links h3 {
            margin-bottom: 15px;
            font-size: 1.1rem;
            color: #555;
            font-weight: 600;
        }
        .verification-links a {
            display: inline-block; /* Changed to inline-block */
            margin: 5px 10px; /* Added horizontal margin */
            font-size: 1rem;
            color: #007bff;
            text-decoration: none;
            padding: 8px 15px; /* Added padding for button-like feel */
            border: 1px solid #007bff;
            border-radius: 5px;
            transition: background-color 0.3s ease, color 0.3s ease;

        }
        .verification-links a:hover {
            background-color: #007bff;
            color: white;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <!-- Navbar (Copy the exact HTML from your other pages) -->
    <nav class="navbar">
        <div class="logo-container">
            <img src="./images/file.png" alt="Paw Logo" class="paw-logo">
            <a href="another-index.php" class="logo-text">PawPeace</a>
        </div>
        <div class="nav-links">
            <!-- Keep relevant links, maybe remove user links if this is purely admin? -->
            <a href="another-index.php" class="nav-link" >Home</a>
            <a href="reptiles.php" class="nav-link">Reptiles</a>
            <a href="other_pets.php" class="nav-link">Other Pets</a>
            <a href="dogs_cats.php" class="nav-link">Dogs & Cats</a>
            <a href="admin_manage_pets.php" class="active nav-link">Manage Pets</a> <!-- This page -->
        </div>
        <div class="search-account">
            <!-- Search might not be needed -->
             <div class="search-container" style="visibility: hidden;"> <!-- Hide search for now -->
                <input type="text" id="petSearch" placeholder="Search pets..." class="search-input">
                <button class="search-btn"><i class="fas fa-search"></i></button>
            </div>
            <div class="account-dropdown">
                <button class="account-btn">
                    <img src="./images/profile.png" alt="Profile" class="profile-img">
                    <span>Admin</span> <!-- Simple Admin label -->
                    <i class="fas fa-caret-down"></i>
                </button>
                <div class="dropdown-content">
                    <a href="#">Dashboard</a> <!-- Placeholder links -->
                    <a href="#">Settings</a>
                    <a href="logout.php">Logout</a> <!-- Assuming you have logout -->
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Admin Content -->
    <main class="admin-form-container">
        <h2>Add New Pet</h2>

        <?php if (!empty($message)): ?>
            <div class="message <?= htmlspecialchars($message_type) ?>">
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>

        <form action="<?= htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label for="name">Pet Name:</label>
                <input type="text" id="name" name="name" value="<?= htmlspecialchars($name) ?>" required>
            </div>

            <div class="form-group">
                <label for="description">Description:</label>
                <textarea id="description" name="description" required><?= htmlspecialchars($description) ?></textarea>
            </div>

            <div class="form-group">
                <label for="image_class">Image CSS Class:</label>
                <input type="text" id="image_class" name="image_class" value="<?= htmlspecialchars($image_class) ?>" required>
                <p class="hint">Enter the CSS class (e.g., 'banner-image-woofer'). Ensure it's defined in the CSS.</p>
            </div>

            <div class="form-group">
                <label for="category">Category (Determines Page/Table):</label>
                <select id="category" name="category" required>
                    <option value="" disabled <?= empty($category) ? 'selected' : '' ?>>-- Select Category --</option>
                    <option value="Reptile" <?= ($category === 'Reptile') ? 'selected' : '' ?>>Reptile (Goes to Reptiles Page)</option>
                    <option value="Aquatic" <?= ($category === 'Aquatic') ? 'selected' : '' ?>>Aquatic (Goes to Other Pets)</option>
                    <option value="Bird" <?= ($category === 'Bird') ? 'selected' : '' ?>>Bird (Goes to Other Pets)</option>
                    <option value="Mammal" <?= ($category === 'Mammal') ? 'selected' : '' ?>>Small Mammal (Goes to Other Pets)</option>
                    <option value="Dog" <?= ($category === 'Dog') ? 'selected' : '' ?>>Dog (Goes to Dogs & Cats)</option>
                    <option value="Cat" <?= ($category === 'Cat') ? 'selected' : '' ?>>Cat (Goes to Dogs & Cats)</option>
                    <option value="Pair" <?= ($category === 'Pair') ? 'selected' : '' ?>>Dog/Cat Pair (Goes to Dogs & Cats)</option>
                    <option value="Other" <?= ($category === 'Other') ? 'selected' : '' ?>>Other (Goes to Other Pets)</option>
                </select>
                 <p class="hint">Selecting the category directs the pet to the correct page and database table.</p>
            </div>

            <div class="form-group">
                <label for="availability_status">Availability Status:</label>
                <select id="availability_status" name="availability_status" required>
                    <option value="available" <?= ($availability_status === 'available') ? 'selected' : '' ?>>Available</option>
                    <option value="rented" <?= ($availability_status === 'rented') ? 'selected' : '' ?>>Rented</option>
                    <option value="unavailable" <?= ($availability_status === 'unavailable') ? 'selected' : '' ?>>Unavailable</option>
                </select>
            </div>

            <button type="submit" class="submit-btn">Add Pet to Catalogue</button>
        </form>

        <!-- Verification Links -->
        <div class="verification-links">
            <h3>Check Pet Pages:</h3>
            <a href="reptiles.php" target="_blank">Reptiles Page →</a>
            <a href="other_pets.php" target="_blank">Other Pets Page →</a>
            <a href="dogs_cats.php" target="_blank">Dogs & Cats Page →</a>
        </div>

    </main>

    <!-- JavaScript for Dropdown -->
     <script>
        // Dropdown functionality
        const accountBtn = document.querySelector('.account-btn');
        const dropdownContent = document.querySelector('.dropdown-content');

        if(accountBtn && dropdownContent) {
             accountBtn.addEventListener('click', function(event) {
                 event.stopPropagation();
                 dropdownContent.classList.toggle('show');
             });

             window.addEventListener('click', function(event) {
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