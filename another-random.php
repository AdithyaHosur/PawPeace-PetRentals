<?php
// Start session - good practice if you add user login features later
session_start();

// Include database connection - might be needed for other elements eventually
require_once 'db_connect.php'; // Make sure this path is correct

// No specific PHP logic needed to DISPLAY content on this page yet.

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>PawPeace - Rent-A-Pet Guide</title> <!-- Updated Title -->
    <link rel="icon" href="./images/file.png" type="image/icon type">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@200;300;400;500;600;700;800&family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap"
      rel="stylesheet"
    />

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

    <!-- Inline Styles (Copied from original) -->
    <style>
      :root {
        --primary-color: #4a90e2;
        --secondary-color: #6c5ce7;
        --accent-color: #ff9f43;
        --text-dark: #2d3436;
        --text-light: #636e72;
        --light-bg: #f8f9fa;
        --white: #ffffff;
      }
      /* Header Styles */
      header {
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        z-index: 1000;
        background-color: rgba(255, 255, 255, 0.6);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
      }
      .nav-link {
        font-weight: 500; transition: all 0.3s ease; position: relative;
      }
      .nav-link:hover { color: var(--primary-color) !important; }
      .nav-link:after {
        content: ''; position: absolute; width: 0; height: 2px; background: var(--primary-color);
        bottom: 0; left: 50%; transform: translateX(-50%); transition: width 0.3s;
      }
       .nav-link.active:after { /* Style for active link underline */
        width: 70%; background: var(--primary-color);
       }
      .nav-link:hover:after { width: 70%; }
      .dropdown-menu {
        border-radius: 8px; box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1); border: none; margin-top: 10px !important;
      }
      .dropdown-item { padding: 10px 20px; transition: all 0.2s ease; }
      .dropdown-item:hover { background-color: var(--light-bg); color: var(--primary-color); }
      .search-box {
        border-radius: 20px; border: 1px solid #e0e0e0; padding-left: 15px; transition: all 0.3s ease;
      }
      .search-box:focus { box-shadow: 0 0 0 0.25rem rgba(74, 144, 226, 0.25); border-color: var(--primary-color); }
      .paw-logo { width: 40px; height: 40px; margin-right: 10px; }

      body {
        font-family: 'Poppins', sans-serif; color: var(--text-dark); overflow-x: hidden;
        width: 100%; /* height: fit-content; */ margin: 0; padding: 0;
        padding-top: 76px; /* Adjust this value based on final navbar height */
      }
      /* Center and Stack Area Styles */
      .center {
        width: 100%; height: fit-content; display: flex; align-items: center;
        justify-content: center; flex-direction: column;
      }
      .stack-area {
        width: 100%; /* height: 300vh; */ position: relative; display: flex;
        justify-content: center; margin-top: 50px; /* Add some space */
      }
      .right, .left {
        /* height: 110vh; */ display: flex; align-items: center; justify-content: center;
        position: sticky; top: 80px; /* Stick below navbar */ box-sizing: border-box; flex-basis: 50%;
        padding: 20px; /* Add padding */
      }
      .cards { width: 100%; height: 500px; /* Fixed height for card area */ position: relative; }
      .card {
        width: 350px; height: 350px; box-sizing: border-box; padding: 35px; border-radius: 6mm;
        display: flex; justify-content: space-between; flex-direction: column; position: absolute;
        top: 50%; left: 50%; transition: transform 0.5s ease-in-out; /* Only transition transform */
        color: white; /* Ensure text is visible */
      }
      .card:nth-child(1) { background: rgb(64, 122, 255); z-index: 5; }
      .card:nth-child(2) { background: rgb(221, 62, 88); z-index: 4; }
      .card:nth-child(3) { background: rgb(186, 113, 245); z-index: 3; }
      .card:nth-child(4) { background: rgb(247, 92, 208); z-index: 2; }
      .card:nth-child(5) { background: lightgreen; z-index: 1; }
      .sub { font-family: poppins; font-size: 18px; font-weight: 500; line-height: 1.4; } /* Adjusted font */
      .content { font-family: poppins; font-size: 38px; font-weight: 700; line-height: 1.2; } /* Adjusted font */
      .card.active { transform-origin: bottom left; }
      .left { align-items: center; flex-direction: column; }
      .title {
        width: 100%; max-width: 480px; font-size: clamp(3rem, 8vw, 5.25rem); /* Responsive font size */
        font-family: poppins; font-weight: 700; line-height: 1.1; margin-bottom: 20px;
      }
      .sub-title {
        width: 100%; max-width: 420px; font-family: poppins; font-size: 17px; margin-top: 20px;
        line-height: 1.6; color: var(--text-light);
      }
       .sub-title ol { padding-left: 20px; margin-top: 15px; }
       .sub-title li { margin-bottom: 8px; }
       .sub-title b { font-weight: 600; color: var(--text-dark); }
      .sub-title .button-group { margin-top: 30px; display: flex; gap: 15px; flex-wrap: wrap; } /* Button group */
      .sub-title button {
        font-family: Poppins, sans-serif; font-size: 14px; padding: 12px 25px; background: black;
        color: white; border-radius: 8mm; border: none; outline: none; cursor: pointer;
        transition: all 0.3s ease-in-out;
      }
      .sub-title button:hover {
          background: rgb(64, 122, 255); color: black;
          box-shadow: 0px 4px 15px rgba(64, 122, 255, 0.6); transform: scale(1.05); font-weight: bold;
      }

      /* Top Section */
      .top {
        width: 100%; height: 60vh; /* Reduced height */ font-family: poppins; font-size: 70px;
        display: flex; align-items: center; /* Vertically center */ justify-content: center; text-align: center;
        background-image: url("./images/doggo.jpg"); background-size: cover; background-position: center;
        position: relative; /* For overlay */ color: white;
      }
       .top::before { /* Dark overlay for better text readability */
           content: ''; position: absolute; top: 0; left: 0; width: 100%; height: 100%;
           background-color: rgba(0, 0, 0, 0.4); z-index: 1;
       }
      .heading{
        /* Removed background gradient */ padding: 20px; border-radius: 20px; font-weight: bold ;
        position: relative; z-index: 2; text-shadow: 2px 2px 5px rgba(0,0,0,0.6);
        font-size: clamp(2.5rem, 6vw, 4.375rem); /* Responsive heading */
      }

      /* Why Rent/Adopt Section */
      .why-rent-adopt {
        background-color: var(--text-dark); color: white; text-align: center;
        padding: 60px 20px; /* Increased padding */ border-radius: 0; /* Full width */
        width: 100%; margin: 0;
      }
      .why-rent-adopt h2 { font-size: clamp(1.8rem, 5vw, 2.5rem); margin-bottom: 15px; font-family: 'Poppins', sans-serif; }
      .why-rent-adopt .intro-text {
        font-size: clamp(1rem, 2.5vw, 1.125rem); margin-bottom: 40px; font-weight: 300;
        font-family: 'Poppins', sans-serif; max-width: 900px; /* Increased max-width */ margin-left: auto; margin-right: auto;
        line-height: 1.7;
      }
      .reasons-container { display: flex; flex-wrap: wrap; justify-content: center; gap: 25px; }
      .reason {
        background: rgba(255, 255, 255, 0.9); /* Slightly transparent white */ color: black; padding: 25px; border-radius: 10px;
        width: 100%; max-width: 300px; /* Increased max-width */ text-align: center;
        transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
      }
      .reason img { width: 60px; margin-bottom: 15px; }
      .reason h3 { font-size: 1.25rem; margin-bottom: 10px; }
      .reason p { font-size: 1rem; font-weight: 300; line-height: 1.5; }
      .reason:hover { transform: translateY(-8px); box-shadow: 0px 8px 20px rgba(255, 255, 255, 0.2); }

      /* Comparison Table Section */
      .table-heading { margin-top: 50px; font-size: clamp(1.6rem, 4vw, 2rem); }
      .table-container { display: flex; justify-content: center; margin-top: 30px; padding: 0 15px; }
      .comparison-table {
        width: 100%; max-width: 800px; border-collapse: collapse; background: white;
        color: black; border-radius: 10px; overflow: hidden; box-shadow: 0 5px 15px rgba(0,0,0,0.1);
      }
      .comparison-table th, .comparison-table td {
        padding: 15px; text-align: center; border-bottom: 1px solid #e0e0e0; font-size: 1rem;
      }
      .comparison-table th { background: var(--primary-color); color: white; font-size: 1.1rem; }
      .comparison-table tr:hover { background: rgba(74, 144, 226, 0.1); } /* Light blue hover */
      .comparison-table td:first-child { font-weight: 600; background: #f8f9fa; text-align: left; padding-left: 20px;}
      .comparison-table tr:last-child td { border-bottom: none; }

      /* Footer Styles (Copied from previous examples) */
      .footer {
          background: linear-gradient(to right, var(--primary-color), var(--secondary-color));
          color: white; border-radius: 20px 20px 0 0; padding: 60px 0 30px; margin-top: 50px; /* Ensure margin-top */
      }
      .footer-heading { font-size: 1.2rem; font-weight: 600; margin-bottom: 20px; color: white;}
      .footer-link { color: rgba(255, 255, 255, 0.8); text-decoration: none; margin-bottom: 10px; display: block; transition: all 0.3s ease;}
      .footer-link:hover { color: white; transform: translateX(5px);}
      .social-icon {
          width: 40px; height: 40px; border-radius: 50%; background-color: rgba(255, 255, 255, 0.1);
          display: flex; align-items: center; justify-content: center; margin-right: 10px; transition: all 0.3s ease;
      }
       .social-icon i { color: white; }
      .social-icon:hover { background-color: rgba(255, 255, 255, 0.2); transform: translateY(-3px);}
      .footer-bottom { padding-top: 30px; border-top: 1px solid rgba(255, 255, 255, 0.1); margin-top: 30px;}
      .footer p { color: rgba(255, 255, 255, 0.9); }
       .footer h5 { color: white; }


      /* Responsiveness */
      @media screen and (max-width: 991px) {
          .stack-area { flex-direction: column; height: auto; }
          .left, .right { position: relative; top: 0; flex-basis: auto; width: 100%; height: auto; padding-bottom: 50px;}
          .left { order: 1; text-align: center; } /* Text first on mobile */
          .right { order: 2; min-height: 600px; /* Ensure space for cards */ }
          .title { text-align: center; max-width: 90%; margin-left: auto; margin-right: auto; }
          .sub-title { text-align: center; max-width: 90%; margin-left: auto; margin-right: auto; }
          .sub-title .button-group { justify-content: center; }
          .cards { height: 600px; } /* Adjust card area height */
          .comparison-table th, .comparison-table td { padding: 10px; font-size: 0.9rem; }
          .comparison-table td:first-child { padding-left: 10px;}
          .footer { text-align: center; }
          .footer .d-flex { justify-content: center; }
      }
       @media screen and (max-width: 767px) {
           .card { width: 80%; max-width: 300px; height: auto; min-height: 300px; padding: 25px;}
           .content { font-size: 30px; }
           .sub { font-size: 16px; }
           .why-rent-adopt .intro-text { max-width: 100%; }
           .reasons-container { gap: 15px; }
           .reason { max-width: 90%; }
           .table-container { padding: 0 5px; }
           .comparison-table { font-size: 0.8rem; }
           .comparison-table th, .comparison-table td { padding: 8px; }
       }


      ::selection {
        background-color: #7dd8ea; color: #fff; text-shadow: 1px 1px 2px black;
      }

    </style>
  </head>
  <body>
    <!-- Header -->
    <header class="py-3 mb-4">
      <div class="container">
        <div class="d-flex flex-wrap align-items-center justify-content-between">
          <!-- Logo and Nav Links -->
          <div class="d-flex align-items-center mb-2 mb-lg-0">
            <!-- UPDATE: Link to PHP index -->
            <a href="./another-index.php" class="d-flex align-items-center text-decoration-none me-4">
              <img src="./images/file.png" alt="Paw Logo" class="paw-logo">
              <span class="fs-4 fw-bold text-dark">PawPeace</span>
            </a>
            <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 mb-md-0">
              <!-- UPDATE: Links point to PHP files -->
              <li><a href="./another-index.php" class="nav-link px-3 link-dark">Home</a></li>
              <li><a href="./another-random.php" class="nav-link px-3 link-dark active">Rent-a-pet</a></li> <!-- This page is active -->
              <li><a href="./past-rentals.php" class="nav-link px-3 link-dark">Past rentals</a></li>
              <li><a href="./book-a-visit.php" class="nav-link px-3 link-dark">Book a visit</a></li>
              <?php if (isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'admin'): ?>
                    <li><a href="./admin_manage_pets.php" class="nav-link px-3 link-warning">Manage Pets</a></li>
                 <?php endif; ?>
            </ul>
          </div>

          <!-- Search and Account Dropdown -->
          <div class="d-flex align-items-center">
            <form class="me-3" role="search">
              <input type="search" class="form-control search-box" placeholder="Search pets..." aria-label="Search">
            </form>
            <div class="dropdown">
              <!-- UPDATE: Links inside dropdown point to PHP files -->
              <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
                <img src="https://api.dicebear.com/6.x/avataaars/svg?seed=Felix" alt="User Avatar" width="32" height="32" class="rounded-circle me-2">
                <span class="d-none d-sm-inline">My Account</span>
              </a>
              <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownUser1">
                <li><a class="dropdown-item" href="profile.php"><i class="fas fa-user-circle me-2"></i>Profile</a></li>
                <li><a class="dropdown-item" href="favorites.php"><i class="fas fa-heart me-2"></i>Favorites</a></li>
                <li><a class="dropdown-item" href="past-rentals.php"><i class="fas fa-history me-2"></i>Rental History</a></li>
                <li><a class="dropdown-item" href="settings.php"><i class="fas fa-cog me-2"></i>Settings</a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item" href="logout.php"><i class="fas fa-sign-out-alt me-2"></i>Sign out</a></li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </header>

    <!-- Main Content -->
    <div class="center">
      <div class="top"><div class="heading">Your Pet-Rental Guide</div></div>
      <div class="stack-area">
        <div class="left">
          <div class="title">How it works:</div>
          <div class="sub-title">
            We offer a diverse selection of pets for rent, including dogs, cats, fish, turtles, and more. Our collection continues to grow, ensuring a variety of options for pet lovers. <br>
            <ol type="i">
                <li><b>Browse Available Pets</b> – Explore our current selection of pets to find your perfect companion.</li>
                <li><b>Choose Your Rental Duration</b> – Select the time period you wish to rent a pet for.</li>
                <li><b>Review Rental Terms</b> – Ensure you meet our care guidelines and requirements for responsible pet handling.</li>
                <li><b>Complete the Rental Process</b> – Fill out the necessary forms and provide any required documentation.</li>
                <li><b>Enjoy Your Time Together!!</b></li>
            </ol>
            <br />
            <!-- UPDATE: Button links to specific pet category pages -->
            <div class="button-group">
                <a href="./dogs_cats.php"><button>Rent Dogs/Cats</button></a>
                <a href="./reptiles.php"><button>Rent Reptiles</button></a>
                <a href="./other_pets.php"><button>Rent Other Pets</button></a>
                <a href="./another-index.php"><button>Home</button></a>
            </div>
          </div>
        </div>
        <div class="right">
          <div class="cards">
            <div class="card">
              <div class="sub">Loyal, playful, and energetic, dogs come in all shapes and sizes to provide companionship, exercise motivation, and even as working animals.</div>
              <div class="content">Dogs</div>
            </div>
            <div class="card">
              <div class="sub"> Independent but affectionate, cats offer a purrfect cuddle buddy. They're relatively low maintenance and can be a calming presence.</div>
              <div class="content">Cats</div>
            </div>
            <div class="card">
              <div class="sub">From chatty parakeets to singing canaries, birds bring a touch of nature and melodic chirps into your home. They can be quite intelligent too.</div>
              <div class="content">Birds</div>
            </div>
            <div class="card">
              <div class="sub">Tranquil and colorful, fish create a mesmerizing underwater world with a low-maintenance calming atmosphere.</div>
              <div class="content">Fish</div>
            </div>
            <div class="card">
                <div class="sub">Beyond the usual suspects, some people keep hamsters, guinea pigs, or even reptiles as pets. These exotic options offer unique challenges and interactions.</div>
                <div class="content">Others</div>
              </div>
          </div>
        </div>
      </div> <!-- End stack-area -->

      <!-- Why Rent/Adopt Section -->
      <section class="why-rent-adopt">
          <h2>Why Rent or Adopt a Pet?</h2>
          <p class="intro-text">Renting or adopting a pet can bring joy, companionship, and many benefits to your life. Whether you're looking for a temporary furry friend or considering long-term adoption, here’s why it might be the perfect choice for you.</p>

          <div class="reasons-container">
              <div class="reason">
                  <i class="fas fa-home fa-3x mb-3"></i> <!-- Using Font Awesome Icon -->
                  <h3>🏡 Temporary Companionship</h3>
                  <p>Not everyone can commit to a pet for life. Renting is perfect for travelers, temporary residents, or short-term pet lovers.</p>
              </div>
              <div class="reason">
                   <i class="fas fa-clipboard-check fa-3x mb-3"></i> <!-- Using Font Awesome Icon -->
                  <h3>🐾 Trial Adoption</h3>
                  <p>Thinking of adopting? Renting first lets you experience pet ownership before making a lifelong commitment.</p>
              </div>
              <div class="reason">
                   <i class="fas fa-heartbeat fa-3x mb-3"></i> <!-- Using Font Awesome Icon -->
                  <h3>❤️ Therapeutic Benefits</h3>
                  <p>Pets reduce stress, provide emotional support, and encourage an active lifestyle. They’re great for mental well-being!</p>
              </div>
          </div>

          <!-- Adoption vs Rental Comparison Table -->
          <h2 class="table-heading">Adoption vs. Rental: Which One is Right for You? 🤔</h2>
          <div class="table-container">
              <table class="comparison-table">
                  <thead>
                      <tr>
                          <th>Feature</th>
                          <th>Renting a Pet</th>
                          <th>Adopting a Pet</th>
                      </tr>
                  </thead>
                  <tbody>
                      <tr>
                          <td>Duration</td>
                          <td>Short-term (Flexible)</td>
                          <td>Lifetime commitment</td>
                      </tr>
                      <tr>
                          <td>Cost</td>
                          <td>Lower upfront, potential recurring</td>
                          <td>Higher upfront (adoption fee), ongoing</td>
                      </tr>
                      <tr>
                          <td>Best for</td>
                          <td>Trial periods, temporary needs, specific events</td>
                          <td>Ready for full responsibility & long-term bond</td>
                      </tr>
                      <tr>
                          <td>Return Option</td>
                          <td>Expected part of the process</td>
                          <td>Difficult, often involves rehoming</td>
                      </tr>
                      <tr>
                          <td>Emotional Attachment</td>
                          <td>Can develop, but known end date</td>
                          <td>Strong lifelong connection expected</td>
                      </tr>
                      <tr>
                          <td>Legal Ownership</td>
                          <td>Remains with rental agency</td>
                          <td>Transferred to adopter</td>
                      </tr>
                      <tr>
                          <td>Responsibilities</td>
                          <td>Daily care during rental period</td>
                          <td>All aspects: health, training, finances</td>
                      </tr>
                  </tbody>
              </table>
          </div>
      </section> <!-- End why-rent-adopt -->

    </div> <!-- End center -->


    <!-- Footer -->
    <footer id="contact" class="footer">
      <div class="container">
        <div class="row gy-4">
            <div class="col-lg-4 col-md-6">
              <div class="d-flex align-items-center mb-3">
                <i class="fas fa-paw fa-2x me-2"></i>
                <h5 class="m-0 fs-4 fw-bold text-white">PawPeace</h5>
              </div>
              <p class="text-white-75">PawPeace brings joy to your life through our pet rental service. Experience the companionship of a furry friend without long-term commitments.</p>
              <div class="d-flex mt-4">
                <a href="#" class="social-icon me-2"><i class="fab fa-facebook-f"></i></a>
                <a href="#" class="social-icon me-2"><i class="fab fa-twitter"></i></a>
                <a href="#" class="social-icon me-2"><i class="fab fa-instagram"></i></a>
                <a href="#" class="social-icon"><i class="fab fa-linkedin-in"></i></a>
              </div>
            </div>
            <div class="col-lg-2 col-md-3 col-6">
              <h6 class="footer-heading text-white">Quick Links</h6>
              <ul class="list-unstyled">
                 <!-- UPDATE: Links point to PHP files -->
                <li><a href="./another-index.php" class="footer-link">Home</a></li>
                <li><a href="./another-random.php" class="footer-link">Rent-a-pet</a></li>
                <li><a href="./past-rentals.php" class="footer-link">Past rentals</a></li>
                <li><a href="./book-a-visit.php" class="footer-link">Book a visit</a></li>
              </ul>
            </div>
            <div class="col-lg-2 col-md-3 col-6">
              <h6 class="footer-heading text-white">Help & Info</h6>
              <ul class="list-unstyled">
                <li><a href="faq.php" class="footer-link">FAQs</a></li>
                <li><a href="terms.php" class="footer-link">Terms of Service</a></li>
                <li><a href="privacy.php" class="footer-link">Privacy Policy</a></li>
                <li><a href="support.php" class="footer-link">Support</a></li>
              </ul>
            </div>
            <div class="col-lg-4 col-md-6">
              <h6 class="footer-heading text-white">Contact Us</h6>
              <ul class="list-unstyled text-white-75">
                <li class="d-flex mb-2">
                  <i class="fas fa-map-marker-alt me-3 mt-1 flex-shrink-0"></i>
                  <span>123 Pet Lane, Animalville, PAW 56789</span>
                </li>
                <li class="d-flex mb-2">
                  <i class="fas fa-phone-alt me-3 mt-1 flex-shrink-0"></i>
                  <span>+91 1234567890</span>
                </li>
                <li class="d-flex mb-2">
                  <i class="fas fa-envelope me-3 mt-1 flex-shrink-0"></i>
                  <span>hello@pawpeace.com</span>
                </li>
              </ul>
              <form class="mt-3">
                 <label for="footerSubscribeEmail" class="visually-hidden">Email address</label>
                <div class="input-group">
                  <input type="email" class="form-control" id="footerSubscribeEmail" placeholder="Enter your email" style="border-radius: 20px 0 0 20px;">
                  <button class="btn btn-light" id="subscribe-button" type="button" style="border-radius: 0 20px 20px 0;">Subscribe</button>
                </div>
              </form>
            </div>
          </div>
        <div class="row footer-bottom mt-4">
          <div class="col-md-6 text-center text-md-start">
            <p class="mb-0 text-white-75">© <?php echo date("Y"); ?> PawPeace. All rights reserved</p> <!-- Dynamic Year -->
          </div>
          <div class="col-md-6 text-center text-md-end">
            <p class="mb-0 text-white-75">Made with <i class="fas fa-heart" style="color: #ff6b6b"></i></p>
          </div>
        </div>
      </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe"
    crossorigin="anonymous"></script>

    <!-- Card Animation Script -->
    <script>
      let cards = document.querySelectorAll(".card");
      let stackArea = document.querySelector(".stack-area");

      function rotateCards() {
        let angle = 0;
        let maxAngle = -40; // Limit the rotation angle
        let angleDecrement = cards.length > 0 ? Math.abs(maxAngle / (cards.length -1)) : 10; // Distribute angles

        cards.forEach((card, index) => {
          if (card.classList.contains("active")) {
            // Simplified active state transform, adjust angle/translation as needed
             card.style.transform = `translate(-50%, -120vh) rotate(-48deg)`;
          } else {
             // Calculate angle for inactive cards
             let currentAngle = Math.max(maxAngle, angle); // Don't go beyond maxAngle
             card.style.transform = `translate(-50%, -50%) rotate(${currentAngle}deg)`;
             angle = angle - angleDecrement; // Decrement for next card
          }
        });
      }

      // Initial rotation only if cards exist
      if (cards.length > 0 && stackArea) {
          rotateCards();

          window.addEventListener("scroll", () => {
            let proportion = stackArea.getBoundingClientRect().top / window.innerHeight;
            if (proportion <= 0.1) { // Start animation slightly before it hits top
              let n = cards.length;
              // Adjust index calculation for smoother transition
              let index = Math.floor((Math.abs(proportion) * n) / 1.5 ); // Tweak divisor for speed
              index = Math.min(n-1, Math.max(0, index)); // Clamp index within bounds

              for (let i = 0; i < n; i++) {
                if (i <= index) {
                  cards[i].classList.add("active");
                } else {
                  cards[i].classList.remove("active");
                }
              }
              rotateCards();
            } else {
                 // Reset cards if scrolled back up past the trigger point
                 cards.forEach(card => card.classList.remove("active"));
                 rotateCards();
            }
          });
      }


      // Code for responsiveness (no changes needed)
      function adjust() {
        // Check if elements exist before trying to manipulate them
        let stackAreaCheck = document.querySelector(".stack-area");
        let leftCheck = document.querySelector(".left");

        if (!stackAreaCheck || !leftCheck) return; // Exit if elements not found

        let windowWidth = window.innerWidth;

        // Detach only if it's currently a child of stackArea
        if (stackAreaCheck.contains(leftCheck)) {
            leftCheck.remove();
        } else if (document.body.contains(leftCheck) && leftCheck.parentNode !== stackAreaCheck) {
            // Check if it's somewhere else (likely already moved before stackArea)
             leftCheck.remove();
        }


        if (windowWidth < 992) { // Changed breakpoint to match Bootstrap's lg
          stackAreaCheck.insertAdjacentElement("beforebegin", leftCheck);
        } else {
          stackAreaCheck.insertAdjacentElement("afterbegin", leftCheck);
        }
      }
       // Run adjust on load and resize only if stackArea exists
       if (document.querySelector(".stack-area")) {
           adjust();
           window.addEventListener("resize", adjust);
       }

    </script>
  </body>
</html>