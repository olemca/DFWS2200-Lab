<?php
// Define variables and set them to empty values
$emailErr = $usernameErr = $passwordErr = $genderErr = "";
$email = $username = $password = $gender = "";

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (empty($_POST["email"])) {
        $emailErr = "Email is required";
    } else {
        $email = test_input($_POST["email"]);
        // check if e-mail address is well-formed
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $emailErr = "Invalid email format";
        }
    }

    if (empty($_POST["username"])) {
        $usernameErr = "Username is required";
    } else {
        $username = test_input($_POST["username"]);

        // check if name only contains letters and whitespace
        if (!(ctype_alnum($username))) {
            $usernameErr = "Only letters and numbers allowed";
        }
    }

    if (empty($_POST["password"])) {
        $passwordErr = "Password is required";
    } else {
        $password = test_input($_POST["password"]);

        if (!ctype_alnum($password)) {
            $passwordErr = "Only letters and numbers allowed!";
        }
    }

    if (empty($_POST["gender"])) {
        $genderErr = "Gender is required";
    } else {
        $gender = test_input($_POST["gender"]);
    }

    // Submit was clicked, try to insert data to database
    if(isset($_POST["Submit"])) {
        // Using prepared statement to safely insert user data into tables
        $stmt = $db->prepare("INSERT INTO user (email, username, password) VALUES (:email, :username, :password)");

        // Declearing types and adding values for the insert
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        $stmt->bindValue(':username', $username, PDO::PARAM_STR);
        $stmt->bindValue(':password', $password, PDO::PARAM_STR);

        // Execute the query in the database with the inserted values
        $stmt->execute();

        // Retrieving the id from the insertion if nessesary
        $insertId = $db->lastInsertId();
    }
}

?>

<div class="register">
    <form id="register" action="/signuplogin" method="post">
        <fieldset>
            <legend>Register</legend>
            <label for="email" >Email Address*:</label>
            <input type="email" placeholder="Thomas@gmail.com"
                    name="email" id="email" maxlength="45" value="<?=$email?>"/>
            <span class="error"> <?=$emailErr?></span>
            <br /><br />

            <label for="username" >Username*:</label>
            <input type="text" placeholder="Thomas"
                    name="username" id="username" maxlength="45" value="<?=$username?>"/>
            <span class="error"> <?=$usernameErr?></span>
            <br /><br />

            <label for="password" >Password*:</label>
            <input type="password" placeholder="Thomas123"
                    name="password" id="password" maxlength="45" value="<?=$password?>"/>
            <span class="error"> <?=$passwordErr?></span>
            <br /><br />

            <label for="gender" >Gender*:</label>
            <br />
            <input type="radio" name="gender" <?php if (isset($gender) && $gender=="female")
                echo "checked";?> value="female">Female
            <input type="radio" name="gender" <?php if (isset($gender) && $gender=="male")
                echo "checked";?> value="male">Male
            <br />
            <span class="error"> <?=$genderErr?></span>
            <br /><br />
            <input type="submit" name="Submit" value="Submit" />
        </fieldset>
    </form>
</div>
