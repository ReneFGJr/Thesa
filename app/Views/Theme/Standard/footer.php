<?php
if (!isset($citation)) { $citation = 'Thesa'; }
$bg_color = 'bg-black';
if (!isset($bgcolor)) {
    $bgcolor = 'bg-secondary text-white';
}
?>
<!-- Footer -->
<footer class="text-center text-lg-start <?php echo $bgcolor;?>  mt-5">
    <!-- Section: Social media -->
    <section class="d-flex justify-content-center justify-content-lg-between p-4 border-bottom">
        <!-- Left -->
        <div class="me-5 d-none ">
            <span><?php echo $citation;?></span>
        </div>
        <!-- Left -->

        <!-- Right -->
        <div>
            <a href="" class="me-4 text-reset">
                <i class="fab fa-facebook-f"></i>
            </a>
            <a href="" class="me-4 text-reset">
                <i class="fab fa-twitter"></i>
            </a>
            <a href="" class="me-4 text-reset">
                <i class="fab fa-google"></i>
            </a>
            <a href="" class="me-4 text-reset">
                <i class="fab fa-instagram"></i>
            </a>
            <a href="" class="me-4 text-reset">
                <i class="fab fa-linkedin"></i>
            </a>
            <a href="" class="me-4 text-reset">
                <i class="fab fa-github"></i>
            </a>
        </div>
        <!-- Right -->
    </section>
    <!-- Section: Social media -->

    <!-- Section: Links  -->
    <section class="">
        <div class="container text-center text-md-start mt-5">
            <!-- Grid row -->
            <div class="row mt-3">
                <!-- Grid column -->
                <div class="col-md-3 col-lg-4 col-xl-3 mx-auto mb-4">
                    <!-- Content -->
                    <p>
                        Sobre o THESA 2.0
                    </p>
                </div>
                <!-- Grid column -->

                <!-- Grid column -->
                <div class="col-md-2 col-lg-2 col-xl-2 mx-auto mb-4">
                    <!-- Links -->
                    <p>
                        <a href="#!" class="text-reset">Como citar</a>
                    </p>
                </div>
                <!-- Grid column -->

                <!-- Grid column -->
                <div class="col-md-6 col-lg-4 col-xl-4 mx-auto mb-6">
                    <!-- Links -->
                </div>
                <!-- Grid column -->

            </div>
            <!-- Grid row -->
        </div>
    </section>
    <!-- Section: Links  -->

    <!-- Copyright -->
    <div class="text-center p-4" style="background-color: rgba(0, 0, 0, 0.05);">
        © <?=date("Y");?> Copyright:
        <a class="text-reset fw-bold" href="https://ufrgs.br/thesa/">UFRGS</a>
    </div>
    <!-- Copyright -->
</footer>
<!-- Footer -->