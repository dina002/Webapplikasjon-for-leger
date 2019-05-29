<?php
    include("incl/start.html");
 ?>
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
              <h3>Velg behandler for å bestille time</h3>
              <form method="post">
                <select name="velgBehandler">
                  <?php include("listeboks-behandlere.php"); ?>
                </select><br><br>
                <input type="submit" value="Velg behandler" name="finnBehandlerKnapp" id="finnBehandlerKnapp"><br><br>
              </form>
              <?php
              @$finnBehandlerKnapp=$_POST["finnBehandlerKnapp"];

              if($finnBehandlerKnapp){
                $velgBehandler=$_POST["velgBehandler"];
                $del=explode(";", $velgBehandler);
                $brukernavn=$del[0];
                $yrkesgruppe=$del[2];
                $behandlernavn=$del[1];

                $sqlSetning="SELECT * FROM timeinndeling WHERE brukernavn='$brukernavn';";
                $sqlResultat=mysqli_query($db, $sqlSetning) or die("Hei");
                $rad=mysqli_fetch_array($sqlResultat);
                $brukernavn=$rad["brukernavn"];
                $ukedag=$rad["ukedag"];
                $tidspunkt=$rad["tidspunkt"];


                print("<form method='post'>");
                print("Dato <input type='text' name='dato' id='dato' required> <br><br>");
                print("<input type='hidden' name='hidden1' id='hidden1' value='$brukernavn'>");
                print("<input type='hidden' name='hidden2' id='hidden2' value='$behandlernavn'>");
                print("<input type='hidden' name='hidden3' id='hidden3' value='$yrkesgruppe'>");


                print("$behandlernavn<br><br>");


                print("<input type='submit' value='Se ledige' name='seledige' id='seledige'><br><br>");
                print("</form>");
              }
              @$seledige=$_POST["seledige"];

              if($seledige){
                $dato=$_POST["dato"];
                $dato2=date('Y-m-d');
                $brukernavn=$_POST["hidden1"];
                $behandlernavn=$_POST["hidden2"];
                $yrkesgruppe=$_POST["hidden3"];
                $ukedag=array("Mandag","Tirsdag","Onsdag","Torsdag","Fredag");
                $aar=substr($dato,0,4);
                $mnd=substr($dato,5,2);
                $dag=substr($dato,8,2);
                $time=0;
                $min=0;
                $sek=0;
                $gittTimestamp=mktime($time,$min,$sek,$mnd,$dag,$aar);
                $dagensukedagnr=date("N",$gittTimestamp)-1;
                $dagensukedagnavn=$ukedag[$dagensukedagnr];

                if($dato<$dato2){
                print("<p style='color:red;'>Ikke mulig å bestille fra tidligere dato</p>");
                }else{
                print("$behandlernavn, $yrkesgruppe sine ledige timer:<br><br>");
                print("<div class='blue'></div> = Ledig");
                print(" <div class='red'></div> = Optatt<br>");
                print("<table class='table table-striped mini'>");
                print("<form method='post'>");
            	print("<thead>
            	<tr>
            	<th class='heads'>$dagensukedagnavn</th>
            	<th class='heads'>$dato</th>
            	</tr>
            	</thead><tbody><tr>");

              $sqlSetning="SELECT * FROM timeinndeling WHERE brukernavn='$brukernavn' AND ukedag='$dagensukedagnavn';";
          	  $sqlResultat=mysqli_query($db, $sqlSetning) or die("Ikke mulig å hente e data fra databasen");
          	  $antallRader=mysqli_num_rows($sqlResultat);

          	  for($r=1;$r<=$antallRader;$r++){
          	    $rad=mysqli_fetch_array($sqlResultat);
                $brukernavn=$rad["brukernavn"];
                $ukedag=$rad["ukedag"];
                $tidspunkt=$rad["tidspunkt"];
                print("<form method='post'>");

                $sqlS="SELECT * FROM time WHERE brukernavn='$brukernavn' AND dato='$dato' AND tid='$tidspunkt'";
            	  $sqlR=mysqli_query($db, $sqlS) or die("Ikke mulig å hente data fra databasen");
            	  $a_rader=mysqli_num_rows($sqlR);
                if($a_rader==0){
                  print("<tr><td colspan='2' class='ledig'><input type='radio' value='$tidspunkt' name='tid'>$tidspunkt</td></tr>");
                } else {
                     print("<tr><td colspan='2' class='optatt'>$tidspunkt</td></tr>");
                }
          	  }

          		print("</tr></tbody></table>");
              print("<input type='hidden' value='$brukernavn' id='hidden4' name='hidden4'readonly>");
              print("<input type='hidden' value='$behandlernavn' id='hidden7' name='hidden7'readonly>");
              print("<input type='hidden' value='$dato' id='hidden5' name='hidden5'readonly>");
              print("<input type='hidden' value='$dagensukedagnavn' id='hidden6' name='hidden6'readonly>");

              print("<h4>Ditt navn</h4>");
              print("<input type='text' id='navn' name='navn' required><br><br>");
              print("<input type='submit' value='Bestill time' name='fortsett' id='fortsett'><br>");
              print("</form><br><br>");
            }
          }
?>



   <?php
@$fortsett=$_POST["fortsett"];
if ($fortsett) {
  if(empty($_POST['tid'])){
    print("Velg en radioknapp!");
  }else {
    $best_ref = uniqid();
    $navn=$_POST["navn"];
    $brukernavn=$_POST["hidden4"];
    $dato=$_POST["hidden5"];
    $tid=$_POST["tid"];
    $ukedag=$_POST["hidden6"];
    $behandlernavn=$_POST["hidden7"];

    if (!$navn) {
        print ("Navn må fylles ut.");
    }
    else {

        include ("css.php");

        $sqlSetning="SELECT * FROM time WHERE best_ref='$best_ref';";
        $sqlResultat=mysqli_query($db,$sqlSetning) or die ("Ikke mulig å hente data fra databasen");
        $antallRader=mysqli_num_rows($sqlResultat);

        if ($antallRader!=0) {
            print ("Timen er registrert fra før");
        }
            else {

                $sqlSetning="INSERT INTO time VALUES ('$best_ref','$brukernavn','$ukedag','$tid','$dato','$navn','$behandlernavn');";
                mysqli_query($db,$sqlSetning) or die ("Ikke mulig å registrere data");


                print("<div class='bestilling'>
              <h3>Følgende time er bestilt:</h3>
              <h4>Bestillingsreferanse:
              $best_ref</h4><br>
              <h4>Navn: $navn</h4><br>
              <h4>Ukedag: $ukedag</h4><br>
              <h4>Tid: $tid</h4><br>
              <h4>Dato: $dato</h4><br>
              <h4>Navn: $navn</h4><br>
              <h4>Behandlernavn: $behandlernavn</h4><br></div>");

               /* print ("Følgende time er nå bestilt:<br> Referansenummer: $best_ref<br> Tid: $tid - $dato<br> Behandler: $behandlernavn ");*/
            }

        }
      }
    }


?>


</div>
</div>
</div>

<?php include("incl/slutt.html"); ?>
