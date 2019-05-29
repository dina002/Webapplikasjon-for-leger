<?php
    include("incl/start.html");
 ?>
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
   <br>
   <?php
     print("<table class='table table-striped'>
       <thead>
       <tr>
       <th>Brukernavn</th>
       <th>Behandlernavn</th>
       <th>Yrkesgruppe</th>
       <th>Bilde</th>
       <th>Se uke</th>
       </tr>
       </thead><tbody><tr>");
     include("css.php");
     $sqlSetning="SELECT * FROM behandler ORDER BY yrkesgruppe;";
     $sqlResultat=mysqli_query($db, $sqlSetning) or die("Ikke mulig å hente data fra databasen");
     $antallRader=mysqli_num_rows($sqlResultat);

     print("<h3>Registrerte behandlere</h3>");
     for($r=1;$r<=$antallRader;$r++){
       print("<form method='post' action=''>");
       $rad=mysqli_fetch_array($sqlResultat);
       $brukernavn=$rad["brukernavn"];
       $behandlernavn=$rad["behandlernavn"];
       $yrkesgruppe=$rad["yrkesgruppe"];
       $bilde=$rad["bildenr"];

       $sqlSetning1="SELECT * FROM bilde WHERE bildenr='$bilde';";
       $sqlResultat1=mysqli_query($db, $sqlSetning1) or die("Hei");
       $rad1=mysqli_fetch_array($sqlResultat1);
       $filnavn=$rad1["filnavn"];
       print("<tr>
       <td><input type='hidden' name='hidden' value='$brukernavn'>$brukernavn</td>
       <td>$behandlernavn</td>
       <td>$yrkesgruppe</td>
       <td><a href='https://home.hbv.no/phptemp/web-prg11v09/bilder/$filnavn'><img style='width:100px;'src='https://home.hbv.no/phptemp/web-prg11v09/bilder/$filnavn' target='_blank'></a></td>
       <td>
       <input type='submit' value='Se uke' id='seUke' name='seUke'>
       </form>
       </td>
       </tr>");
       print("</form>");
     }

     print("</tr></tbody></table>");

     @$seUke=$_POST["seUke"];
     $ukedager=array("Mandag","Tirsdag","Onsdag","Torsdag","Fredag");

     if($seUke){
       $brukernavn=$_POST["hidden"];

       for($y=0;$y<=4;$y++){
         print("<table class='table table-striped mini'><tr><th class='heads'>$ukedager[$y]</th><th class='heads'>Tidspunkt</th></tr>");
         $sqlSetning="SELECT * FROM timeinndeling WHERE brukernavn='$brukernavn' AND ukedag='$ukedager[$y]';";
         $sqlResultat=mysqli_query($db, $sqlSetning) or die("Synj, db not wurk");
         $antallRader=mysqli_num_rows($sqlResultat);

         for($c=1;$c<=$antallRader;$c++){
             $rad=mysqli_fetch_array($sqlResultat);
             $brukernavn=$rad["brukernavn"];
             $ukedag=$rad["ukedag"];
             $tidspunkt=$rad["tidspunkt"];
             print("<tr><td>$ukedag</td><td>$tidspunkt</td></tr>");

           }
         print("</table>");
       }


     }


   ?>
 </div>
        </div>
</div>
<?php include("incl/slutt.html"); ?>
