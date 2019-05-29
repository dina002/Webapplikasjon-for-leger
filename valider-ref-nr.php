<?php

   function validerRefNr($best_ref) {
   $lovligRefNr=true;

    if (!$best_ref) {
        $lovligRefNr=false;
    }
    else if (!preg_match('/[a-z]+[0-9]+/', $best_ref))
    {
      $lovligRefNr=false;
    }

        return $lovligRefNr;
   }
?>
