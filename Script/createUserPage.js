function sign_up() {
    const name = document.getElementById("name").value;
    const surname = document.getElementById("surname").value;
    const email = document.getElementById("email").value;
    const adress = document.getElementById("adress").value;
    const password = document.getElementById("password").value;
    const confirmPassword = document.getElementById("confirmPassword").value;
    const errorField = document.getElementById("errorField");

    let data = {
        name: name,
        surname: surname,
        email: email,
        adress: adress,
        password: password
    }

    if(password === confirmPassword){
        attempt_add_user(data);
    }
    else{
        errorField.textContent = "Passwords do not match";
    }
}

async function attempt_add_user(data) {
    const errorField = document.getElementById("errorField");
   
    const url = "dynamicFunctions.php";
    let functionBody = {
        functionName: "attempt_add_user",
        data: data
    };

    try {
        $response = await fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(functionBody)
        })
            .then(response => {
                return response.text();
            });
        $response = JSON.parse($response);
    }
    catch (error) {
        console.error(error);
    }

    console.log($response);

    if ($response.user_added === "true") {
        $user_logged = await attempt_user_login(data);
        if($user_logged === "true"){
            window.open("Dashboard.php", "_self");
        }
        else{
            window.open("LoginPage.php", "_self");
        }
    }
    else {
        errorField.textContent = $response.error;
    }
}

function redirect_to_login_page() {
    window.open("LoginPage.php", "_self");
}