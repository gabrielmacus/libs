<?php

shell_exec("mode com8: BAUD=9600 PARITY=n DATA=8 STOP=1 to=off dtr=off rts=off");
echo $consola;
$fp = fopen ("com8", "w");

$status = "connected";
echo $status;
fwrite($fp, "Hola Arduino");
}