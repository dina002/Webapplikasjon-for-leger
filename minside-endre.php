<?php
    include("incl/start.php");
    @$endreTime=$_POST["endreTime"];
    if($endreTime){

      @$best_ref=$_POST["hidden1"];
      @$navn=$_POST["hidden2"];
      @$ukedag=$_POST["hidden3"];
      @$tid=$_POST["hidden4"];
      @$behandlernavn=$_POST["hidden5"];
      @$dato=$_POST["hidden6"];
      @$brukernavn=$_POST["hidden7"];
      $_SESSION["hidden1"]=$best_ref;
      $_SESSION["hidden2"]=$navn;
      $_SESSION["hidden3"]=$ukedag;
      $_SESSION["hidden4"]=$tid;
      $_SESSION["hidden5"]=$behandlernavn;
      $_SESSION["hidden6"]=$dato;
      $_SESSION["hidden7"]=$brukernavn;
    }
    else{

      $best_ref=$_SESSION["hidden1"];
      $navn=$_SESSION["hidden2"];
      $ukedag=$_SESSION["hidden3"];
      $tid=$_SESSION["hidden4"];
      $behandlernavn=$_SESSION["hidden5"];
      $dato=$_SESSION["hidden6"];
      $brukernavn=$_SESSION["hidden7"];
    }
    $o=$behandlernavn;
    $b=$brukernavn;
 ?>
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
              <h3>Velg ny behandler</h3><br>
              <form method="post">
                <select name="velgBehandler">
                  <?php include("listeboks-behandlere.php"); ?>
                  <?php
                  include("css.php");
                  $sqlQ="SELECT * FROM behandler WHERE behandlernavn='$o' AND brukernavn='$b';";
                  $sqlResult=mysqli_query($db, $sqlQ) or die("Synj, db not wurk");
                  $rad=mysqli_fetch_array($sqlResult);

                  $brukernavn=$rad["brukernavn"];
                  $yrkesgruppe=$rad["yrkesgruppe"];
                  $bildenr=$rad["bildenr"];
                  print("<option value='$brukernavn;$o;$yrkesgruppe;$bildenr' selected='selected'>$o $yrkesgruppe</option>");
                  ?>
                </select><br><br>
                <input type="submit" value="Velg behandler" name="finnBehandlerKnapp" id="finnBehandlerKnapp"><br><br>
              </form>
              <?php


               print("<div class='bestilling'> <h4>Gammel time</h4><br><h5>Bestillingsreferanse: $best_ref</h5><br><h5>Navn: $navn</h5><br>
               <h5>Ukedag: $ukedag</h5><br><h5>Tid: $tid</h5><br><h5>Dato: $dato</h5><br>
               <h5>Behandlernavn: $o</h5><br></div>");
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
                print("Dato <input type='text' name='dato' id='dato' required><br><br>");
                print("<input type='hidden' name='hidden1' id='hidden1' value='$brukernavn'>");
                print("<input type='hidden' name='hidden2' id='hidden2' value='$behandlernavn'>");
                print("<input type='hidden' name='hidden3' id='hidden3' value='$yrkesgruppe'>");


                print(" $behandlernavn <br><br>");


                print("<input type='submit' value='Se ledige' name='seledige' id='seledige'><br>");
                print("</form>");



              }
              @$seledige=$_POST["seledige"];

              if($seledige){
                @$dato2=date('Y-m-d');
                $dato=$_POST["dato"];
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
                  print("<p style='color:red;'>Dato har allerede vært</p>");
                }
                else{
                print("<h4>$behandlernavn, $yrkesgruppe sine ledige timer:</h4>");

                print("<table style='float:left; width:550px; margin-right:10px;' class='table table-striped'>
              	<thead>
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
                print("<input type='text' id='navn' name='navn' value='$navn' required><br><br> ");
                print("<input type='submit' value='Endre time' name='fortsett' id='fortsett'><br>");
                print("</form>");
            }
          }

          @$fortsett=$_POST["fortsett"];
          if ($fortsett) {
            if(empty($_POST['tid'])){
              print("Velg en radioknapp!");
            }else {


            $navn=$_POST["navn"];
            $brukernavn=$_POST["hidden4"];
            $dato=$_POST["hidden5"];
            @$tid=$_POST["tid"];
            $ukedag=$_POST["hidden6"];
            $behandlernavn=$_POST["hidden7"];

            if (!$navn) {
              print ("Navn må fylles ut.");
            }
            else {

              include ("css.php");
                print("<div class='bestilling'> <h4>Ny time</h4><br><h5>Bestillingsreferanse: $best_ref</h5><br><h5>Navn: $navn</h5><br>
               <h5>Ukedag: $ukedag</h5><br><h5>Tid: $tid</h5><br><h5>Dato: $dato</h5><br>
               <h5>Behandlernavn: $behandlernavn</h5><br></div>");


                $sqlSetning="UPDATE time SET brukernavn='$brukernavn', ukedag='$ukedag', tid='$tid', dato='$dato', navn='$navn', behandlernavn='$behandlernavn' WHERE best_ref='$best_ref';";
                mysqli_query($db,$sqlSetning) or die ("Kan ikke registrere");
                @session_start();
          	    session_destroy();  /* sesjonen avsluttes */

          	    print("<META HTTP-EQUIV='Refresh' CONTENT='10;URL=minside.php'>");
              }
            }
            }
?>


</div>
</div>
</div>

<?php include("incl/slutt.html"); ?>
