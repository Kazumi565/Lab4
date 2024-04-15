<?php
require_once 'dynamicFunctions.php';

load_dashboard();

function load_dashboard()
{
    session_start();

    ?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Document</title>
        <link rel="stylesheet" href="Styles/generalDashboard.css">
        <link id="theme" rel="stylesheet" href="Styles/<?php echo get_theme() ?>.css">
    </head>

    <body>
        <?php create_navbar(); ?>
        <div id="contentWrap">
        </div>
        <script src="Script/dashboard.js"></Script>
    </body>

    </html>
    <?php
}

function create_navbar()
{
    ?>
    <div id="vertical_navbar">
        <button id="navThemeButton" class="nav_button" onclick="change_theme()">Change theme</button>
        <button id="navDescriptionButton" class="nav_button" onclick="load_description()">Description</button>
        <?php
        if (isset($_SESSION['user_type']) && $_SESSION['user_type'] == 2) {
            ?>
            <button id="navDataTableButton" class="nav_button" onclick="load_data_table()">Tabel de date</button>
            <?php
        }
        if (isset($_SESSION['logged'])) {
            ?>
            <button id="navLogoutButton" class="nav_button" onclick="logout()">Log out</button>
            <?php
        } else {
            ?>
            <button id="navLoginButton" class="nav_button" onclick="login()">Log in</button>
            <?php
        }
        ?>
    </div>
    <?php
}

?>