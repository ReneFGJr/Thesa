<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title><?= $title; ?></title>

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Thesa">
    <meta name="author" content="Rene Faustino Gabriel Junior">
    <meta name="generator" content="v0.22.09.15">
    <meta name="docsearch:language" content="en">
    <meta name="docsearch:version" content="2.1">
    <link rel="canonical" href="https://ufrgs.br/thesa/">

    <!-- Favicons -->
    <link rel="apple-touch-icon" href="<?= getenv("app.baseURL"); ?>/img/favicons/favicon-32x32.png" sizes="180x180">
    <link rel="icon" href="<?= getenv("app.baseURL"); ?>/img/favicons/favicon-32x32.png" sizes="32x32" type="image/png">
    <link rel="icon" href="<?= getenv("app.baseURL"); ?>/img/favicons/favicon-32x32.png" sizes="16x16" type="image/png">
    <link rel="mask-icon" href="<?= getenv("app.baseURL"); ?>/img/favicons/favicon-32x32.png" color="#712cf9">
    <link rel="icon" href="<?= getenv("app.baseURL"); ?>/img/favicons/favicon.ico">
    <meta name="theme-color" content="#712cf9">

    <!-- CSS only -->
    <link href="<?= getenv("app.baseURL"); ?>/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= getenv("app.baseURL"); ?>/css/thesa.css" rel="stylesheet">

    <!-- JavaScript Bundle with Popper -->
    <script>
        $path = "<?php echo PATH; ?>";
    </script>
    <script src="<?= getenv("app.baseURL"); ?>/js/bootstrap.bundle.min.js"></script>
    <script src="<?= getenv("app.baseURL"); ?>/js/jquery.js"></script>
    <script type="text/javascript" src="https://code.jquery.com/ui/1.8.24/jquery-ui.min.js"></script>
    <script src="<?= getenv("app.baseURL"); ?>/js/thesa.js"></script>
    <script src="<?= getenv("app.baseURL"); ?>/js/drop_drag.js"></script>
    <script src="<?= getenv("app.baseURL"); ?>/js/clipboard.js"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lora&family=Montserrat:wght@100;300;600&family=Titillium+Web:wght@200;400;600&display=swap" rel="stylesheet">
</head>
<style>
    body {
        /* font-family: 'Montserrat', sans-serif; */
        font-family: 'Titillium Web', 'Montserrat', sans-serif;
    }
</style>