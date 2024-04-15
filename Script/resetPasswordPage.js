async function reset_password() {
    const email = document.getElementById("email").value;
    let parolaRecuperata = document.getElementById("parolaRecuperata");
    let data = {
        email: email,
    }
    console.log(await attempt_reset_password(data));
    parolaRecuperata.innerText=(await attempt_reset_password(data)).toString();
}
async function attempt_reset_password(data){
    const url = "dynamicFunctions.php";
    let functionBody = {
        functionName: "reset_password",
        data: data
    };
    let user_password = "";
    try {
        user_password = await fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(functionBody)
        })
            .then(response => {
                return response.text();
            })
    }
    catch (error) {
        console.error(error);
    }
    return user_password;
}

function redirect_to_login_page(){
    window.open("LoginPage.php", "_self");
}