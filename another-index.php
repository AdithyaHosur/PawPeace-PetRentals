<?php
// Start session - good practice if you add user login features later
session_start();

// Include database connection - might be needed for other elements eventually
require_once 'db_connect.php'; // Make sure this path is correct

// No specific PHP logic needed to DISPLAY content on this page yet.
// Database connection might be used later for things like checking login status for the navbar, etc.

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>PawPeace - Rent Your Perfect Pet Companion</title>
  <link rel="icon" href="./images/file.png" type="image/icon type">
  
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link
    href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@200;300;400;500;600;700;800&family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap"
    rel="stylesheet" />
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

    body {
      font-family: 'Poppins', sans-serif;
      color: var(--text-dark);
      overflow-x: hidden;
      height: fit-content;
      margin: 0;
      padding: 0;
      padding-top: 76px;
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
      color: var(--primary-color) !important;
    }

    .nav-link:after {
      content: '';
      position: absolute;
      width: 0;
      height: 2px;
      background: var(--primary-color);
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

    /* Title Section */
    #title {
      padding: 60px 0;
      border-radius: 0 0 20px 20px;
      margin-bottom: 30px;
      height: 45rem;
    }

    #title h1 {
      font-weight: 800;
      font-size: 3.5rem;
      margin-bottom: 20px;
    }

    #title h2 {
      font-weight: 400;
      opacity: 0.9;
    }

    .btn-light {
      background-color: var(--white);
      border: none;
      border-radius: 30px;
      padding: 12px 30px;
      font-weight: 600;
      transition: all 0.3s ease;
    }

    .btn-light:hover {
      transform: translateY(-3px);
      background-color: #2d3436;
      color: white;
      box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    }

    .btn-outline-light {
      border-radius: 30px;
      padding: 12px 30px;
      font-weight: 600;
      transition: all 0.3s ease;
    }

    .btn-outline-light:hover {
      transform: translateY(-3px);
      background-color: white;
      box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    }

    /* Features Section */
    #features {
      padding: 50px 0;
    }

    .icon-square {
      width: 60px;
      height: 60px;
      border-radius: 12px;
      display: flex;
      align-items: center;
      justify-content: center;
      color: var(--primary-color);
      background-color: rgba(74, 144, 226, 0.1);
      margin-right: 20px;
      transition: all 0.3s ease;
    }

    .feature-card {
      border-radius: 16px;
      padding: 30px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
      transition: all 0.3s ease;
    }

    .feature-card:hover {
      transform: translateY(-10px);
      box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
      cursor: pointer;
    }

    .feature-card:hover .icon-square {
      background-color: var(--primary-color);
      color: white;
    }

    /* Rent Button */
    .rent-div {
      text-align: center;
      margin: 50px 0;
    }

    .rent-button {
      padding: 15px 40px;
      font-size: 1.2rem;
      font-weight: 600;
      border: none;
      border-radius: 50px;
      color: white;
      cursor: pointer;
      box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
      transition: all 0.3s ease;
    }

    .rent-button:hover {
      transform: scale(1.05);
      box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
    }

    /* Testimonial Section */
    #testimonial {
      padding: 30px 0;
    }

    .testimonial-section {
      border-radius: 20px;
      padding: 60px 0;
      background-color: var(--light-bg);
    }

    .profile-img {
      width: 120px;
      height: 120px;
      object-fit: cover;
      border-radius: 50%;
      border: 5px solid white;
      box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    }

    .partner-logos {
      padding: 30px 0;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .partner-logo {
      filter: grayscale(100%);
      opacity: 0.7;
      transition: all 0.3s ease;
      max-height: 30px;
    }

    .partner-logo:hover {
      filter: grayscale(0%);
      opacity: 1;
    }

    /* Pricing Section */
    #pricing {
      padding: 50px 0;
    }

    .pricing-card {
      border-radius: 16px;
      overflow: hidden;
      transition: all 0.3s ease;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
      border: none;
    }

    .pricing-card:hover {
      transform: translateY(-10px);
      box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
    }

    .card-header {
      padding: 20px;
      font-weight: 600;
    }

    .pricing-card-title {
      font-size: 2.5rem;
      font-weight: 700;
      margin: 20px 0;
    }

    .pricing-btn {
      border-radius: 30px;
      padding: 12px 0;
      font-weight: 600;
      margin-top: 20px;
      transition: all 0.3s ease;
    }

    .pricing-btn:hover {
      transform: translateY(-3px);
      box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    }

    .featured-card {
      transform: scale(1.05);
      z-index: 1.0;
    }

    /* Footer */
    .footer {
      background: linear-gradient(to right, var(--primary-color), var(--secondary-color));
      color: white;
      border-radius: 20px 20px 0 0;
      padding: 60px 0 30px;
      margin-top: 50px;
    }

    .footer-heading {
      font-size: 1.2rem;
      font-weight: 600;
      margin-bottom: 20px;
    }

    .footer-link {
      color: rgba(255, 255, 255, 0.8);
      text-decoration: none;
      margin-bottom: 10px;
      display: block;
      transition: all 0.3s ease;
    }

    .footer-link:hover {
      color: white;
      transform: translateX(5px);
    }

    .social-icon {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      background-color: rgba(255, 255, 255, 0.1);
      display: flex;
      align-items: center;
      justify-content: center;
      margin-right: 10px;
      transition: all 0.3s ease;
    }

    .social-icon:hover {
      background-color: rgba(255, 255, 255, 0.2);
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

    #subscribe-button:hover{
      transform: translateY(0);
    }
    
    ::selection {
      background-color: #7dd8ea; /* Light red background */
      color: #fff; /* Black text */
      text-shadow: 1px 1px 2px black;
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
            <li><a href="./another-random.php" class="nav-link px-3 link-dark">Rent-a-pet</a></li> <!-- Assuming random.html becomes this -->
            <li><a href="./past-rentals.php" class="nav-link px-3 link-dark">Past rentals</a></li> <!-- Assuming past-rentals.html becomes this -->
            <li><a href="./book-a-visit.php" class="nav-link px-3 link-dark">Book a visit</a></li> <!-- Assuming book-a-visit.html becomes this -->
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
             <!-- The user dropdown structure is kept as is -->
             <!-- UPDATE: Links inside dropdown point to potential PHP files -->
            <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
              <img src="https://api.dicebear.com/6.x/avataaars/svg?seed=Felix" alt="User Avatar" width="32" height="32" class="rounded-circle me-2">
              <span class="d-none d-sm-inline">My Account</span>
            </a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownUser1">
              <li><a class="dropdown-item" href="profile.php"><i class="fas fa-user-circle me-2"></i>Profile</a></li> <!-- Link to profile.php -->
              <li><a class="dropdown-item" href="favorites.php"><i class="fas fa-heart me-2"></i>Favorites</a></li> <!-- Link to favorites.php -->
              <li><a class="dropdown-item" href="past-rentals.php"><i class="fas fa-history me-2"></i>Rental History</a></li> <!-- Link to past-rentals.php -->
              <li><a class="dropdown-item" href="settings.php"><i class="fas fa-cog me-2"></i>Settings</a></li> <!-- Link to settings.php -->
              <li><hr class="dropdown-divider"></li>
              <li><a class="dropdown-item" href="logout.php"><i class="fas fa-sign-out-alt me-2"></i>Sign out</a></li> <!-- Link to logout.php -->
            </ul>
          </div>
        </div>
      </div>
    </div>
  </header>

  <!-- Title Section -->
  <section id="title" class="gradient-background">
    <div class="container">
      <div class="row align-items-center">
        <div class="col-lg-6 animate-fade-in">
          <h1 class="display-4 fw-bold text-white">Find Your Perfect Pet Companion</h1>
          <h2 class="mb-4">Experience the joy of pet companionship without the long-term commitment</h2>
          <div class="d-grid gap-3 d-md-flex justify-content-md-start">
            <button type="button" class="btn btn-light btn-lg download-btn">
              <i class="fab fa-apple me-2"></i>Download App
            </button>
            <button type="button" class="btn btn-outline-light btn-lg android-button">
              <i class="fab fa-google-play me-2"></i>Get on Android
            </button>
          </div>
        </div>
        <div class="col-lg-6 mt-5 mt-lg-0 text-center animate-fade-in delay-1 d-flex align-items-end justify-content-center">
          <img src="./images/iphone.png" class="img-fluid" alt="PawPeace App" style="height: 41rem; margin-bottom: -50px;">
        </div>
      </div>
    </div>
  </section>

  <!-- Features Section -->
  <section id="features">
    <div class="container">
      <div class="row text-center mb-5">
        <div class="col-lg-8 mx-auto">
          <h2 class="fs-1 fw-bold mb-3">Why Choose PawPeace?</h2>
          <p class="lead text-muted">Experience the joy of pet companionship without the long-term commitment</p>
        </div>
      </div>
      <div class="row g-4">
        <div class="col-md-4 animate-fade-in">
          <div class="feature-card h-100">
            <div class="d-flex align-items-start">
              <div class="icon-square d-inline-flex align-items-center justify-content-center fs-4 flex-shrink-0">
                <i class="fas fa-calendar-alt"></i>
              </div>
              <div>
                <h3 class="fs-5 fw-bold">Flexible Rental Options</h3>
                <p class="text-muted">Choose from hourly visits, daily care, or weekly companions to fit your lifestyle perfectly.</p>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-4 animate-fade-in delay-1">
          <div class="feature-card h-100">
            <div class="d-flex align-items-start">
              <div class="icon-square d-inline-flex align-items-center justify-content-center fs-4 flex-shrink-0">
                <i class="fas fa-medal"></i>
              </div>
              <div>
                <h3 class="fs-5 fw-bold">Premium Pet Care</h3>
                <p class="text-muted">All our animals receive top-tier healthcare, grooming, and training from certified professionals.</p>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-4 animate-fade-in delay-2">
          <div class="feature-card h-100">
            <div class="d-flex align-items-start">
              <div class="icon-square d-inline-flex align-items-center justify-content-center fs-4 flex-shrink-0">
                <i class="fas fa-coffee"></i>
              </div>
              <div>
                <h3 class="fs-5 fw-bold">Petting Café Experience</h3>
                <p class="text-muted">Visit our cozy café where you can relax with a beverage while surrounded by friendly animals.</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Rent-A-Pet Button -->
  <div class="rent-div animate-fade-in">
    <a href="./another-random.php">
      <button class="gradient-background rent-button">
        <i class="fas fa-paw me-2"></i>Rent-A-Pet Now
      </button>
    </a>
  </div>

  <!-- Testimonial Section -->
  <section id="testimonial">
    <div class="container">
      <div class="testimonial-section animate-fade-in">
        <div class="text-center">
          <i class="fas fa-quote-left fa-2x mb-3" style="color: var(--primary-color);"></i>
          <h2 class="fs-3 fw-light mb-4">
            "I bring endless energy for walks and playtime! I'll be your furry fitness buddy, keeping you active and smiling. Paws crossed, we can have some pawsome adventures together!"
          </h2>
          <img class="profile-img" src="./images/dog-img.jpg" alt="Golden Retriever">
          <p class="fs-5 fw-bold mt-3 mb-1">Pebbles</p>
          <p class="text-muted">Golden Retriever, 3 years old</p>
          <div class="d-flex justify-content-center mt-4">
            <span class="mx-1"><i class="fas fa-star" style="color: var(--accent-color);"></i></span>
            <span class="mx-1"><i class="fas fa-star" style="color: var(--accent-color);"></i></span>
            <span class="mx-1"><i class="fas fa-star" style="color: var(--accent-color);"></i></span>
            <span class="mx-1"><i class="fas fa-star" style="color: var(--accent-color);"></i></span>
            <span class="mx-1"><i class="fas fa-star" style="color: var(--accent-color);"></i></span>
          </div>
        </div>
      </div>
      
      <!-- Alternative solution with inline styles for guaranteed spacing -->
      <div class="partner-logos mt-5 animate-fade-in">
        <div style="display: flex; justify-content: center; align-items: center; flex-wrap: wrap; gap: 60px; width: 100%; padding: 0 20px;">
          <div style="flex: 0 0 auto; max-width: 150px; text-align: center;">
            <img src="./images/techcrunch.png" alt="TechCrunch" class="partner-logo" style="max-width: 100%;">
          </div>
          <div style="flex: 0 0 auto; max-width: 150px; text-align: center;">
            <img src="./images/mashable.png" alt="Mashable" class="partner-logo" style="max-width: 100%;">
          </div>
          <div style="flex: 0 0 auto; max-width: 150px; text-align: center;">
            <img src="./images/bizinsider.png" alt="Business Insider" class="partner-logo" style="max-width: 100%;">
          </div>
          <div style="flex: 0 0 auto; max-width: 150px; text-align: center;">
            <img src="./images/tnw.png" alt="TNW" class="partner-logo" style="max-width: 100%;">
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Pricing Section -->
  <section id="pricing">
    <div class="container">
      <div class="text-center mb-5 animate-fade-in">
        <h2 class="display-5 fw-bold">A Plan for Every Pet Lover</h2>
        <p class="fs-5 text-muted mx-auto" style="max-width: 700px;">
          Simple and affordable pet rental plans tailored to your preferences and schedule.
        </p>
      </div>

      <div class="row">
        <div class="col-md-4 mb-4 animate-fade-in">
          <div class="pricing-card h-100">
            <div class="card-header text-center bg-light">
              <h4 class="my-0 fw-normal">Fish, Birds & Rodents</h4>
            </div>
            <div class="card-body text-center">
              <h2 class="pricing-card-title">₹50<small class="text-muted fw-light">/day</small></h2>
              <ul class="list-unstyled mt-3 mb-4">
                <li class="py-2"><i class="fas fa-fish me-2 text-primary"></i>Finny the Fish</li>
                <li class="py-2"><i class="fas fa-dove me-2 text-primary"></i>Chirpy the Bird</li>
                <li class="py-2"><i class="fas fa-carrot me-2 text-primary"></i>Hammy the Hamster</li>
                <li class="py-2"><i class="fas fa-check me-2 text-success"></i>Basic care kit included</li>
              </ul>
              <a href="./other_pets.php">
                <button type="button" class="btn btn-outline-primary pricing-btn w-100">
                  Book Now
                </button>
              </a>
            </div>
          </div>
        </div>

        <div class="col-md-4 mb-4 animate-fade-in delay-1">
          <div class="pricing-card h-100">
            <div class="card-header text-center bg-light">
              <h4 class="my-0 fw-normal">Reptiles & Turtles</h4>
            </div>
            <div class="card-body text-center">
              <h2 class="pricing-card-title">₹150<small class="text-muted fw-light">/day</small></h2>
              <ul class="list-unstyled mt-3 mb-4">
                <li class="py-2"><i class="fas fa-dragon me-2 text-primary"></i>Scales the Snake</li>
                <li class="py-2"><i class="fas fa-leaf me-2 text-primary"></i>Chammy the Chameleon</li>
                <li class="py-2"><i class="fas fa-water me-2 text-primary"></i>Shelly the Turtle</li>
                <li class="py-2"><i class="fas fa-check me-2 text-success"></i>Habitat setup included</li>
              </ul>
              <a href="./reptiles.php">
                <button type="button" class="btn btn-primary pricing-btn w-100">
                  Book Now
                </button>
              </a>
            </div>
          </div>
        </div>

        <div class="col-md-4 mb-4 animate-fade-in delay-2">
          <div class="pricing-card featured-card h-100">
            <div class="card-header text-center text-white" style="background: var(--primary-color);">
              <h4 class="my-0 fw-normal">Dogs & Cats</h4>
              <span class="badge bg-warning text-dark mt-2">MOST POPULAR</span>
            </div>
            <div class="card-body text-center">
              <h2 class="pricing-card-title">₹300<small class="text-muted fw-light">/day</small></h2>
              <ul class="list-unstyled mt-3 mb-4">
                <li class="py-2"><i class="fas fa-dog me-2 text-primary"></i>Woofer the Dog</li>
                <li class="py-2"><i class="fas fa-cat me-2 text-primary"></i>Mittens the Cat</li>
                <li class="py-2"><i class="fas fa-home me-2 text-primary"></i>Home delivery available</li>
                <li class="py-2"><i class="fas fa-check me-2 text-success"></i>Pet essentials kit included</li>
              </ul>
              <a href="dogs_cats.php">
                <button type="button" class="btn btn-primary pricing-btn w-100">
                  Book Now
                </button>
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Footer -->
  <footer id="contact" class="footer gradient-background">
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
            <li><a href="./another-index.php" class="footer-link">Home</a></li>
            <li><a href="./another-random.php" class="footer-link">Rent-a-pet</a></li>
            <li><a href="./past-rentals.php" class="footer-link">Past rentals</a></li>
            <li><a href="./book-a-visit.php" class="footer-link">Book a visit</a></li>
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
          <p class="mb-0">© <?php echo date("Y"); ?> PawPeace. All rights reserved</p>
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