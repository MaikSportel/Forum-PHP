<?php
session_start();
$_SESSION['message'] = '';

$mysqli = new mysqli('localhost','root','mypass123','phpforum');

if($_SERVER['REQUEST_METHOD'] == 'post') {

    //Wachtwoorden zijn gelijk
    if($_POST['wachtwoord'] == $_POST['wachtwoordherhalen']) {

        print_r($_FILES); die;

        $gebruikersnaam = $mysqli->real_escape_string('gebruikersnaam');
        $email = $mysqli->real_escape_string('email');
        $wachtwoord = md5($_POST['wachtwoord']);        //Door md5 word je wachtwoord gehashed
        $avatarpad = $mysqli->real_escape_string('image/'.$_FILES['avatar']['name']);


        // Juiste type voor afbeeldingen
        if(preg_match("!image!",$_FILES['avatar']['type'])){

            // Afbeelding naar afbeelding map sturen
            if(copy($_FILES['avatar']['tmp_name'], $avatarpad)){

                $_SESSION['gebruikersnaam'] = $gebruikersnaam;
                $_SESSION['avatar'] = $avatarpad;


                $s1l = "INSERT INTO users (gebruikersnaam,email,wachtwoord,avatar) "
                    . "VALUES('$gebruikersnaam', '$email', '$wachtwoord', '$avatarpad')";

                if ($mysqli->query($sql) === true) {
                    $_SESSION['message'] = "Registratie voltooid! $gebruikersnaam is toegevoegd aan de database!";
                    header("location: profiel.php");
                }
                else {
                    $_SESSION['message'] = "Gebruiker kon niet worden toegevoegd";
                }
            }
            else {
                $_SESSION['message'] = "Upload is niet geslaagd";
            }



        }
        else {
            $_SESSION['message'] = "Geen afbeeldingsformaat";}
    }
    else {
        $_SESSION['message'] = "De wachtwoorden zijn niet gelijk!";
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

    <form class="form" action="register.php" method="post" enctype="multipart/form-data" autocomplete="off">
        <section class="alert alert-info"><?=$_SESSION['message'] ?></section>
        <input class="gbregister" type="text" placeholder="Gebruikersnaam" name="gebruikersnaam" required />
        <input class="emailregister" type="email" placeholder="E-mail" name="email" required />
        <input class="wwregister" type="password" placeholder="Wachtwoord" name="wachtwoord" autoclomplete="new-password" required />
        <input class="hhwwregister" type="password" placeholder="Herhaal wachtwoord" name="wachtwoordherhalen" autoclomplete="new-password" required />
        <section class="avatar"><label>Kies uw avatar</label><input type="file" name="avatar" accept="image/*"  /> </section>
        <input type="submit" value="Registreer" name="registreer" class="knopregistreren" />
    </form>





</section>


<footer>
    <section class="footer">
        Copyright by Maik ©
    </section>
</footer>

</body>
</html>