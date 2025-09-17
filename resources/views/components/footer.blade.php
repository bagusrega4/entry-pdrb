<style>
    footer {
        background: #222;
        color: #fff;
        padding: 40px 0;
        font-size: 14px;
    }

    footer .sitename {
        font-weight: 600;
        font-size: 16px;
        color: #fff;
    }

    footer .footer-contact p {
        margin: 0 0 6px 0;
        line-height: 1.6;
    }

    footer .social-links a {
        font-size: 18px;
        display: inline-block;
        color: #ddd;
        margin-right: 12px;
        transition: 0.3s;
    }

    footer .social-links a:hover {
        color: #fff;
    }

    footer iframe {
        width: 100%;
        max-width: 100%;
        border-radius: 8px;
    }

    footer .copyright {
        margin-top: 30px;
        font-size: 13px;
        color: #aaa;
    }

    footer .credits a {
        color: #ffcc00;
        text-decoration: none;
    }

    footer .credits a:hover {
        text-decoration: underline;
    }

    /* Responsif di HP */
    @media (max-width: 768px) {
        .footer-about {
            text-align: center;
        }

        .footer-about .social-links {
            justify-content: center;
        }
    }
</style>

<footer id="footer" class="mt-12 footer dark-background">

    <div class="container footer-top">
        <div class="row gy-4">

            <!-- Info BPS -->
            <div class="col-12 col-md-6 footer-about">
                <a href="index.html" class="logo d-flex align-items-center mb-3">
                    <span class="sitename">Badan Pusat Statistik Kabupaten Kediri</span>
                </a>

                <div class="footer-contact pt-2">
                    <p>Jl. Pamenang No. 42</p>
                    <p>Kabupaten Kediri</p>
                    <p class="mt-3"><strong>Phone </strong> <span>(62-354) 689673</span></p>
                    <p><strong>Fax </strong> <span>(62-354) 689673</span></p>
                    <p><strong>Email: </strong> <span>bps3506@bps.go.id</span></p>
                </div>

                <div class="social-links d-flex mt-4">
                    <a href="https://x.com/bps_kediri"><i class="bi bi-twitter-x"></i></a>
                    <a href="https://www.facebook.com/bpskediri"><i class="bi bi-facebook"></i></a>
                    <a href="https://www.instagram.com/bps_kediri/"><i class="bi bi-instagram"></i></a>
                    <a href="https://www.youtube.com/@KegiatanBPSKabupatenKediri"><i class="bi bi-youtube"></i></a>
                </div>
            </div>

            <!-- Peta Google -->
            <div class="col-12 col-md-6">
                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3952.846275136441!2d112.04155177368307!3d-7.806093492214241!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e78575ed1647ac9%3A0x344c8a07809b3605!2sBadan%20Pusat%20Statistik%20Kabupaten%20Kediri!5e0!3m2!1sen!2sid!4v1755050615300!5m2!1sen!2sid"
                    width="100%" height="250"
                    style="border:0;"
                    allowfullscreen=""
                    loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>

        </div>

        <div class="container copyright text-center mt-4">
            <p>© <span>Copyright</span>
                <strong class="px-1 sitename">2025/2026</strong>
                <span>BPS Kabupaten Kediri</span>
            </p>
            <div class="credits">
                <a href="mailto:bps3506@bps.go.id">E-mail : bps3506@bps.go.id</a>
            </div>
        </div>
    </div>
</footer>