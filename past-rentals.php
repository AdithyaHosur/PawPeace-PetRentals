<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - Pet Rental & Adoption</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@200;300;400;500;600;700;800&family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet" />
    <style>
        body {
            font-family: Poppins, sans-serif;
            color: white;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            /* justify-content: center;
            align-items: center; */
            min-height: 100vh;
        }

        .contact-container {
            background: rgba(0, 0, 0, 0.7);
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0px 4px 25px rgba(255, 255, 255, 0.2);
            width: 90%;
            max-width: 700px;
            text-align: center;
            /* Adjust margin-top to account for fixed header */
            margin: 120px auto 50px auto; /* top auto bottom auto */
        }

        h2 {
            color: #76ede5;
            margin-bottom: 20px;
        }

        .form-group {
            display: flex;
            gap: 20px; /* Increased spacing between first and last name */
            justify-content: space-between;
            margin-bottom: 15px;
        }

        .form-group div {
            flex: 1;
        }

        label {
            font-weight: 600;
            display: block;
            text-align: left;
            margin-top: 10px;
            font-size: 14px;
            margin-bottom: 3px;
        }

        input, select, textarea {
            width: 100%;
            padding: 12px;
            margin-top: 0;
            border: 1px solid #76ede5;
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.2);
            color: white;
            font-size: 14px;
            transition: 0.3s ease;
        }

        input::placeholder, textarea::placeholder {
            color: rgba(255, 255, 255, 0.7);
        }

        input:focus, select:focus, textarea:focus {
            outline: none;
            border-color: #76ede5;
            background: rgba(255, 255, 255, 0.3);
        }

        /* Improved Dropdown Styling */
        select { 
          appearance: none; cursor: pointer; 
          background: rgba(255, 255, 255, 0.2) url('data:image/svg+xml;charset=US-ASCII,<svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="%23ffffff" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z"/></svg>') no-repeat right 10px center; background-size: 12px 12px; 
        } /* Added basic arrow */

        select option {
            background: #333;
            color: white;
        }

        .hidden {
            display: none;
        }

        button {
            background: #76ede5;
            color: rgba(0, 0, 0, 0.8);
            border: none;
            padding: 14px;
            border-radius: 10px;
            font-size: 16px;
            cursor: pointer;
            margin-top: 20px;
            width: 100%;
            transition: 0.3s ease;
            font-weight: bold;
        }

        button:hover {
            background: #5fcac0;
            transform: translateY(-2px);
            box-shadow: 0px 4px 15px rgba(102, 252, 255, 0.3);
        }

        /* Gradient Background */
        .gradient-background {
            background: linear-gradient(300deg, rgba(135,206,235,0.8) ,rgba(152,255,152,0.8), rgba(135,206,235,0.8));
            background-size: 180% 180%;
            animation: gradient-animation 8s ease infinite;
        }
        
        @keyframes gradient-animation {
        0% {
            background-position: 0% 50%;
        }
        50% {
            background-position: 100% 50%;
            color: white;
        }
        100% {
            background-position: 0% 50%;
        }
        }

        .paw-logo {
            width: 40px;
            height: 40px;
            margin-right: 10px;
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

        ::selection {
            background-color: #7dd8ea; /* Light red background */
            color: #fff; /* Black text */
            text-shadow: 1px 1px 2px black;
        }

    </style>
</head>
<body class="gradient-background">

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
                <li><a href="./book-a-visit.php" class="nav-link px-3 link-dark">Book a visit</a></li>
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

<div class="contact-container">
    <h2>Contact Us - Rent or Adopt a Pet</h2>
    <form id="petForm" action="process_inquiry.php" method="POST">
        <div class="form-group">
            <div>
                <label for="firstName">First Name:</label>
                <input type="text" id="firstName" name="firstName" placeholder="John" required>
            </div>
            <div>
                <label for="lastName">Last Name:</label>
                <input type="text" id="lastName" name="lastName" placeholder="Doe" required>
            </div>
        </div>

        <label for="email">Email Address:</label>
        <input type="email" id="email" name="email" placeholder="example@email.com" required>

        <label for="phone">Phone Number:</label>
        <input type="tel" id="phone" name="phone" placeholder="+1 234 567 890" required>

        <label for="address">Address:</label>
        <textarea id="address" name="address" rows="2" placeholder="Your full address" required></textarea>

        <label for="pet-type">What type of pet are you interested in?</label>
        <select id="pet-type" name="petType" required>
            <option value="" disabled selected>Select a pet type</option>
            <option value="dog">Dog</option>
            <option value="cat">Cat</option>
            <option value="fish">Fish</option>
            <option value="turtle">Turtle</option>
            <option value="other">Other</option>
        </select>

        <label for="inquiryType">Are you looking to rent or adopt?</label>
        <select id="inquiryType" name="inquiryType" required>
            <option value="" disabled selected>Select an option</option>
            <option value="rent">Rent</option>
            <option value="adopt">Adopt</option>
        </select>

        <label for="petExperience">Do you have previous experience with pets?</label>
        <select id="petExperience" name="petExperience" onchange="toggleExperienceForm()" required>
            <option value="" disabled selected>Select an option</option>
            <option value="yes">Yes</option>
            <option value="no">No</option>
        </select>

        <div id="experience-form" class="hidden">
            <label for="pastPets">What pets have you owned before?</label>
            <input type="text" id="pastPets" name="pastPets" placeholder="E.g., Dogs, Cats">

            <label for="careExperience">Briefly describe your experience in taking care of pets:</label>
            <textarea id="careExperience" name="careExperience" rows="3" placeholder="Share your experience..."></textarea>
        </div>

        <label for="additionalInfo">Any additional information?</label>
        <textarea id="additionalInfo" name="additionalInfo" rows="4" placeholder="Optional"></textarea>

        <button type="submit">Submit Inquiry</button>
    </form>
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
            <li><a href="./contact.php" class="footer-link">Contact Us</a></li> <!-- Added Contact link -->
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

<script>
    function toggleExperienceForm() {
        let experienceForm = document.getElementById("experience-form");
        let experienceSelect = document.getElementById("petExperience").value; // Use the correct ID here

        if (experienceSelect === "yes") {
            experienceForm.classList.remove("hidden");
        } else {
            experienceForm.classList.add("hidden");
            // Optionally clear hidden fields when switching to 'no'
             document.getElementById("pastPets").value = "";
             document.getElementById("careExperience").value = "";
        }
    }
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
         integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe"
         crossorigin="anonymous">
</script>

</body>
</html>
