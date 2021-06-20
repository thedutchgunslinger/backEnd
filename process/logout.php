<?php
session_start();
//hier zetten we de sessie variabele loginstate op false om aan te geven dat niemand meer is ingelogd
$_SESSION["loginstate"] = false;

//daarna sturen we de gebruiker naar index.php
header("location: ../index.php");
