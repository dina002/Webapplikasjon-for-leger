<?php
  include("css.php");

  $sqlSetning="SELECT * FROM behandler ORDER BY brukernavn;";

  $sqlResultat=mysqli_query($db, $sqlSetning) or die("Synj, db not wurk");
  $antallRader=mysqli_num_rows($sqlResultat);

  for($r=1;$r<=$antallRader;$r++){
    $rad=mysqli_fetch_array($sqlResultat);
    $brukernavn=$rad["brukernavn"];
    $behandlernavn=$rad["behandlernavn"];
    $yrkesgruppe=$rad["yrkesgruppe"];
    $bildenr=$rad["bildenr"];
    print("<option value='$brukernavn;$behandlernavn;$yrkesgruppe;$bildenr'>$behandlernavn $yrkesgruppe</option>");
  }

?>
