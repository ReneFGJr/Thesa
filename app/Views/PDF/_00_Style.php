<style>
    body {
        font-family: Arial, 'Arial Narrow Bold', sans-serif;
    }

    .page_break {
        page-break-before: always;
    }

    body {
        font-family: Arial, Helvetica, sans-serif;
    }

    #footer {
        position: fixed;
        right: 0px;
        bottom: 10px;
        text-align: right;
    }

    #footer .page:after {
        content: counter(page, decimal);
    }

    #header {
        position: fixed;
        left: 0px;
        top: -50px;
        right: 0px;
        height: 20px;
        background-color: #000044;
        text-align: center;
        color: white;
    }

    #ficha_catalografica {
        border: 1px solid #000000;
        min-height: 350px;
        margin-top: 400px;
        margin-left: 10%;
        margin-right: 10%;
    }

    #footer {
        position: fixed;
        left: 0px;
        bottom: -40px;
        right: 0px;
        height: 20px;
    }

    #footer .page:after {
        content: counter(page);
    }

    @page {
        margin: 50px 50px 60px 60px;
    }

    .small {
        font-size: 0.8em;
    }

    .recuo {
        text-indent: -1em;
        margin-left: 1em;
    }

    h3 {
        text-transform: uppercase;
    }

    .paragrafo {
        text-indent: 2em;
        margin-left: 1em;
        text-align: justify;
        line-height: 140%;
        margin-bottom: 15px;
    }

    .term_h {
        line-height: 100%;
        font-size: 1.1em;
        margin: 0px 0px 0px 0px;
        margin-top: 0px;
    }

    .avanco {
        margin-left: 20px;
        color: red;
    }

    .center {
        text-align: center;
    }

    .term {
        font-size: 1.1em;
        font-weight: bold;
    }
</style>



<head>
    <title><?= $th_name; ?></title>
    <meta name="author" content="Arthur Herbert Fonzarelli">
    <meta name="keywords" content="fonzie, cool, ehhhhhhh">
</head>


<body>