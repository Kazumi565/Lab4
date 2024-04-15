<?php
load_login_page();

function load_login_page()
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
            <input type="text" id="email" placeholder="Email" value="Ion@gmail.com">
            <div style="position: relative;">
                <input style="width: 100%; padding-right: 0.8cm;" type="password" id="password" placeholder="Password"
                    value="Macovei">
                <input class="show-password-checkbox" type="checkbox" onclick="password_visibility_change(event)">
            </div>
            <button id="LoginButton" onclick="log_in()">Login</button>
            <div style="display: flex; flex-direction: row; justify-content: space-between;">
                <button style="width: 48%" id="SignUpButton" onclick="create_new_user()">Sign up</button>
                <button style="width: 48%" id="ResetPassword" onclick="reset_password()">Reset Password</button>
            </div>
            <button id="guestButton" onclick="enter_as_guest()">Enter as guest</button>
            <div id="errorField"></div>
        </div>
        <script src="Script/loginPage.js"></Script>
        <script src="Script/generalUserFunctions.js"></Script>
    </body>

    </html>

    <?php
}
?>