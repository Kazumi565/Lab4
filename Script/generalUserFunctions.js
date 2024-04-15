function password_visibility_change(event) {
    var inputBox = event.currentTarget.parentElement;
    var input = inputBox.getElementsByTagName('input')[0];
    if (input.type === "password") {
        input.type = "text";
    } else {
        input.type = "password";
    }
}

async function attempt_user_login(data) {
    const url = "dynamicFunctions.php";
    let functionBody = {
        functionName: "attempt_user_login",
        data: data
    };

    let user_logged = "";
    try {
        user_logged = await fetch(url, {
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
    
    return user_logged;
}