<?php
session_start();
$_SESSION['message'] = '';

$mysqli = new mysqli('localhost','root','mypass123','accounts');

if($_SERVER['REQUEST_METHOD'] == 'POST') {

    if ($_POST['password'] == $_POST['confirmpassword'])
    {

        $username = $mysqli->real_escape_string($_POST['username']);
        $email = $mysqli->real_escape_string($_POST['email']);

        $password = md5($_POST['password']);

        $avatar_path = $mysqli->real_escape_string('images/'.$_FILES['avatar']['name']);

        if (preg_match("!image!",$_FILES['avatar']['type'])) {
            //copy image to images/ folder
            if (copy($_FILES['avatar']['tmp_name'], $avatar_path)) {
                $_SESSION['username'] = $username;
                $_SESSION['avatar'] = $avatar_path;

                //create SQL query string for inserting data into the database
                $sql = "INSERT INTO users (username, email, password, avatar) "
                    . "VALUES ('$username', '$email', '$password', '$avatar_path')";

                if ($mysqli->query($sql) === true) {
                    $_SESSION['message'] = "$username is succesvol geregistreerd, welkom op het forum!";
                    //redirect the user to welcome.php
                    header("location: welcome.php");
                }
                else {
                    $_SESSION['message'] = 'Gebruiker kan helaas niet toegevoegd worden aan de database';
                }
                $mysqli->close();
            }
            else {
                $_SESSION['message'] = 'Het is niet gelukt om het bestand up te loaden';
            }
        }
        else {
            $_SESSION['message'] = 'Upload alleen: GIF, JPG of PNG afbeeldingen!';
        }
    }
    else {
        $_SESSION['message'] = 'De wachtwoorden zijn niet gelijk!';
    }
}



?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<section class="header">
    <img class="titel" src="css/img/titel.png">
    <nav>
        <ul>
            <li><a href="index.php">Home</a> </li>
            <li><a href="serie.php">Serie</a> </li>
            <li><a href="cast.php">Cast</a> </li>
            <li><a href="comics.php">Comics</a> </li>
            <li><a href="games.php">Games</a> </li>
            <li><a href="contact.php">Contact</a> </li>
            <li class="container">
                <section class="dropdown">
                    <button class="dropbtn" onclick="myFunction()">Login</button>
                    <section class="dropdown-content" id="myDropdown">
                        <a href="#">Login</a>
                        <a href="#">Profiel</a>
                        <a href="register.php">Registreer</a>
                    </section>
                </section>
            </li>
        </ul>
    </nav>
</section>
<script>
    /* When the user clicks on the button,
     toggle between hiding and showing the dropdown content */
    function myFunction() {
        document.getElementById("myDropdown").classList.toggle("show");
    }

    // Close the dropdown if the user clicks outside of it
    window.onclick = function(e) {
        if (!e.target.matches('.dropbtn')) {
            var myDropdown = document.getElementById("myDropdown");
            if (myDropdown.classList.contains('show')) {
                myDropdown.classList.remove('show');
            }
        }
    }
</script>
<section class="blok">

    <div class="module">
            <h1>Create an account</h1>
            <form class="form" action="form.php" method="post" enctype="multipart/form-data" autocomplete="off">
                <div class="alert alert-error"><?= $_SESSION['message']?></div>
                <input type="text" placeholder="User Name" name="username" required />
                <input type="email" placeholder="Email" name="email" required />
                <input type="password" placeholder="Password" name="password" autocomplete="new-password" required />
                <input type="password" placeholder="Confirm Password" name="confirmpassword" autocomplete="new-password" required />
                <div class="avatar"><label>Select your avatar: </label><input type="file" name="avatar" accept="image/*" required /></div>
                <input type="submit" value="Register" name="register" class="btn btn-block btn-primary" />
            </form>
        </div>





</section>


<footer>
    <section class="footer">
        Copyright by Maik ©
    </section>
</footer>

</body>
</html>