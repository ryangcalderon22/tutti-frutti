<?php
/**
 * Template Name: Directions
 *
 * @package Tutti_Frutti_Cafe
 */

get_header();
?>

<main id="primary" class="site-main page-directions site-main--page">

    <section class="page-section page-section--cream page-section--top">

        <div class="container">

            <!-- Top Navigation -->
            <div class="directions-top">

                <a href="javascript:history.back();" class="directions-back">
                    ← Back
                </a>

                <a class="btn btn-brand--green directions-top-btn"
                   href="https://www.google.com/maps/dir/?api=1&destination=2357+Foothill+Blvd,+La+Verne,+CA+91750"
                   target="_blank"
                   rel="noopener">
                    Get Directions
                </a>

            </div>

            <!-- Google Map -->
            <div class="map-wrapper">

                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d54113345.29927102!2d168.3984375!3d34.125447565116126!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x80c32fc9d324dcbf%3A0xdc6402e8cff7160f!2s2357%20Foothill%20Blvd%2C%20La%20Verne%2C%20CA%2091750%2C%20USA!5e0!3m2!1sen!2sph!4v1782358622022!5m2!1sen!2sph"
                    width="100%"
                    height="550"
                    style="border:0;"
                    allowfullscreen
                    loading="lazy"
                    referrerpolicy="strict-origin-when-cross-origin">
                </iframe>

            </div>

        </div>

    </section>

    <section class="store-information">

        <div class="container">

            <div class="store-card">

                <h2>Store Information</h2>

                <div class="store-item">
                    <h3>Address</h3>
                    <p>
                        2357 Foothill Blvd<br>
                        La Verne, CA 91750
                    </p>
                </div>

                <div class="store-item">
                    <h3>Phone</h3>
                    <p>(909) 245-1383</p>
                </div>

                <div class="store-item">
                    <h3>Business Hours</h3>

                    <p>
                        Monday – Thursday<br>
                        10:00 AM – 9:00 PM
                    </p>

                    <p>
                        Friday – Sunday<br>
                        10:00 AM – 10:00 PM
                    </p>

                </div>

            </div>

        </div>

    </section>

</main>

<?php get_footer(); ?>