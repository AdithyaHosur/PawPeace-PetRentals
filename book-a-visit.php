<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Past Rentals</title>
    
  <link rel="icon" href="./images/file.png" type="image/icon type">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link
    href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@200;300;400;500;600;700;800&family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet" />
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: linear-gradient(-45deg, #7dd8eaa2, #6ef0a1, #3faef5, #1cc08a);
            background-size: 400% 400%;
            animation: gradientBG 18s ease infinite;
            flex-direction: column;
        }
        
        /* Header Styles */
        header {
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            position: fixed; /* Changed from sticky to fixed */
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            background-color: rgba(255, 255, 255, 0.5); /* Added transparency */
            backdrop-filter: blur(10px); /* Adds blur effect behind the navbar */
            -webkit-backdrop-filter: blur(10px); /* For Safari support */
        }

        .nav-link {
            font-weight: 500;
            transition: all 0.3s ease;
            position: relative;
        }

        .nav-link:hover {
            color: #03c2fc !important;
        }

        .nav-link:after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            background: #03c2fc;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            transition: width 0.3s;
        }

        .nav-link:hover:after {
            width: 70%;
        }

        .dropdown-menu {
        border-radius: 8px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        border: none;
        margin-top: 10px;
        }

        .dropdown-item {
        padding: 10px 20px;
        transition: all 0.2s ease;
        }

        .dropdown-item:hover {
        background-color: var(--light-bg);
        color: var(--primary-color);
        }

        .search-box {
        border-radius: 20px;
        border: 1px solid #e0e0e0;
        padding-left: 15px;
        transition: all 0.3s ease;
        }

        .search-box:focus {
        box-shadow: 0 0 0 0.25rem rgba(74, 144, 226, 0.25);
        border-color: var(--primary-color);
        }

        /* Footer */
        .footer {
        background-color: rgba(255, 255, 255, 0.3);
        color: black;
        border-radius: 20px 20px 0 0;
        padding: 60px 0 30px;
        margin-top: 50px;
        width: 100%;
        }

        .footer-heading {
        font-size: 1.2rem;
        font-weight: 600;
        margin-bottom: 20px;
        }

        .footer-link {
        color: rgba(0, 0, 0, 0.8);
        text-decoration: none;
        margin-bottom: 10px;
        display: block;
        transition: all 0.3s ease;
        }

        .footer-link:hover {
        color: black;
        transform: translateX(5px);
        text-decoration: none;        
        }

        .social-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background-color: rgba(0, 0, 0, 0.9);
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 10px;
        transition: all 0.3s ease;
        text-decoration: none;
        }

        .social-icon:hover {
        background-color: rgba(0, 0, 0, 0.5);
        transform: translateY(-3px);
        }

        .footer-bottom {
        padding-top: 30px;
        border-top: 1px solid rgba(255, 255, 255, 0.1);
        margin-top: 30px;
        }

        /* Animation */
        @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
        }

        .animate-fade-in {
        animation: fadeIn 0.5s ease forwards;
        }

        .delay-1 {
        animation-delay: 0.1s;
        }

        .delay-2 {
        animation-delay: 0.2s;
        }

        .delay-3 {
        animation-delay: 0.3s;
        }

        .paw-logo {
            width: 40px;
            height: 40px;
            margin-right: 10px;
        }

        ::selection {
            background-color: #7dd8ea; /* Light red background */
            color: #fff; /* Black text */
            text-shadow: 1px 1px 2px black;
        }

        @keyframes gradientBG {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .form-container {
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(50px);
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.2);
            width: 90%;
            max-width: 600px;
            margin-top: 120px;
            margin-bottom: -20px;
            color: black;
        }

        .form-container h2 {
            text-align: center;
            margin-bottom: 20px;
            color: black;
        }

        .form-control {
            border-radius: 8px;
            padding: 12px;
            color: black;
            background-color: rgba(255, 255, 255, 0.7); /* Slightly opaque background */
            border: 1px solid rgba(0, 0, 0, 0.2);
        }

        .form-control::placeholder { /* Style placeholders */
           color: #555;
        }

        .form-label { /* Style labels */
           color: black; /* Ensure labels are visible */
           font-weight: 500;
        }

        .btn-submit {
            width: 100%;
            background: #03c2fc;
            border: none;
            padding: 12px;
            border-radius: 8px;
            font-weight: bold;
            transition: all 0.3s ease;
            color: black;
        }

        .btn-submit:hover {
            background: #0288d1;
            transform: scale(1.05);
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="py-3 mb-4">
        <div class="container">
        <div class="d-flex flex-wrap align-items-center justify-content-between">
            <div class="d-flex align-items-center">
            <a href="another-index.php" class="d-flex align-items-center text-decoration-none me-4">
                <img src="./images/file.png" alt="Paw Logo" class="paw-logo">
                <span class="fs-4 fw-bold text-dark">PawPeace</span>
            </a>
            <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 mb-md-0">
                <li><a href="./another-index.php" class="nav-link px-3 link-dark active">Home</a></li>
                <li><a href="./another-random.php" class="nav-link px-3 link-dark">Rent-a-pet</a></li>
                <li><a href="./past-rentals.php" class="nav-link px-3 link-dark">Past rentals</a></li>
                <li><a href="book-a-visit.php" class="nav-link px-3 link-dark">Book a visit</a></li>
                <?php if (isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'admin'): ?>
                    <li><a href="./admin_manage_pets.php" class="nav-link px-3 link-warning">Manage Pets</a></li>
                 <?php endif; ?>
            </ul>
            </div>

            <div class="d-flex align-items-center">
            <form class="me-3" role="search">
                <input type="search" class="form-control search-box" placeholder="Search pets..." aria-label="Search">
            </form>

            <div class="dropdown">
                <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
                <img src="https://api.dicebear.com/6.x/avataaars/svg?seed=Felix" alt="User Avatar" width="32" height="32" class="rounded-circle me-2">
                <span class="d-none d-sm-inline">My Account</span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownUser1">
                <li><a class="dropdown-item" href="profile.php"><i class="fas fa-user-circle me-2"></i>Profile</a></li>
                <li><a class="dropdown-item" href="#"><i class="fas fa-heart me-2"></i>Favorites</a></li>
                <li><a class="dropdown-item" href="#"><i class="fas fa-history me-2"></i>Rental History</a></li>
                <li><a class="dropdown-item" href="#"><i class="fas fa-cog me-2"></i>Settings</a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item" href="#"><i class="fas fa-sign-out-alt me-2"></i>Sign out</a></li>
                </ul>
            </div>
            </div>
        </div>
        </div>
    </header>

    <div class="form-container">
        <h2>Book A Visit</h2>
        <!-- **** MODIFIED FORM **** -->
        <form action="process_visit_request.php" method="POST"> 
            <div class="mb-3">
                <!-- Changed label 'for' to match input 'id' -->
                <label for="fullName" class="form-label">Full Name</label> 
                <!-- Added name="fullName" -->
                <input type="text" class="form-control" id="fullName" name="fullName" placeholder="Enter your full name" required> 
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email Address</label>
                <!-- Added name="email" -->
                <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" required>
            </div>
            <div class="mb-3">
                <label for="phone" class="form-label">Phone Number</label>
                <!-- Added name="phone" -->
                <input type="tel" class="form-control" id="phone" name="phone" placeholder="Enter your phone number" required>
            </div>
            <div class="mb-3">
                <label for="visitDate" class="form-label">Preferred Visit Date</label>
                <!-- Added name="visitDate" -->
                <input type="date" class="form-control" id="visitDate" name="visitDate" required>
            </div>
            <div class="mb-3">
                <label for="reason" class="form-label">Reason for Visit</label>
                <!-- Added name="reason" -->
                <textarea class="form-control" id="reason" name="reason" rows="4" placeholder="Explain why you want to visit" required></textarea>
            </div>
            <div class="mb-3">
                <label for="petsInterest" class="form-label">Pets You’re Interested In</label>
                <!-- Added name="petsInterest" -->
                <select class="form-control" id="petsInterest" name="petsInterest" required>
                    <option value="">Select a pet category</option>
                    <option value="Dogs">Dogs</option>
                    <option value="Cats">Cats</option>
                    <option value="Rabbits">Rabbits</option>
                    <option value="Birds">Birds</option>
                </select>
            </div>
            <button type="submit" class="btn-submit">Submit Request</button> 
        </form>
        <!-- **** END MODIFIED FORM **** -->
    </div>

    <!-- Footer -->
    <footer id="contact" class="footer">
        <div class="container">
        <div class="row">
            <div class="col-lg-4 mb-4 mb-lg-0">
            <div class="d-flex align-items-center mb-4">
                <i class="fas fa-paw fa-2x me-2"></i>
                <h5 class="m-0 fs-4 fw-bold">PawPeace</h5>
            </div>
            <p>PawPeace brings joy to your life through our pet rental service. Experience the companionship of a furry friend without long-term commitments.</p>
            <div class="d-flex mt-4">
                <a href="#" class="social-icon me-2"><i class="fab fa-facebook-f text-white"></i></a>
                <a href="#" class="social-icon me-2"><i class="fab fa-twitter text-white"></i></a>
                <a href="#" class="social-icon me-2"><i class="fab fa-instagram text-white"></i></a>
                <a href="#" class="social-icon"><i class="fab fa-linkedin-in text-white"></i></a>
            </div>
            </div>
            <div class="col-lg-2 col-md-4 col-6 mb-4 mb-md-0">
            <h6 class="footer-heading">Quick Links</h6>
            <ul class="list-unstyled">
                <li><a href="#" class="footer-link">Home</a></li>
                <li><a href="./random.html" class="footer-link">Rent-a-pet</a></li>
                <li><a href="#" class="footer-link">Past rentals</a></li>
                <li><a href="#" class="footer-link">Book a visit</a></li>
            </ul>
            </div>
            <div class="col-lg-2 col-md-4 col-6 mb-4 mb-md-0">
            <h6 class="footer-heading">Help & Info</h6>
            <ul class="list-unstyled">
                <li><a href="#" class="footer-link">FAQs</a></li>
                <li><a href="#" class="footer-link">Terms of Service</a></li>
                <li><a href="#" class="footer-link">Privacy Policy</a></li>
                <li><a href="#" class="footer-link">Support</a></li>
            </ul>
            </div>
            <div class="col-lg-4 col-md-4">
            <h6 class="footer-heading">Contact Us</h6>
            <ul class="list-unstyled">
                <li class="d-flex mb-2">
                <i class="fas fa-map-marker-alt me-3 mt-1"></i>
                <span>123 Pet Lane, Animalville, PAW 56789</span>
                </li>
                <li class="d-flex mb-2">
                <i class="fas fa-phone-alt me-3 mt-1"></i>
                <span>+91 1234567890</span>
                </li>
                <li class="d-flex mb-2">
                <i class="fas fa-envelope me-3 mt-1"></i>
                <span>hello@pawpeace.com</span>
                </li>
            </ul>
            <form class="mt-3">
                <div class="input-group">
                <input type="email" class="form-control" placeholder="Enter your email" style="border-radius: 20px 0 0 20px;">
                <button class="btn btn-light" id="subscribe-button" type="button" style="border-radius: 0 20px 20px 0;">Subscribe</button>
                </div>
            </form>
            </div>
        </div>
        <div class="row footer-bottom">
            <div class="col-md-6 text-center text-md-start">
            <p class="mb-0">© 2025 PawPeace. All rights reserved</p>
            </div>
            <div class="col-md-6 text-center text-md-end">
            <p class="mb-0">Made with <i class="fas fa-heart" style="color: #ff6b6b"></i></p>
            </div>
        </div>
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe"
            crossorigin="anonymous"></script>

</body>
</html>
