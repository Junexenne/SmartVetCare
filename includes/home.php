<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart Vet Care - Furry Friends Animal Clinic</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <style>
        /* Modern Hero Design */
        .hero {
            position: relative;
            background: url('assets/images/clinic-bg.png') no-repeat center center/cover;
            min-height: 88vh;
            display: flex;
            align-items: center;
            color: #ffffff;
            padding: 40px 20px 80px 20px;
        }
        .hero-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, rgba(30, 27, 75, 0.92) 0%, rgba(22, 59, 122, 0.85) 50%, rgba(22, 59, 122, 0.2) 100%);
            display: flex;
            align-items: center;
        }
        .hero-container-custom {
            position: relative;
            z-index: 2;
            max-width: 1200px;
            margin: 0 auto;
            width: 100%;
            display: flex;
            flex-direction: column;
            gap: 50px;
        }
        .hero-content-wrapper {
            max-width: 620px;
        }
        .hero-badge-tag {
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: #ffffff;
            opacity: 0.9;
            margin-bottom: 12px;
            font-weight: 600;
            display: block;
        }
        .hero-content-wrapper h1 {
            font-size: 52px;
            font-weight: 700;
            line-height: 1.15;
            margin-bottom: 20px;
            color: #ffffff;
        }
        .hero-content-wrapper p {
            font-size: 15px;
            line-height: 1.6;
            color: rgba(255, 255, 255, 0.9);
            margin-bottom: 32px;
        }
        .hero-buttons {
            display: flex;
            gap: 16px;
            align-items: center;
            flex-wrap: wrap;
        }
        .btn-primary-custom {
            background: #5142f5;
            color: #ffffff;
            padding: 14px 28px;
            border-radius: 30px;
            font-weight: 600;
            font-size: 14px;
            text-decoration: none;
            transition: all 0.3s ease;
            box-shadow: 0 4px 20px rgba(81, 66, 245, 0.4);
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        .btn-primary-custom:hover {
            background: #4032d9;
            transform: translateY(-2px);
            box-shadow: 0 6px 24px rgba(81, 66, 245, 0.5);
        }
        .btn-outline-custom {
            background: rgba(255, 255, 255, 0.08);
            color: #ffffff;
            border: 1.5px solid rgba(255, 255, 255, 0.5);
            padding: 14px 28px;
            border-radius: 30px;
            font-weight: 600;
            font-size: 14px;
            text-decoration: none;
            transition: all 0.3s ease;
            backdrop-filter: blur(4px);
        }
        .btn-outline-custom:hover {
            background: rgba(255, 255, 255, 0.2);
            border-color: #ffffff;
            transform: translateY(-2px);
        }

        /* Floating Stats Bar */
        .hero-stats-bar {
            background: #ffffff;
            border-radius: 20px;
            padding: 24px 36px;
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 24px;
            box-shadow: 0 12px 35px rgba(30, 27, 75, 0.12);
            color: #1e1b4b;
            border: 1px solid #edf2f7;
        }
        .stat-item {
            display: flex;
            align-items: center;
            gap: 16px;
        }
        .stat-icon {
            width: 50px;
            height: 50px;
            background: #f0eeff;
            color: #5142f5;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            flex-shrink: 0;
        }
        .stat-info h3 {
            font-size: 22px;
            font-weight: 700;
            margin: 0;
            color: #1e1b4b;
            line-height: 1.2;
        }
        .stat-info p {
            font-size: 12.5px;
            color: #64748b;
            margin: 2px 0 0 0;
            font-weight: 500;
        }

        /* About Us Banner Card */
        .about-section-custom {
            padding: 60px 20px;
            background: #f8fafc;
        }
        .about-card-banner {
            max-width: 1200px;
            margin: 0 auto;
            position: relative;
            background: url('assets/images/about-us.png') no-repeat center right / cover;
            border-radius: 28px;
            box-shadow: 0 20px 40px rgba(22, 59, 122, 0.15);
            overflow: hidden;
        }
        .about-banner-overlay {
            width: 100%;
            height: 100%;
            padding: 56px;
            box-sizing: border-box;
            background: linear-gradient(90deg, #163B7A 0%, rgba(23, 63, 129, 0.95) 55%, rgba(23, 63, 129, 0.2) 100%);
            display: flex;
            align-items: center;
        }
        .about-banner-content {
            max-width: 620px;
            z-index: 2;
        }
        .about-badge-tag {
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: #a5b4fc;
            margin-bottom: 12px;
            display: block;
        }
        .about-banner-content h2 {
            font-size: 38px;
            font-weight: 700;
            color: #ffffff;
            margin: 0 0 18px 0;
            line-height: 1.2;
        }
        .about-banner-content p {
            font-size: 14.5px;
            color: rgba(255, 255, 255, 0.9);
            line-height: 1.7;
            margin-bottom: 14px;
        }

        /* Why Choose Us Section */
        .why-choose-us-section {
            padding: 80px 20px;
            background: #ffffff;
        }
        .why-choose-container {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 50px;
        }
        .why-choose-content {
            flex: 1;
            max-width: 580px;
        }
        .why-choose-badge {
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: #5142f5;
            background: #f0eeff;
            padding: 6px 16px;
            border-radius: 20px;
            display: inline-block;
            margin-bottom: 12px;
        }
        .why-choose-content h2 {
            font-size: 36px;
            font-weight: 700;
            color: #1e1b4b;
            margin: 0 0 16px 0;
            line-height: 1.2;
        }
        .why-choose-content p.subtitle {
            font-size: 14.5px;
            color: #64748b;
            line-height: 1.6;
            margin-bottom: 32px;
        }
        .why-choose-features-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 24px;
        }
        .feature-box {
            display: flex;
            align-items: flex-start;
            gap: 14px;
        }
        .feature-icon-circle {
            width: 44px;
            height: 44px;
            background: #f0eeff;
            color: #5142f5;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            flex-shrink: 0;
        }
        .feature-text h4 {
            font-size: 16px;
            font-weight: 700;
            color: #1e1b4b;
            margin: 0 0 4px 0;
        }
        .feature-text p {
            font-size: 12.5px;
            color: #64748b;
            line-height: 1.5;
            margin: 0;
        }
        .why-choose-image {
            flex: 1;
            max-width: 520px;
            display: flex;
            justify-content: center;
            align-items: center;
            background: transparent !important;
            box-shadow: none !important;
            border: none !important;
        }
        .why-choose-image img {
            width: 100%;
            height: auto;
            max-height: 400px;
            object-fit: contain;
            border-radius: 0 !important;
            box-shadow: none !important;
            background: transparent !important;
            filter: drop-shadow(0 10px 20px rgba(0, 0, 0, 0.08));
        }

        /* Services Grid Layout */
        .services-section-custom {
            padding: 80px 20px;
            background: #f8fafc;
        }
        .services-container-custom {
            max-width: 1200px;
            margin: 0 auto;
        }
        .services-header-custom {
            margin-bottom: 40px;
        }
        .services-header-custom .badge {
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: #5142f5;
            background: #f0eeff;
            padding: 6px 16px;
            border-radius: 20px;
            display: inline-block;
            margin-bottom: 10px;
        }
        .services-header-custom h2 {
            font-size: 36px;
            font-weight: 700;
            color: #1e1b4b;
            margin: 0 0 10px 0;
        }
        .services-header-custom p {
            font-size: 14.5px;
            color: #64748b;
            margin: 0;
            max-width: 600px;
            line-height: 1.6;
        }
        .services-grid-new {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 24px;
        }
        .service-card-new {
            background: #ffffff;
            border: 1px solid #edf2f7;
            border-radius: 20px;
            padding: 24px;
            display: flex;
            align-items: center;
            gap: 20px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0,0,0,0.02);
        }
        .service-card-new:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 30px rgba(81, 66, 245, 0.08);
            border-color: #cbd5e1;
        }
        .service-info-new {
            flex: 1;
        }
        .service-icon-circle {
            width: 46px;
            height: 46px;
            background: #5142f5;
            color: #ffffff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            margin-bottom: 14px;
            box-shadow: 0 4px 12px rgba(81, 66, 245, 0.25);
        }
        .service-info-new h3 {
            font-size: 20px;
            font-weight: 700;
            color: #1e1b4b;
            margin: 0 0 8px 0;
        }
        .service-info-new p {
            font-size: 13px;
            color: #64748b;
            line-height: 1.5;
            margin: 0;
        }
        .service-image-new {
            width: 140px;
            height: 140px;
            flex-shrink: 0;
            border-radius: 16px;
            overflow: hidden;
        }
        .service-image-new img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* CLIENT TESTIMONIALS SECTION - ONE LAYER / SLIDER FIX */
        .testimonial-section-new {
            padding: 80px 20px;
            background: #ffffff;
            position: relative;
        }
        .testimonial-container-new {
            max-width: 1200px;
            margin: 0 auto;
        }
        .testimonial-top-row {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-bottom: 40px;
        }
        .testimonial-header-new .badge {
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: #5142f5;
            background: #f0eeff;
            padding: 6px 16px;
            border-radius: 20px;
            display: inline-block;
            margin-bottom: 10px;
        }
        .testimonial-header-new h2 {
            font-size: 36px;
            font-weight: 700;
            color: #1e1b4b;
            margin: 0 0 8px 0;
        }
        .testimonial-header-new p {
            font-size: 14.5px;
            color: #64748b;
            margin: 0;
        }

        .testimonial-nav-btns {
            display: flex;
            gap: 12px;
        }
        .testimonial-section-new button.prev-btn,
        .testimonial-section-new button.next-btn {
            position: static !important;
            width: 44px !important;
            height: 44px !important;
            border-radius: 50% !important;
            border: 1.5px solid #e2e8f0 !important;
            background: #ffffff !important;
            color: #1e1b4b !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            cursor: pointer !important;
            transition: all 0.3s ease !important;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04) !important;
            font-size: 15px !important;
            padding: 0 !important;
            margin: 0 !important;
        }
        .testimonial-section-new button.prev-btn:hover,
        .testimonial-section-new button.next-btn:hover {
            background: #5142f5 !important;
            color: #ffffff !important;
            border-color: #5142f5 !important;
            transform: translateY(-2px) !important;
        }

        /* ONE LAYER CAROUSEL WRAPPER */
        .testimonial-slider-wrapper {
            overflow: hidden;
            width: 100%;
            padding: 10px 0 20px 0;
        }
        .testimonial-grid-new {
            display: flex !important; /* Ginawang Flex para One Layer lamang */
            gap: 24px;
            transition: transform 0.4s ease-in-out;
            will-change: transform;
        }
        .testimonial-card-new {
            background: #ffffff;
            border: 1px solid #edf2f7;
            border-radius: 20px;
            padding: 28px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.03);
            display: flex;
            flex-direction: column;
            gap: 16px;
            transition: all 0.3s ease;
            width: calc((100% - 48px) / 3); /* Tatlong cards lang ang lalabas sa screen */
            flex-shrink: 0;
            box-sizing: border-box;
        }
        .testimonial-card-new:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 30px rgba(81, 66, 245, 0.08);
            border-color: #cbd5e1;
        }
        .testimonial-user-profile {
            display: flex;
            align-items: center;
            gap: 14px;
        }
        .testimonial-avatar {
            width: 52px;
            height: 52px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #f0eeff;
        }
        .testimonial-user-info h4 {
            font-size: 16px;
            font-weight: 700;
            color: #1e1b4b;
            margin: 0 0 2px 0;
        }
        .testimonial-user-info p {
            font-size: 12.5px;
            color: #64748b;
            margin: 0;
        }
        .testimonial-stars {
            color: #f59e0b;
            font-size: 14px;
            display: flex;
            gap: 3px;
        }
        .testimonial-text-content {
            font-size: 13.5px;
            color: #475569;
            line-height: 1.6;
            margin: 0;
            font-style: normal;
        }

        /* Responsive Settings */
        @media (max-width: 992px) {
            .hero-content-wrapper h1 { font-size: 38px; }
            .hero-stats-bar { grid-template-columns: repeat(2, 1fr); padding: 20px 24px; }
            .hero-overlay { background: linear-gradient(180deg, rgba(30, 27, 75, 0.92) 0%, rgba(22, 59, 122, 0.88) 100%); }
            .about-card-banner { background-position: center center; }
            .about-banner-overlay { padding: 40px 28px; background: linear-gradient(180deg, rgba(22, 59, 122, 0.96) 0%, rgba(23, 63, 129, 0.88) 100%); }
            .about-banner-content { max-width: 100%; }
            .about-banner-content h2 { font-size: 30px; }
            .why-choose-container { flex-direction: column; text-align: center; }
            .why-choose-content { max-width: 100%; }
            .feature-box { text-align: left; }
            .why-choose-image { max-width: 100%; }
            .services-grid-new { grid-template-columns: 1fr; }
            
            .testimonial-card-new {
                width: calc((100% - 24px) / 2); /* Dalawa kapag tablet */
            }
        }
        @media (max-width: 576px) {
            .hero { padding: 30px 16px 60px 16px; }
            .hero-content-wrapper h1 { font-size: 30px; }
            .hero-stats-bar { grid-template-columns: 1fr; gap: 16px; }
            .stat-icon { width: 42px; height: 42px; font-size: 16px; }
            .stat-info h3 { font-size: 18px; }
            .about-banner-overlay { padding: 32px 20px; }
            .about-banner-content h2 { font-size: 26px; }
            .about-banner-content p { font-size: 13.5px; }
            .why-choose-features-grid { grid-template-columns: 1fr; }
            .service-card-new { flex-direction: column-reverse; align-items: flex-start; }
            .service-image-new { width: 100%; height: 160px; }
            .testimonial-top-row { flex-direction: column; align-items: flex-start; gap: 16px; }
            
            .testimonial-card-new {
                width: 100%; /* Isa kapag mobile */
            }
        }
    </style>
</head>
<body>

<!-- =====================================
    HERO SECTION
===================================== -->
<section class="hero" id="home">
    <div class="hero-overlay">
        <div class="hero-container-custom container">
            <div class="hero-content-wrapper">
                <span class="hero-badge-tag">Compassionate Care For</span>
                <h1>Healthy Pets, Happier Lives.</h1>
                <p>
                    We provide expert veterinary care, modern facilities, and a loving environment for your pets because they're family.
                </p>
                <div class="hero-buttons">
                    <a href="auth/login-user.php" class="btn-primary-custom">
                        Book an Appointment <i class="fa-solid fa-arrow-right"></i>
                    </a>
                    <a href="#services" class="btn-outline-custom">Our Services</a>
                </div>
            </div>

            <!-- Stats Component -->
            <div class="hero-stats-bar">
                <div class="stat-item">
                    <div class="stat-icon"><i class="fa-solid fa-user-doctor"></i></div>
                    <div class="stat-info">
                        <h3>10+</h3>
                        <p>Years of Experience</p>
                    </div>
                </div>
                <div class="stat-item">
                    <div class="stat-icon"><i class="fa-solid fa-paw"></i></div>
                    <div class="stat-info">
                        <h3>10K+</h3>
                        <p>Happy Pets</p>
                    </div>
                </div>
                <div class="stat-item">
                    <div class="stat-icon"><i class="fa-solid fa-face-smile"></i></div>
                    <div class="stat-info">
                        <h3>98%</h3>
                        <p>Client Satisfaction</p>
                    </div>
                </div>
                <div class="stat-item">
                    <div class="stat-icon"><i class="fa-solid fa-clock"></i></div>
                    <div class="stat-info">
                        <h3>Quality Care</h3>
                        <p>Trusted Pet Healthcare</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- =====================================
    ABOUT US BANNER SECTION
===================================== -->
<section class="about-section-custom" id="about">
    <div class="about-card-banner">
        <div class="about-banner-overlay">
            <div class="about-banner-content">
                <span class="about-badge-tag">About Us</span>
                <h2>About Furry Friends Animal Clinic</h2>
                <p>
                    Furry Friends Animal Clinic is committed to providing quality, compassionate, and affordable veterinary care. Our experienced veterinarians and dedicated staff ensure every pet receives the attention, treatment, and love they deserve.
                </p>
                <p>
                    We believe healthy pets create happier families. From routine check-ups to advanced medical services, we are here every step of the way.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- =====================================
    WHY CHOOSE US SECTION
===================================== -->
<section class="why-choose-us-section" id="why-us">
    <div class="why-choose-container">
        <div class="why-choose-content">
            <span class="why-choose-badge">The Veterinary Clinic Difference</span>
            <h2>Why Choose Us</h2>
            <p class="subtitle">
                We go beyond basic care — providing personalized, compassionate, and advanced veterinary services for your beloved pets.
            </p>
            
            <div class="why-choose-features-grid">
                <!-- Feature 1 -->
                <div class="feature-box">
                    <div class="feature-icon-circle"><i class="fa-solid fa-user-nurse"></i></div>
                    <div class="feature-text">
                        <h4>Experienced Team</h4>
                        <p>Skilled and compassionate veterinarians.</p>
                    </div>
                </div>
                <!-- Feature 2 -->
                <div class="feature-box">
                    <div class="feature-icon-circle"><i class="fa-solid fa-heart"></i></div>
                    <div class="feature-text">
                        <h4>Gentle & Loving Care</h4>
                        <p>Stress-free and friendly handling for pets.</p>
                    </div>
                </div>
                
                <!-- Feature 3 -->
                <div class="feature-box">
                  <div class="feature-icon-circle"><i class="fa-solid fa-pump-soap"></i></div>
                 <div class="feature-text">
                    <h4>Clean & Safe Clinic</h4>
                    <p>Well-maintained and sanitized environment.</p>
                </div>
                </div>
                <!-- Feature 4 -->
                     <div class="feature-box">
                         <div class="feature-icon-circle"><i class="fa-solid fa-hand-holding-dollar"></i></div>
                         <div class="feature-text">
                         <h4>Affordable Care</h4>
                         <p>Quality veterinary services at reasonable rates.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Transparent Image Right Side -->
        <div class="why-choose-image">
            <img src="assets/images/choose-us.png" alt="Why Choose Furry Friends Animal Clinic">
        </div>
    </div>
</section>

<!-- =====================================
    SERVICES OFFER (GRID LAYOUT STYLE)
===================================== -->
<section class="services-section-custom" id="services">
    <div class="services-container-custom">
        <div class="services-header-custom">
            <span class="badge">Comprehensive Care</span>
            <h2>What We Can Offer</h2>
            <p>From routine checkups to specialized treatments, we offer a full range of veterinary services to keep your pets healthy at every stage of life.</p>
        </div>
        <div class="services-grid-new">
            <!-- SERVICE 1: Wellness -->
            <div class="service-card-new">
                <div class="service-info-new">
                    <div class="service-icon-circle"><i class="fa-solid fa-heart-pulse"></i></div>
                    <h3>Wellness</h3>
                    <p>Routine examinations, preventive healthcare, and complete wellness plans to keep your pets happy and healthy.</p>
                </div>
                <div class="service-image-new">
                    <img src="assets/images/vaccination.png" alt="Wellness">
                </div>
            </div>

            <!-- SERVICE 2: Laboratories -->
            <div class="service-card-new">
                <div class="service-info-new">
                    <div class="service-icon-circle"><i class="fa-solid fa-vial"></i></div>
                    <h3>Laboratories</h3>
                    <p>Comprehensive laboratory testing including bloodwork and diagnostics for accurate medical evaluation.</p>
                </div>
                <div class="service-image-new">
                    <img src="assets/images/laboratories.png" alt="Laboratories">
                </div>
            </div>

            <!-- SERVICE 3: Check Up -->
            <div class="service-card-new">
                <div class="service-info-new">
                    <div class="service-icon-circle"><i class="fa-solid fa-stethoscope"></i></div>
                    <h3>Check Up</h3>
                    <p>Thorough physical examinations and consultations to monitor your pet's overall health and well-being.</p>
                </div>
                <div class="service-image-new">
                    <img src="assets/images/checkup.png" alt="Check Up">
                </div>
            </div>

            <!-- SERVICE 4: Minor Surgeries -->
            <div class="service-card-new">
                <div class="service-info-new">
                    <div class="service-icon-circle"><i class="fa-solid fa-scissors"></i></div>
                    <h3>Minor Surgeries</h3>
                    <p>Safe and professional minor surgical procedures performed with care by our experienced veterinary team.</p>
                </div>
                <div class="service-image-new">
                    <img src="assets/images/minor-surgeries.png" alt="Minor Surgeries">
                </div>
            </div>
        </div>
    </div>
</section>

<!-- =====================================
    RABIES PREVENTION AWARENESS
===================================== -->
<section class="video-section" id="awareness">
    <div class="container video-container">
        <!-- LEFT -->
        <div class="video-content">
            <span class="video-badge" style="background: #f0eeff; color: #5142f5;">Pet Care Awareness</span>
            <h2 style="color: #1e1b4b;">Rabies Prevention Awareness</h2>
            <p>
                Protect your pets and your family by learning the importance of
                rabies prevention. Regular vaccination, responsible pet ownership,
                and proper awareness can save lives.
            </p>
            <ul class="video-list">
                <li>✔ Vaccinate your pets regularly.</li>
                <li>✔ Avoid contact with stray animals.</li>
                <li>✔ Wash bite wounds immediately with soap and water.</li>
                <li>✔ Visit your veterinarian immediately after an animal bite.</li>
            </ul>
        </div>
        <!-- RIGHT -->
        <div class="video-player">
            <video
                controls
                playsinline
                webkit-playsinline
                preload="metadata"
                poster="assets/images/rabies-thumbnail.jpg">
                <source src="assets/videos/rabies-prevention.mp4" type="video/mp4">
                Your browser does not support the video tag.
            </video>
        </div>
    </div>
</section>

<!-- =====================================
    CLIENT TESTIMONIALS (ONE LAYER HORIZONTAL SLIDER)
===================================== -->
<section class="testimonial-section-new" id="testimonial">
    <div class="testimonial-container-new">
        <div class="testimonial-top-row">
            <div class="testimonial-header-new">
                <span class="badge">Client Reviews</span>
                <h2>Real Stories, Happy Tails</h2>
                <p>See what pet parents are saying about their experience with Furry Friends Animal Clinic.</p>
            </div>
            <div class="testimonial-nav-btns">
                <button class="prev-btn" id="prevReviewBtn"><i class="fa-solid fa-chevron-left"></i></button>
                <button class="next-btn" id="nextReviewBtn"><i class="fa-solid fa-chevron-right"></i></button>
            </div>
        </div>

        <div class="testimonial-slider-wrapper">
            <div class="testimonial-grid-new" id="testimonialTrack">
                <!-- Testimonial 1 -->
                <div class="testimonial-card-new">
                    <div class="testimonial-user-profile">
                        <img src="assets/images/testimonial-1.jpg" alt="Crachzel Kyle Asistio" class="testimonial-avatar">
                        <div class="testimonial-user-info">
                            <h4>Crachzel Kyle Asistio</h4>
                            <p>Dog Owner</p>
                        </div>
                    </div>
                    <div class="testimonial-stars">
                        <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                    </div>
                    <p class="testimonial-text-content">
                        "The veterinarians are very kind and professional. They treated my dog with great care and made the whole visit completely stress-free."
                    </p>
                </div>

                <!-- Testimonial 2 -->
                <div class="testimonial-card-new">
                    <div class="testimonial-user-profile">
                        <img src="assets/images/testimonial-2.jpg" alt="Joshua Reyes" class="testimonial-avatar">
                        <div class="testimonial-user-info">
                            <h4>Joshua Reyes</h4>
                            <p>Dog Owner</p>
                        </div>
                    </div>
                    <div class="testimonial-stars">
                        <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                    </div>
                    <p class="testimonial-text-content">
                        "Malinis ang clinic, very comfortable at mababait yung mga doctor. Highly recommended place for your pet's healthcare!"
                    </p>
                </div>

                <!-- Testimonial 3 -->
                <div class="testimonial-card-new">
                    <div class="testimonial-user-profile">
                        <img src="assets/images/testimonial-3.jpg" alt="Robee De Jesus" class="testimonial-avatar">
                        <div class="testimonial-user-info">
                            <h4>Robee De Jesus</h4>
                            <p>Cat Owner</p>
                        </div>
                    </div>
                    <div class="testimonial-stars">
                        <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                    </div>
                    <p class="testimonial-text-content">
                        "Our cat recovered quickly because of their excellent medical care and genuine attention to detail. Truly unmatched service!"
                    </p>
                </div>

                <!-- Testimonial 4 -->
                <div class="testimonial-card-new">
                    <div class="testimonial-user-profile">
                        <img src="assets/images/testimonial-4.jpg" alt="Sarah Jane Santos" class="testimonial-avatar">
                        <div class="testimonial-user-info">
                            <h4>Sarah Jane Santos</h4>
                            <p>Dog Owner</p>
                        </div>
                    </div>
                    <div class="testimonial-stars">
                        <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                    </div>
                    <p class="testimonial-text-content">
                        "Sobrang bait ng staff at mabilis silang mag-accommodate ng mga alaga natin kahit emergency. Sulit na sulit ang tiwala namin dito."
                    </p>
                </div>

                <!-- Testimonial 5 -->
                <div class="testimonial-card-new">
                    <div class="testimonial-user-profile">
                        <img src="assets/images/testimonial-5.jpg" alt="Mark Anthony Cruz" class="testimonial-avatar">
                        <div class="testimonial-user-info">
                            <h4>Mark Anthony Cruz</h4>
                            <p>Cat Owner</p>
                        </div>
                    </div>
                    <div class="testimonial-stars">
                        <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                    </div>
                    <p class="testimonial-text-content">
                        "Very detailed magpaliwanag ang mga veterinarian tungkol sa gamot at diet ng pusa ko. Napaka-ganda ng online records system nila!"
                    </p>
                </div>

                <!-- Testimonial 6 -->
                <div class="testimonial-card-new">
                    <div class="testimonial-user-profile">
                        <img src="assets/images/testimonial-1.jpg" alt="Clarisse Mae Villamor" class="testimonial-avatar">
                        <div class="testimonial-user-info">
                            <h4>Clarisse Mae Villamor</h4>
                            <p>Pet Owner</p>
                        </div>
                    </div>
                    <div class="testimonial-stars">
                        <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                    </div>
                    <p class="testimonial-text-content">
                        "A very clean and pet-friendly environment. My pets didn't feel stressed at all during their routine vaccination check-up."
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- =====================================
    CONTACT US
===================================== -->
<section class="contact" id="contact">
    <div class="container">
        <!-- HEADER -->
        <div class="contact-header">
            <span class="contact-badge" style="background: #f0eeff; color: #5142f5;">CONTACT US</span>
            <h2 style="color: #1e1b4b;">We'd Love to Hear From You</h2>
            <p>Have questions or need assistance? Feel free to contact us or visit our clinic.</p>
        </div>
        <div class="contact-container">
            <!-- LEFT SIDE -->
            <div class="contact-info">
                <div class="info-box">
                    <h3 style="color: #173F81;">📍 Address</h3>
                    <p>
                        Furry Friends Animal Clinic<br>
                        78 Malolos Ave, Bagong Barrio West,<br>
                        Caloocan, 1407 Metro Manila
                    </p>
                </div>
                <div class="info-box">
                    <h3 style="color: #173F81;">📞 Phone</h3>
                    <p>0925-889-7811</p>
                </div>
                <div class="info-box">
                    <h3 style="color: #173F81;">✉ Email</h3>
                    <p>furryfriendsclinic@gmail.com</p>
                </div>
                <div class="info-box">
                    <h3 style="color: #173F81;">🕒 Clinic Hours</h3>
                    <p>Monday - Sunday<br>10:00 AM - 07:00 PM</p>
                </div>
            </div>
            <!-- RIGHT SIDE -->
            <div class="contact-form">
                <form id="contactForm">
                    <input type="text" placeholder="Full Name" required>
                    <input type="email" placeholder="Email Address" required>
                    <input type="text" placeholder="Subject" required>
                    <textarea placeholder="Write your message..." rows="6" required></textarea>
                    <button type="submit" style="background: #5142f5;">📨 Send Message</button>
                </form>
            </div>
        </div>
    </div>
</section>

<!-- =====================================
    FREQUENTLY ASKED QUESTIONS
===================================== -->
<section class="faq" id="faq">
    <div class="container">
        <div class="faq-header">
            <span class="faq-badge" style="background: #f0eeff; color: #5142f5;">Frequently Asked Questions</span>
            <h2 style="color: #1e1b4b;">Have Questions? We Have Answers</h2>
        </div>
        <div class="faq-container">
            <div class="faq-item">
                <div class="faq-question">
                    <h3 style="color: #1e1b4b;">How can I book an appointment?</h3>
                    <span style="color: #5142f5;">+</span>
                </div>
                <div class="faq-answer">
                    <p>You can book an appointment by creating an account, logging in, and selecting your preferred date and time through the Smart Vet Care system.</p>
                </div>
            </div>
            <div class="faq-item">
                <div class="faq-question">
                    <h3 style="color: #1e1b4b;">What animals do you accept?</h3>
                    <span style="color: #5142f5;">+</span>
                </div>
                <div class="faq-answer">
                    <p>Our clinic currently provides veterinary services for dogs and cats.</p>
                </div>
            </div>
            <div class="faq-item">
                <div class="faq-question">
                    <h3 style="color: #1e1b4b;">Can I access my pet's medical records online?</h3>
                    <span style="color: #5142f5;">+</span>
                </div>
                <div class="faq-answer">
                    <p>Yes. Registered pet owners can securely access their pet's vaccination history, medical records, and appointments.</p>
                </div>
            </div>
            <div class="faq-item">
                <div class="faq-question">
                    <h3 style="color: #1e1b4b;">Do you accept emergency cases?</h3>
                    <span style="color: #5142f5;">+</span>
                </div>
                <div class="faq-answer">
                    <p>Yes. Emergency cases are accommodated during our regular clinic hours, depending on availability.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- =====================================
    FOOTER SECTION
===================================== -->
<footer class="footer" style="background: #173F81;">
    <div class="footer-container">
        <div class="footer-left">
            <p>&copy; 2026, Smart Vet Care</p>
            <div class="footer-links">
                <a href="includes/privacy.php">Privacy Policy</a>
                <a href="includes/terms.php">Terms and Conditions</a>
            </div>
        </div>
        <div class="footer-right">
            <a href="mailto:furryfriendsclinic@yahoo.com" class="footer-email">furryfriendsclinic@yahoo.com</a>
            <div class="footer-socials">
                <a href="https://www.facebook.com/furryfriendsanimalclinic" target="_blank"><i class="fab fa-facebook-f"></i></a>
            </div>
        </div>
    </div>
</footer>

<script src="assets/js/script.js"></script>
<script src="assets/js/faq.js"></script>

<!-- TESTIMONIAL SLIDER SCRIPT -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    const track = document.getElementById('testimonialTrack');
    const prevBtn = document.getElementById('prevReviewBtn');
    const nextBtn = document.getElementById('nextReviewBtn');
    const cards = document.querySelectorAll('.testimonial-card-new');

    if (!track || !prevBtn || !nextBtn || cards.length === 0) return;

    let currentIndex = 0;

    function getVisibleCardsCount() {
        if (window.innerWidth <= 576) return 1;
        if (window.innerWidth <= 992) return 2;
        return 3;
    }

    function updateSlider() {
        const visibleCards = getVisibleCardsCount();
        const maxIndex = cards.length - visibleCards;
        
        if (currentIndex > maxIndex) currentIndex = maxIndex;
        if (currentIndex < 0) currentIndex = 0;

        const cardWidth = cards[0].getBoundingClientRect().width;
        const gap = 24;
        const moveAmount = (cardWidth + gap) * currentIndex;

        track.style.transform = `translateX(-${moveAmount}px)`;
    }

    nextBtn.addEventListener('click', function () {
        const visibleCards = getVisibleCardsCount();
        const maxIndex = cards.length - visibleCards;

        if (currentIndex < maxIndex) {
            currentIndex++;
        } else {
            currentIndex = 0; // Loop pabalik sa simula
        }
        updateSlider();
    });

    prevBtn.addEventListener('click', function () {
        const visibleCards = getVisibleCardsCount();
        const maxIndex = cards.length - visibleCards;

        if (currentIndex > 0) {
            currentIndex--;
        } else {
            currentIndex = maxIndex; // Loop papunta sa huli
        }
        updateSlider();
    });

    window.addEventListener('resize', updateSlider);
});
</script>
</body>
</html>