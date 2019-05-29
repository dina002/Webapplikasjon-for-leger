<?php

include ("css.php");

$sqlSetning="SELECT * FROM timeinndeling ORDER BY tidspunkt;";
$sqlResultat=mysqli_query($db,$sqlSetning) or die ("Ikke mulig å hente data");
$antallRader=mysqli_num_rows($sqlResultat);

for ($r=1;$r<=$antallRader;$r++) {
    
    $rad=mysqli_fetch_array($sqlResultat);
    
    $tidspunkt=$rad["tidspunkt"];
    
    print ("<option value='$tidspunkt'>$tidspunkt</option>");
}

?>