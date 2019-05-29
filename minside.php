<?php
    include("incl/start.html");
    include("css.php");
 ?>
 <script src="incl/funksjon.js"></script>
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
              <h3>Min side </h3><br>
              <form method="post">

                Referansenummer: <input type="text" name="best_ref" id="best_ref" required><br><br>
                <input type="submit" value="Se time" name="finnTimeKnapp" id="finnTimeKnapp"><br><br><br>
              </form>
                <?php
@$finnTimeKnapp=$_POST ["finnTimeKnapp"];
 include ("css.php");
if ($finnTimeKnapp) {
   include ("valider-ref-nr.php");

     @$best_ref=$_POST["best_ref"];
    $lovligRefNr=validerRefNr($best_ref);

 if (!$lovligRefNr) {
    print ("Referansenummer er ikke korrekt fylt ut");
 }
  else{

    $sqlSetning="SELECT * FROM time WHERE best_ref='$best_ref';";
    $sqlResultat=mysqli_query($db, $sqlSetning) or die("Ikke mulig å hente data");
    $antallRader=mysqli_num_rows($sqlResultat);

    if($antallRader==0){
      print("Denne finnes ikke!");
    }else{

    print ("<table class='table table-striped'>
    <thead>
    <tr>
    <th>Referansenummer</th>
    <th>Navn</th>
    <th>Ukedag</th>
    <th>Tidspunkt</th>
    <th>Dato</th>
    <th>Behandlernavn</th>
    <th>Endre</th>
    <th>Slett</th>
    </tr>
    </thead><tbody>");

    for ($r=1;$r<=$antallRader;$r++)
    {
      $rad=mysqli_fetch_array($sqlResultat);
      $best_ref=$rad["best_ref"];
      $navn=$rad["navn"];
      $ukedag=$rad["ukedag"];
      $tid=$rad["tid"];
      $dato=$rad["dato"];
      $navn=$rad["navn"];
      $behandlernavn=$rad["behandlernavn"];
      $brukernavn=$rad["brukernavn"];

         print ("<tr>
         <td>$best_ref</td>
         <td>$navn</td>
         <td>$ukedag</td>
         <td>$tid</td>
         <td>$dato</td>
         <td>$behandlernavn</td>
         <td>
          <form method='post' action='minside-endre.php'>
          <input type='hidden' value='$best_ref' id='hidden1' name='hidden1'readonly>
          <input type='hidden' value='$navn' id='hidden2' name='hidden2'readonly>
          <input type='hidden' value='$ukedag' id='hidden3' name='hidden3'readonly>
          <input type='hidden' value='$tid' id='hidden4' name='hidden4'readonly>
          <input type='hidden' value='$behandlernavn' id='hidden5' name='hidden5'readonly>
          <input type='hidden' value='$dato' id='hidden6' name='hidden6'readonly>
          <input type='hidden' value='$brukernavn' id='hidden7' name='hidden7'readonly>
          <input type='submit' value='Endre' id='endreTime' name='endreTime'>
          </form>
        </td>
        <td>
         <form method='post' onSubmit='return bekreft()'>
         <input type='submit' value='Slett' id='slettTime' name='slettTime'>
         <input type='hidden' value='$best_ref' id='hidden1' name='hidden1'readonly>
         <input type='hidden' value='$navn' id='hidden2' name='hidden2'readonly>
         <input type='hidden' value='$ukedag' id='hidden3' name='hidden3'readonly>
         <input type='hidden' value='$tid' id='hidden4' name='hidden4'readonly>
         <input type='hidden' value='$behandlernavn' id='hidden5' name='hidden5'readonly>
         </form>
       </td>
         </tr>");
      }

      print("</tbody></table>");
    }
}
}
@$slettTime=$_POST["slettTime"];
if($slettTime){
  @$best_ref=$_POST["hidden1"];
  @$navn=$_POST["hidden2"];
  @$ukedag=$_POST["hidden3"];
  @$tid=$_POST["hidden4"];
  @$behandlernavn=$_POST["hidden5"];

  $sqlSetning="DELETE FROM time WHERE best_ref='$best_ref';";
  mysqli_query($db, $sqlSetning) or die("Ikke mulig å slette fra database!!!");
  print("Følgende time er slettet: <br>Referansenummer:$best_ref<br>Navn: $navn<br>Ukedag: $ukedag<br>Tid: $tid<br>Behandlernavn: $behandlernavn");
}
?>
</div>
</div>
</div>
<?php include("incl/slutt.html"); ?>
