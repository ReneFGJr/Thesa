<?php
$Socials = new \App\Models\Socials();
if ((isset($_SESSION['id'])) and ($_SESSION['id'] != '')) {
    $acesso = $Socials->nav_user();
} else {
    $lk = "'" . PATH . '/social/login' . "'";
    $acesso = '<li class="nav-item" style="list-style-type: none;">';
    $acesso .= '<button class="btn btn-outline-danger" ';
    $acesso .= 'onclick="location.href=' . $lk . ';" ';
    $acesso .= 'style="margin-left: 7px;" type="submit">';
    $acesso .= 'ACESSO';
    $acesso .= '</button>';
    $acesso .= '</li>';
}
?>