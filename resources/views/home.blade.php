<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>SiReMon BPS Kabupaten Kediri</title>
    <meta name="description" content="">
    <meta name="keywords" content="">

    <!-- Favicons -->
    <link rel="icon" href="{{ asset('/assets/img/logo.png') }}" type="image/x-icon" />
    <link href="{{ asset('assets/img/apple-touch-icon.png') }}" rel="apple-touch-icon">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;500;700;900&family=Poppins:wght@100;200;300;400;500;600;700;800;900&family=Raleway:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/aos/aos.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/glightbox/css/glightbox.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/swiper/swiper-bundle.min.css') }}" rel="stylesheet">

    <!-- Main CSS File -->
    <link href="{{ asset('assets/css/main.css') }}" rel="stylesheet">

    <style>
        /* --- Tambahan agar lebih responsif --- */
        .hero .hero-bg {
            width: 100%;
            height: auto;
            object-fit: cover;
        }

        .hero .hero-img img {
            max-width: 75%;
            height: auto;
        }

        .section {
            padding: 40px 20px;
        }

        @media (max-width: 768px) {
            .hero h1 {
                font-size: 28px;
                line-height: 36px;
            }

            .hero p {
                font-size: 14px;
            }

            .hero .hero-img img {
                max-width: 50%;
            }

            .btn-get-started {
                font-size: 14px;
                padding: 10px 20px;
            }
        }
    </style>
</head>

<body class="index-page">

    <x-header />

    <main class="main">

        <!-- Hero Section -->
        <section id="hero" class="hero section dark-background">
            <img src="assets/img/hero-bg-2.jpg" alt="" class="hero-bg img-fluid">

            <div class="container">
                <div class="row gy-4 align-items-center">
                    <!-- Hero Text -->
                    <div class="col-lg-6 order-2 order-lg-1 d-flex flex-column justify-content-center text-center text-lg-start" data-aos="fade-in">
                        <h1>Sistem Rencana Kerja <span>Monitoring</span></h1>
                        <p>Sistem rencana kerja dan monitoring (SiReMon) untuk pegawai BPS Kabupaten Kediri</p>
                        <div class="d-flex justify-content-center justify-content-lg-start">
                            <a href="/login" class="btn-get-started">Login</a>
                        </div>
                    </div>

                    <!-- Hero Image -->
                    <div class="col-lg-5 col-md-8 order-1 order-lg-2 hero-img text-center" data-aos="zoom-out" data-aos-delay="100">
                        <img src="assets/img/icon-landing-page.png" class="img-fluid w-75" alt="">
                    </div>
                </div>
            </div>

            <svg class="hero-waves" xmlns="http://www.w3.org/2000/svg" viewBox="0 24 150 28" preserveAspectRatio="none">
                <defs>
                    <path id="wave-path" d="M-160 44c30 0 58-18 88-18s 58 18 88 18 
                58-18 88-18 58 18 88 18 v44h-352z"></path>
                </defs>
                <g class="wave1">
                    <use xlink:href="#wave-path" x="50" y="3"></use>
                </g>
                <g class="wave2">
                    <use xlink:href="#wave-path" x="50" y="0"></use>
                </g>
                <g class="wave3">
                    <use xlink:href="#wave-path" x="50" y="9"></use>
                </g>
            </svg>
        </section>
        <!-- /Hero Section -->

        <!-- About Section -->
        <section id="about" class="about section">
            <div class="container" data-aos="fade-up" data-aos-delay="100">
                <div class="row align-items-center gy-4">

                    <!-- About Content -->
                    <div class="row mb-4">
                        <div class="col-lg-12 text-center">
                            <div class="content">
                                <h2>Sistem Rencana Kerja dan Monitoring</h2>
                                <p>Merupakan alat bantu untuk mempermudah pegawai BPS Kabupaten Kediri dalam merencanakan dan memonitoring kegiatan dinas di luar kantor.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Icon Boxes -->
                    <div class="row gy-4">
                        <div class="col-md-6 text-center text-md-start" data-aos="fade-up" data-aos-delay="300">
                            <div class="icon-box">
                                <i class="bi bi-clipboard-pulse"></i>
                                <h3>Monitoring Renja</h3>
                                <p>Peningkatan efektivitas dalam memonitor rencana kerja melalui penerapan fitur notifikasi yang memberikan informasi secara real-time</p>
                            </div>
                        </div>

                        <div class="col-md-6 text-center text-md-start" data-aos="fade-up" data-aos-delay="400">
                            <div class="icon-box">
                                <i class="bi bi-command"></i>
                                <h3>Kolaborasi</h3>
                                <p>Memberikan dukungan untuk meningkatkan proses kolaborasi antar tim/divisi di BPS Kabupaten Kediri</p>
                            </div>
                        </div>
                    </div> <!-- End Icon Boxes Row -->

                </div>
            </div>
        </section>
        <!-- /About Section -->

    </main>

    <x-footer />

    <!-- Scroll Top -->
    <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

    <!-- Preloader -->
    <div id="preloader"></div>

    <!-- Vendor JS Files -->
    <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/php-email-form/validate.js') }}"></script>
    <script src="{{ asset('assets/vendor/aos/aos.js') }}"></script>
    <script src="{{ asset('assets/vendor/glightbox/js/glightbox.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/purecounter/purecounter_vanilla.js') }}"></script>
    <script src="{{ asset('assets/vendor/swiper/swiper-bundle.min.js') }}"></script>

    <!-- Main JS File -->
    <script src="{{ asset('assets/js/main.js') }}"></script>

</body>

</html>