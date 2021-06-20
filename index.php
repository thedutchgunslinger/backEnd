<!DOCTYPE html>
<html>

<head>
    <title>Admin Login</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" sizes="32x32" href="favicon-32x32.png">
    <link rel="stylesheet" href="css/main.css">

<body>
    <form method="POST" action="" class="formHandler">
        <h1>Admin Login</h1>
        <input type="text" name="email" placeholder="E-mail">
        <input type="password" name="wachtwoord" placeholder="Wachtwoord">
        <button type=submit>Log in</button>
    </form>
    <?php
    //Voeg de database connectie in
    include('process/db.php');
    //hier starten we een sessie om je login status te onthouden
    session_start();
    ?>



    <?php




    //als we een post request ontvangen waar email en wachtwoord niet leeg is gaan we kijken of de gegevens in onze database staan.
    if (
        $_SERVER['REQUEST_METHOD'] == "POST"
        && isset($_POST["email"]) && $_POST["email"] != ""
        && isset($_POST["wachtwoord"]) && $_POST["wachtwoord"] != ""
    ) {
        //hier zetten we de POST data in een variabelle
        $email = $_POST['email'];
        $wachtwoord = $_POST['wachtwoord'];
        //voordat we het wachtwoord in de database zetten zorgen we er voor dat we het wachtwoord is gehashed is
        $wachtwoordhash = hash('sha256', $wachtwoord);

        //hier roepen we de functie voor met de database te verbinden uit db.php
        $db = db_connect();

        //dit is een select query, we kijken of we iemand in de database hebben waar het wachtwoord en email het zelfde is als de ingevullde waardes
        $sql = "SELECT * FROM gebruikers WHERE email = :email AND wachtwoord = :wachtwoord ";

        //voordat we de data opsturen willen we eerste onze variabele in de query zetten
        $stmt = $query = $db->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':wachtwoord', $wachtwoordhash);
        //nu is het tijd om de query uit te voeren
        $query->execute();

        //als we we een match vinden zetten we de gevens in sessie variabele zodat we ze later kunnen gebruiken
        if ($query->rowCount() > 0) {
            $record = $query->fetch();


            $_SESSION["logId"] = $record['id'];
            $_SESSION["logVoornaam"] = $record['voornaam'];
            $_SESSION["logAchternaam"] = $record['achternaam'];
            $_SESSION["logEmail"] = $record['email'];

            //ook zetten we loginstate op true zodat we paginas kunnen beschermen van mensen die geen account hebben
            $_SESSION["loginstate"] = true;
            //vervolgens sturen we de gebruiker naar het dashboard
            header("location: dashboard.php");
        }
    }


    //sluit connectie
    $db = null;

    ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
</body>

</html>