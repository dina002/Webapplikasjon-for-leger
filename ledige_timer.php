<?php include("incl/start.html");?>
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <h3>Se ledige timer hos behandler</h3><br>
                <form method="post" action="" id="" name=" " onSubmit=" ">
                    Behandler <select name='behandlernavn' id='behandlernavn'>
                        <?php include ("listeboks-behandlere.php"); ?>
                    </select> <br><br>
                    Ukenummer <select name="ukenummer" id='ukenummer'>
                     <option value="<?php print(date('W'));?>"><?php print(date('W'));?></option>
                     <option value="<?php print(date('W')+1);?>"><?php print(date('W')+1);?></option>
                     <option value="<?php print(date('W')+2);?>"><?php print(date('W')+2);?></option>
                     <option value="<?php print(date('W')+3);?>"><?php print(date('W')+3);?></option>
                     <option value="<?php print(date('W')+4);?>"><?php print(date('W')+4);?></option>
                     <option value="<?php print(date('W')+5);?>"><?php print(date('W')+5);?></option>
                     <option value="<?php print(date('W')+6);?>"><?php print(date('W')+6);?></option>
                 </select> <br><br>
                    <input type="submit" value="Søk ledige timer" id="fortsett" name="fortsett"/><br><br>
                   <!--- <input type="reset" value="Nullstill" name="nullstill" id="nullstill"/>--->
                </form>
<?php
include("css.php");
@$fortsett=$_POST["fortsett"];
if($fortsett){
    $behandler=$_POST["behandlernavn"];
    $ukenummer=$_POST["ukenummer"];

    $del=explode(";", $behandler);
    $brukernavn=$del[0];
    $behandlernavn=$del[1];
    $yrkesgruppe=$del[2];

    function week_date($week, $year){
    $date = new DateTime();
    return $date->setISODate($year, $week, "1")->format('Y-m-d');

    }
    $dato=week_date($ukenummer,2017);

    print("<h4>Viser ledige timer for $yrkesgruppe $behandlernavn for uke $ukenummer</h4> <br><br>");
    print("<div class='blue'></div> = Ledig");
    print(" <div class='red'></div> = Optatt<br>");
    print("<form method='post'>");

    $ukedager=array("Mandag","Tirsdag","Onsdag","Torsdag","Fredag");

    for($y=0;$y<=4;$y++){
      print("<table class='table table-striped mini'><tr><th class='heads'>$ukedager[$y]</th><th class='heads'>$dato</th></tr>");
      $sqlSetning="SELECT * FROM timeinndeling WHERE brukernavn='$brukernavn' AND ukedag='$ukedager[$y]';";
      $sqlResultat=mysqli_query($db, $sqlSetning) or die("Synj, db not wurk");
      $antallRader=mysqli_num_rows($sqlResultat);

      for($c=1;$c<=$antallRader;$c++){
          $rad=mysqli_fetch_array($sqlResultat);
          $brukernavn=$rad["brukernavn"];
          $ukedag=$rad["ukedag"];
          $tidspunkt=$rad["tidspunkt"];

          $sqlS="SELECT * FROM time WHERE brukernavn='$brukernavn' AND dato='$dato' AND tid='$tidspunkt'";
      	  $sqlR=mysqli_query($db, $sqlS) or die("Ikke mulig å hente data fra databasen");
      	  $a_rader=mysqli_num_rows($sqlR);
          if($a_rader==0){
                print("<tr><td colspan='2' class='ledig'><input type='radio' value='$tidspunkt;$ukedager[$y];$dato' name='tid'>  $tidspunkt</td></tr>");
          } else {
               print("<tr><td colspan='2' class='optatt'>$tidspunkt</td></tr>");
          }

        }

      $dato=date('Y-m-d',strtotime($dato.'+ 1 days'));
    print("</table>");
    }

        print("<input type='hidden' value='$brukernavn' id='hidden4' name='hidden4'readonly>");
        print("<input type='hidden' value='$behandlernavn' id='hidden7' name='hidden7'readonly>");

        print("<h4>Ditt navn</h4>");
        print("<input type='text' id='navn' name='navn' required><br>");
        print("<br><input type='submit' value='Bestill time' id='fortsett1' name='fortsett1'>");
        print("</form>");
}

@$fortsett1=$_POST["fortsett1"];
@$dato2=date('Y-m-d');
@$tiden=$_POST["tid"];
@$del=explode(";", $tiden);
@$dato=$del[2];
@$ukedag=$del[1];
@$tid=$del[0];
if ($fortsett1) {
  if(empty($_POST['tid'])){
    print("Velg en radioknapp!");
  }
  else if($dato<$dato2){
    print("Ikke mulig å registrere time på gammel dato");
  }
  else{
   $best_ref = uniqid();
   $navn=$_POST["navn"];
   $brukernavn=$_POST["hidden4"];


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

               print ("<div class='registrert'><h4>Time bestilt</h4><br> Referansenummer: $best_ref<br> Tid: $tid - $dato<br> Behandler: $behandlernavn </div>");
           }

       }
  }
 }


?>

</div>
</div>
</div>
<?php include("incl/slutt.html"); ?>
