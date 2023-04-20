<div class="container">
    <div class="row">
        <div class="col-5 col-md-4 p-4 text-end" style="margin-top: 15%; margin-bottom: 15%;">
            <img src="<?= PATH . '/img/logo/logo_thesa.svg'; ?>" class="img-fluid anim-text-flow">
            <br />
            <span class="awesome anim-text-flow"><?= lang("thesa.tsa"); ?></span>
        </div>

        <div class="col-1 col-md-2">
        </div>

        <div class="col-6 col-md-6 p-4">
            <?= $content; ?>
        </div>
    </div>
</div>

<style>
    .awesome {
        margin: 0 auto;
        text-align: center;

        color: #313131;
        font-size: 0.7em;

        text-align: right;
        -webkit-animation: colorchange 20s infinite alternate;
    }

    @-webkit-keyframes colorchange {
        0% {
            color: #ee7755;
        }

        10% {
            color: #9723d5;
        }

        20% {
            color: #1abc9c;
        }

        30% {
            color: #d35400;
        }

        40% {
            color: blue;
        }

        50% {
            color: #34495e;
        }

        60% {
            color: blue;
        }

        70% {
            color: #2980b9;
        }

        80% {
            color: #f1c40f;
        }

        90% {
            color: #2980b9;
        }

        100% {
            color: pink;
        }
    }
</style>