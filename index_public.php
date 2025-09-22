<?php
/**
 * Public Front Page - JEL Air Conditioning Services
 * Professional landing page for customers
 */

require_once 'includes/config.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JEL Air Conditioning Services - Professional AC Solutions</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .hero-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 80px 0;
            min-height: 70vh;
            display: flex;
            align-items: center;
        }
        .service-card {
            transition: all 0.3s ease;
            border: none;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            height: 100%;
        }
        .service-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 20px rgba(0, 0, 0, 0.15);
        }
        .cta-button {
            background: linear-gradient(45deg, #667eea, #764ba2);
            border: none;
            padding: 15px 30px;
            font-size: 1.1rem;
            border-radius: 50px;
            transition: all 0.3s ease;
            color: white;
        }
        .cta-button:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
            color: white;
        }
        .stats-section {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            padding: 60px 0;
        }
        .stat-number {
            font-size: 3rem;
            font-weight: bold;
            color: #667eea;
        }
        .feature-icon {
            font-size: 3rem;
            color: #667eea;
            margin-bottom: 1rem;
        }
        .navbar-brand {
            font-size: 1.5rem;
            font-weight: bold;
        }
        .hero-image {
            max-height: 300px;
            width: 100%;
            object-fit: contain;
        }
        .service-image {
            max-height: 120px;
            width: 100%;
            object-fit: contain;
        }
        .section-padding {
            padding: 80px 0;
        }
        .text-gradient {
            background: linear-gradient(135deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm fixed-top">
        <div class="container">
            <a class="navbar-brand text-primary" href="#">
                <img src="assets/images/logo.svg" alt="JEL Air Conditioning" height="40" class="me-2">
                JEL Air Conditioning
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#services">Services</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#about">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#contact">Contact</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="customer_portal/login.php">Customer Portal</a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-primary ms-2 shadow-sm" href="book_service.php" style="border-radius: 8px; padding: 8px 20px; font-weight: 500;">Book Service</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section text-center">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <!-- Hero Image -->
                    <div class="mb-4">
                        <img src="assets/images/hero/air-conditioning-hero.svg" alt="Professional Air Conditioning Services" class="hero-image">
                    </div>
                    <h1 class="display-4 fw-bold mb-4">Professional Air Conditioning Services</h1>
                    <p class="lead mb-4">Expert installation, repair, and maintenance services for your home and business. Stay cool with JEL Air Conditioning!</p>
                    <div class="d-flex justify-content-center gap-3 flex-wrap">
                        <a href="book_service.php" class="btn btn-light btn-lg cta-button">
                            <i class="fas fa-calendar-plus me-2"></i>Book Service Now
                        </a>
                        <a href="customer_portal/login.php" class="btn btn-outline-light btn-lg">
                            <i class="fas fa-user me-2"></i>Customer Portal
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section id="services" class="section-padding bg-light">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center mb-5">
                    <h2 class="display-5 fw-bold text-gradient">Our Services</h2>
                    <p class="lead text-muted">Comprehensive air conditioning solutions for every need</p>
                </div>
            </div>
            <div class="row g-4 justify-content-center">
                <div class="col-lg-4 col-md-6">
                    <div class="card service-card text-center p-4">
                        <div class="mb-3">
                            <img src="assets/images/services/ac-installation.svg" alt="AC Installation" class="service-image">
                        </div>
                        <h4 class="text-primary">AC Installation</h4>
                        <p class="text-muted">Professional installation of new air conditioning units with warranty coverage.</p>
                        <div class="mt-auto">
                            <span class="badge bg-primary fs-6">From ₱2,500</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="card service-card text-center p-4">
                        <div class="mb-3">
                            <img src="assets/images/services/ac-cleaning.svg" alt="AC Cleaning" class="service-image">
                        </div>
                        <h4 class="text-primary">AC Cleaning</h4>
                        <p class="text-muted">Thorough cleaning and maintenance to keep your AC running efficiently.</p>
                        <div class="mt-auto">
                            <span class="badge bg-success fs-6">From ₱800</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="card service-card text-center p-4">
                        <div class="mb-3">
                            <img src="assets/images/services/ac-repair.svg" alt="AC Repair" class="service-image">
                        </div>
                        <h4 class="text-primary">AC Repair</h4>
                        <p class="text-muted">Expert diagnosis and repair services for all AC problems.</p>
                        <div class="mt-auto">
                            <span class="badge bg-warning fs-6">From ₱1,500</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats-section">
        <div class="container">
            <div class="row text-center">
                <div class="col-md-3 mb-4">
                    <div class="stat-number">500+</div>
                    <h5>Happy Customers</h5>
                    <p class="text-muted">Satisfied clients across the region</p>
                </div>
                <div class="col-md-3 mb-4">
                    <div class="stat-number">1000+</div>
                    <h5>Services Completed</h5>
                    <p class="text-muted">Successful installations and repairs</p>
                </div>
                <div class="col-md-3 mb-4">
                    <div class="stat-number">5+</div>
                    <h5>Years Experience</h5>
                    <p class="text-muted">Professional air conditioning expertise</p>
                </div>
                <div class="col-md-3 mb-4">
                    <div class="stat-number">24/7</div>
                    <h5>Emergency Service</h5>
                    <p class="text-muted">Round-the-clock support available</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="about" class="section-padding">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center mb-5">
                    <h2 class="display-5 fw-bold text-gradient">Why Choose JEL Air Conditioning?</h2>
                    <p class="lead text-muted">Professional service you can trust</p>
                </div>
            </div>
            <div class="row g-4">
                <div class="col-lg-4 col-md-6">
                    <div class="text-center">
                        <div class="feature-icon">
                            <i class="fas fa-tools"></i>
                        </div>
                        <h4>Expert Technicians</h4>
                        <p class="text-muted">Certified professionals with years of experience in air conditioning systems.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="text-center">
                        <div class="feature-icon">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <h4>Warranty Coverage</h4>
                        <p class="text-muted">All our services come with comprehensive warranty protection.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="text-center">
                        <div class="feature-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <h4>24/7 Emergency</h4>
                        <p class="text-muted">Round-the-clock emergency service for urgent AC problems.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="text-center">
                        <div class="feature-icon">
                            <i class="fas fa-dollar-sign"></i>
                        </div>
                        <h4>Competitive Pricing</h4>
                        <p class="text-muted">Fair and transparent pricing with no hidden costs.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="text-center">
                        <div class="feature-icon">
                            <i class="fas fa-star"></i>
                        </div>
                        <h4>Quality Service</h4>
                        <p class="text-muted">Commitment to excellence in every service we provide.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="text-center">
                        <div class="feature-icon">
                            <i class="fas fa-phone"></i>
                        </div>
                        <h4>Easy Booking</h4>
                        <p class="text-muted">Simple online booking system for your convenience.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="section-padding" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
        <div class="container">
            <div class="row text-center">
                <div class="col-lg-8 mx-auto">
                    <h2 class="display-5 fw-bold mb-4">Ready to Stay Cool?</h2>
                    <p class="lead mb-4">Book your air conditioning service today and experience the difference professional service makes.</p>
                    <div class="d-flex justify-content-center gap-3 flex-wrap">
                        <a href="book_service.php" class="btn btn-light btn-lg cta-button">
                            <i class="fas fa-calendar-plus me-2"></i>Book Service Now
                        </a>
                        <a href="customer_portal/login.php" class="btn btn-outline-light btn-lg">
                            <i class="fas fa-user me-2"></i>Customer Portal
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="section-padding bg-light">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center mb-5">
                    <h2 class="display-5 fw-bold text-gradient">Contact Us</h2>
                    <p class="lead text-muted">Get in touch for professional air conditioning services</p>
                </div>
            </div>
            <div class="row g-4">
                <div class="col-lg-4">
                    <div class="text-center">
                        <div class="feature-icon">
                            <i class="fas fa-phone"></i>
                        </div>
                        <h4>Phone</h4>
                        <p class="text-muted">+63 123 456 7890</p>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="text-center">
                        <div class="feature-icon">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <h4>Email</h4>
                        <p class="text-muted">info@jelaircon.com</p>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="text-center">
                        <div class="feature-icon">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <h4>Address</h4>
                        <p class="text-muted">123 Main Street, City, Philippines</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-dark text-white py-4">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5>JEL Air Conditioning Services</h5>
                    <p class="text-muted">Professional air conditioning solutions for your home and business.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="text-muted mb-0">&copy; 2024 JEL Air Conditioning. All rights reserved.</p>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>