<?php
session_start();

function checkLogin()
{
    if (!isset($_SESSION['login']) || $_SESSION['login'] == false) {
        header("Location: /index.php");
    }
}

function responseSuccess()
{
    if (isset($_SESSION['success_message']) && !empty($_SESSION['success_message'])) {
        echo "<div class=\"success-message\" style=\"margin-bottom: 20px;font-size: 20px;color: green;\">{$_SESSION['success_message']}</div>";
        unset($_SESSION['success_message']);
    }
}

function responseError()
{
    if (isset($_SESSION['error_message']) && !empty($_SESSION['error_message'])) {
        echo "<div class=\"error-message\" style=\"margin-bottom: 20px;font-size: 20px;color: red; text-align: center;\">{$_SESSION['error_message']}</div>";
        unset($_SESSION['error_message']);
    }
}

function pagination($jmlHalaman, $halamanAktif)
{
    $pagination = "
    <nav aria-label = \"Page navigation example\">
        <ul class=\"pagination\">
    ";
    if ($halamanAktif > 1) {
        $pagination .= "
            <li class=\"page-item\">
                <a class=\"page-link\" href=\"?halaman=" . ($halamanAktif - 1) . "\" aria-label=\"Previous\">
                    <span aria-hidden=\"true\">&laquo;</span>
                    <span class=\"sr-only\"> Previous </span>
                </a>
            </li>
        ";
    }
    for ($i = 1; $i <= $jmlHalaman; $i++) {
        if ($i == $halamanAktif) {
            $pagination .= "
            <li class=\"page-item active\">
                <a class=\"page-link\" href=\"?halaman=" . $i . "\">" . $i . "</a>
            </li>
        ";
        } else {
            $pagination .= "
                <li class=\"page-item\">
                    <a class=\"page-link\" href=\"?halaman=" . $i . "\">" . $i . "</a>
                </li>
            ";
        }
    }
    if ($halamanAktif < $jmlHalaman) {
        $pagination .= "
            <li class=\"page-item\">
                <a class=\"page-link\" href=\"?halaman=" . ($halamanAktif + 1) . "\" aria-label = \"Next\">
                    <span aria-hidden=\"true\">&raquo;</span>
                    <span class=\"sr-only\"> Next </span>
                </a>
            </li>
        ";
    }
    $pagination .= "
        </ul>
    </nav>
    ";
    echo $pagination;
}
