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
            <input type="text" id="email" placeholder="Email" value="Ion">
            <h3 id="parolaRecuperata"></h3>
            <button id="ResetPasswordButton" onclick="reset_password()">Send password reset code</button>
            <button id="RedirectToLogin" onclick="redirect_to_login_page()">Back to login</button>
            <div id="errorField"></div>
        </div>
        <script src="Script/resetPasswordPage.js"></Script>
        <script src="Script/generalUserFunctions.js"></Script>
    </body>

    </html>

    <?php
}
?>