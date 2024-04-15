<?php
function load_create_user_page()
{
    ?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Document</title>
        <link rel="stylesheet" href="Styles/generalLogin.css">
    </head>

    <body>
        <div id="contentWrap">
            <div style="display: flex; flex-direction: row; justify-content: space-between;">
                <input style="width: 49%" type="text" id="name" placeholder="Name">
                <input style="width: 49%" type="text" id="surname" placeholder="Surname">
            </div>
            <input type="text" id="email" placeholder="Email">
            <input type="text" id="adress" placeholder="Adress">
            <div style="position: relative;">
                <input style="width: 100%; padding-right: 0.8cm;" type="password" id="password" placeholder="Password">
                <input class="show-password-checkbox" type="checkbox" onclick="password_visibility_change(event)">
            </div>
            <div style="position: relative;">
                <input style="width: 100%; padding-right: 0.8cm;" type="password" id="confirmPassword"
                    placeholder="Confirm password">
                <input class="show-password-checkbox" type="checkbox" onclick="password_visibility_change(event)">
            </div>
            <button id="SignUpButton" onclick="sign_up()">Create an account</button>
            <button id="RedirectToLogin" onclick="redirect_to_login_page()">Back to login</button>
            <div id="errorField"></div>
        </div>
        <script src="Script/CreateUserPage.js"></Script>
        <script src="Script/generalUserFunctions.js"></Script>
    </body>

    </html>

    <?php
}

load_create_user_page();
?>