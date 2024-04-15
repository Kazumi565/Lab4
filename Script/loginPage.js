async function log_in(){
    const email = document.getElementById("email").value;
    const password = document.getElementById("password").value;
    const errorField = document.getElementById("errorField");

    data = {
        email: email,
        password: password
    }

    let user_logged = await attempt_user_login(data);

    if (user_logged === "true") {
        window.open("Dashboard.php", "_self");
    }
    else if (user_logged === "false") {
        errorField.textContent = "Your login or password is wrong";
    }
    else{
        errorField.textContent = "Error on logging in";
    }
}

function enter_as_guest() {
    const url = "dynamicFunctions.php";
    let functionBody = {
        functionName: "delete_session",
    };

    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(functionBody)
    })
        .catch(error => {
            console.error(error);
        });

    window.open("Dashboard.php", "_self");
}

function create_new_user() {
    window.open("CreateUserPage.php", "_self");
}

function reset_password() {
    window.open("ResetPasswordPage.php", "_self");
}