<!-- Footer -->
<footer class="bg-seconday text-center text-lg-start text-black mt-5" style="border-top: 1px solid #4B0082;">

    <!-- Copyright -->
    <div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.2)">
        © 2017-<?= date("Y"); ?> Copyright:
        <a href="https://ufrgs.br/">UFRGS.br</a>
    </div>
    <!-- Copyright -->

    <!-- Grid container -->
    <div class="container p-4">
        <!-- Section: Links -->
        <section class="">
            <!--Grid row-->
            <div class="row">
                <!--Grid column-->
                <div class="col-lg-3 col-md-6 mb-4 mb-md-0">
                    <h5 class="text-uppercase">Sobre</h5>

                    <ul class="list-unstyled mb-0">
                        <li>
                            <a href="#!">Contato</a>
                        </li>
                        <li>
                            <a href="#!">GITHUB</a>
                        </li>
                    </ul>
                </div>
                <!--Grid column-->

                <!--Grid column-->
                <div class="col-lg-3 col-md-6 mb-4 mb-md-0">
                    <h5 class="text-uppercase">Contato</h5>
                    <p>
                        <?= anchor('https://ufrgs.br/thesa'); ?><br />
                        rene.gabriel@ufrgs.br</p>

                </div>
                <!--Grid column-->

                <!--Grid column-->
                <div class="col-lg-3 col-md-6 mb-4 mb-md-0">
                    <h5 class="text-uppercase">Links</h5>
                    CNPq
                </div>
                <!--Grid column-->

                <!--Grid column-->
                <div class="col-lg-3 col-md-6 mb-4 mb-md-0">
                    <h5 class="text-uppercase">Desenvolvimento</h5>

                    <ul class="list-unstyled mb-0">
                        <li>
                            <a href="#!">PPGCIN</a>
                        </li>
                        <li>
                            <a href="#!">ORCALAB</a>
                        </li>
                        <li>
                            <a href="<?= getenv("app.baseURL"); ?>/admin">Admin</a>
                        </li>
                    </ul>
                </div>
                <!--Grid column-->
            </div>
            <!--Grid row-->
        </section>
        <!-- Section: Links -->

    </div>
    <!-- Grid container -->



</footer>
<!-- Footer -->