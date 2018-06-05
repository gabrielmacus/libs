<?php
include "PhpSerial.php";
$comPort = "COM8"; //The com port address. This is a debian address

$msg = '';

if(isset($_POST["hi"])){

    $serial = new phpSerial;

    $serial->deviceSet($comPort);

    $serial->confBaudRate(9600);

    $serial->confParity("none");

    $serial->confCharacterLength(8);

    $serial->confStopBits(1);

    $serial->deviceOpen();

    sleep(2); //Unfortunately this is nessesary, arduino requires a 2 second delay in order to receive the message

    $serial->sendMessage("Well hello!");
    var_dump($serial->readPort());

    $serial->deviceClose();

    $msg = "You message has been sent! WOHOO!";

}

?>

<html>

<head>

    <title>Arduino control</title>

</head>

<body>

<form method="POST">

    <input type="submit" value="Send" name="hi">

</form><br>

<?=$msg?>

</body>

</html>