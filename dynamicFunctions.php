<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    initialize_action();
}

function initialize_action()
{
    session_start();

    $textBody = file_get_contents("php://input");
    if ($textBody) {
        ob_start();
        $body = null;
        try {
            $body = json_decode($textBody);
        } catch (Exception $error) {
            echo false;
            exit();
        }
        if (isset($body->functionName)) {
            $functionName = $body->functionName;
            $response = "";
            switch ($functionName) {
                case 'attempt_user_login':
                    $data = $body->data;
                    if (
                        isset($data->email) &&
                        isset($data->password)
                    ) {
                        $response = attempt_user_login($data);
                    }
                    break;
                case 'delete_session':
                    delete_session();
                    break;
                case 'change_theme':
                    $response = change_theme();
                    break;
                case 'attempt_add_user':
                    $data = $body->data;
                    if (
                        isset($data->name) &&
                        isset($data->surname) &&
                        isset($data->adress) &&
                        isset($data->email) &&
                        isset($data->password)
                    ) {
                        $response = json_encode(attempt_add_user($data));
                    }
                    break;
                    case 'reset_password':
                        $data = $body->data;
                        if (
                            isset($data->email)
                        ) {
                            $response = json_encode(reset_password($data));
                        }
                        break;
                case 'get_site_description':
                    $response = get_site_description();
                    break;
            }

            if (isset($_SESSION['logged'])&& $_SESSION['logged']) {
                switch ($functionName) {
                    case 'get_session_data':
                        $response = json_encode(get_session_data());
                        break;
                }
            }

            if (isset($_SESSION['user_type']) && $_SESSION['user_type'] == 2) {
                switch ($functionName) {
                    case 'get_database_table_names':
                        $response = json_encode(get_database_table_names());
                        break;
                    case 'get_data_table_template':
                        $response = get_data_table_template();
                        break;
                    case 'get_database_table_data':
                        $data = $body->data;
                        if (
                            isset($data->tableName)
                        ) {
                            $response = json_encode(get_database_table_data($data->tableName));
                        }
                        break;
                }
            }

            ob_end_clean();

            echo $response;
        }
    }
}

function connect_db()
{
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "Magazin";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    return $conn;
}

function set_session_data($data)
{
    session_unset();

    $_SESSION['logged'] = true;
    $_SESSION['theme'] = $data['tema'];
    $_SESSION['email'] = $data['email'];
    $_SESSION['user_type'] = $data['id_tip_utilizatori'];
    $_SESSION['name'] = $data['prenume'];
}

function attempt_user_login($data)
{
    $response = "false";
    $conn = connect_db();

    foreach ($data as &$element) {
        $element = $conn->real_escape_string($element);
    }
    $email = $data->email;
    $password = $data->password;

    $sql = "SELECT * FROM utilizatori WHERE email = '$email' AND parola = '$password'";
    $result = $conn->query($sql);

    if ($result !== FALSE) {
        if ($result->num_rows > 0) {
            $data = $result->fetch_all(MYSQLI_ASSOC)[0];
            set_session_data($data);
            $response = "true";
        }
    }
    $conn->close();
    return $response;
}

function attempt_add_user($data)
{
    $response["email_found"] = "false";
    $conn = connect_db();

    $name = $conn->real_escape_string($data->name);
    $surname = $conn->real_escape_string($data->surname);
    $adress = $conn->real_escape_string($data->adress);
    $email = $conn->real_escape_string($data->email);
    $password = $conn->real_escape_string($data->password);
    $date = date("Y-m-d");

    if (isArrayElementEmpty($data)) {
        $response["error"] = "fields cannot be empty";
    } else if (!isValidEmail($data->email)) {
        $response["error"] = "Email does not follow the accepted structure (\"example@email.com\")";
    } else if (!isValidName($data->name) || !isValidName($data->surname)) {
        $response["error"] = "Name can only contain Alphanumeric characters and \"_\"";
    } else if (!isValidPassword($data->password)) {
        $response["error"] = "Password should be at least 8 characters long and can only contain Alphanumeric characters symbols in \"_!@#$%^&*()-+=\"";
    } else if (!emailAvailable($email)) {
        $response["error"] = "This email is already taken";
    } else {
        $sql = "INSERT INTO Utilizatori (nume, prenume, adresa, email, parola, tema, id_tip_utilizatori, data_inregistrarii)
    VALUES ('$name', '$surname', '$adress', '$email', '$password','dark', 0, '$date')";
        $result = $conn->query($sql);

        if ($result !== FALSE) {
            $response["email_found"] = "true";
        } else {
            $response["error"] = "There was a request error";
        }
    }
    $conn->close();
    return $response;
}
function reset_password($data)
{
    $response["email_found"] = "false";
    $conn = connect_db();

    $email = $conn->real_escape_string($data->email);

    if (isArrayElementEmpty($data)) {
        $response["error"] = "fields cannot be empty";
    } else if (!isValidEmail($data->email)) {
        $response["error"] = "Email does not follow the accepted structure (\"example@email.com\")";
    }
     else {
        $sql = "SELECT parola FROM Utilizatori WHERE email = '$email'";
        $result = $conn->query($sql);

        if ($result !== FALSE) {
            if ($result->num_rows > 0) {
                $data = $result->fetch_all(MYSQLI_ASSOC)[0]["parola"];
                $response=($data);
            }
        } else {
            $response["error"] = "There was a request error";
            $response["error"] = (string)$email;
        }
    }
    $conn->close();
    return $response;
}

function isArrayElementEmpty($data)
{
    foreach ($data as $element) {
        if (empty($element)) {
            return true;
        }
    }
}

function emailAvailable($email)
{
    $conn = connect_db();

    $sql = "SELECT * FROM utilizatori WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result !== FALSE && $result->num_rows == 0) {
        return true;
    }
    return false;
}

function isValidName($name)
{
    $pattern = '/^[a-zA-Z0-9_]+$/';
    return preg_match($pattern, $name) === 1;
}

function isValidPassword($password)
{
    $response = false;
    $pattern = '/^[a-zA-Z0-9_!@#$%^&*()-+=]+$/';

    if ((strlen($password) >= 8) && (preg_match($pattern, $password) === 1)) {
        $response = true;
    }
    return $response;
}

function isValidEmail($email)
{
    $pattern = '/^[a-zA-Z0-9._]+@[a-zA-Z0-9_]+.[a-zA-Z0-9]+$/';
    return preg_match($pattern, $email) === 1;
}

function get_database_table_names()
{
    $conn = connect_db();
    $result = $conn->query("SHOW TABLES");

    $tables = array();
    while ($row = $result->fetch_row()) {
        $tables[] = $row[0];
    }
    $conn->close();
    return $tables;
}

function get_database_table_data($tableName)
{
    $conn = connect_db();
    $tableName = $conn->real_escape_string($tableName);

    $sql = "SELECT * FROM $tableName";
    $result = $conn->query($sql);

    $tableData = array();
    if ($result !== FALSE && $result->num_rows > 0) {
        $tableData = $result->fetch_all(MYSQLI_ASSOC);
    }
    $conn->close();
    return $tableData;
}

function get_data_table_template()
{
    ob_clean();
    ?>
    <div id="tableWrap">
        <div id="selectBar">
            <select id="tableSelector" name="selectedTable">
            </select>
        </div>
        <div id="TableBox">
            <table id="dataTable">
            </table>
        </div>
    </div>
    <?php

    $tableTemplate = ob_get_clean();

    return $tableTemplate;
}

function get_site_description()
{
    ob_clean();
    ?>
    <div class="message_wrap">
        <h2>
            <p>
                Buna ziua
                <span>
                    <?php echo ((isset($_SESSION['logged'])) ? ($_SESSION['name']) : ("Vizitator")) ?>!
                </span>
            </p>
        </h2>
    </div>
    <?php

    $siteDescription = ob_get_clean();

    return $siteDescription;
}

function change_theme()
{
    $conn = connect_db();

    if (isset($_SESSION['theme'])) {
        switch ($_SESSION['theme']) {
            case "light":
                $_SESSION['theme'] = "dark";
                break;
            case "dark":
                $_SESSION['theme'] = "light";
                break;
            default:
                $_SESSION['theme'] = "dark";
        }
        $sql = "UPDATE utilizatori SET tema = '" . $_SESSION['theme'] . "' WHERE email = '" . $_SESSION['email'] . "';";
        $result = $conn->query($sql);
    }

    $conn->close();

    return $_SESSION['theme'];
}

function get_theme()
{
    if (!isset($_SESSION['theme'])) {
        $_SESSION['theme'] = "dark";
    }
    return $_SESSION['theme'];
}

function get_session_data()
{
    return $_SESSION;
}

function delete_session()
{
    session_unset();
    session_destroy();
}

?>