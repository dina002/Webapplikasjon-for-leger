function bekreft(){
  return confirm("Sikker?");
}
function validerKlassekode(klassekode)
{
  var tegn0,tegn1;
  var lovligKlassekode=true;

  if(!klassekode)
  {
    lovligKlassekode=false;
  }
  else if (klassekode.length<3)
  {
    lovligKlassekode=false;
  }
  else{
    tegn0=klassekode.substr(-1);
    
  }

  return lovligKlassekode;
}

function validerRegistrerKlassedata()
{

  var klassenavn=document.getElementById("fagnavn").value;
  var klassekode=document.getElementById("klassekode").value;

  var lovligKlassekode=validerKlassekode(klassekode);

  var feilmelding="";

  if(!lovligKlassekode)
  {
    feilmelding="Klassekode er ikke korrekt fylt ut<br>";
  }
  if(!klassenavn){
    feilmelding=feilmelding+"Klassenavn er ikke fylt ut<br>";
  }
  if(lovligKlassekode)
  {
    return true;
  }
  else
  {
    document.getElementById("melding").style.color="red";
    document.getElementById("melding").innerHTML=feilmelding;
    return false;
  }
}

function validerbrukernavn(brukernavn)
{
  var lovligbrukernavn=true;
  if(!brukernavn)
  {
    lovligbrukernavn=false;
  }
  else if (brukernavn.length!=2)
  {
    lovligbrukernavn=false;
  }
  return lovligbrukernavn;
}

function validerRegistrerStudentdata()
{

  var brukernavn=document.getElementById("brukernavn").value;
  var fornavn=document.getElementById("fornavn").value;
  var etternavn=document.getElementById("etternavn").value;

  var lovligbrukernavn=validerbrukernavn(brukernavn);

  var feilmelding="";

  if(!lovligbrukernavn)
  {
    feilmelding="Brukernavn er ikke korrekt fylt ut<br>";
  }
  if(!fornavn){
    feilmelding=feilmelding+"Fornavn er ikke fylt ut<br>";
  }
  if(!etternavn){
    feilmelding=feilmelding+"Etternavn er ikke fylt ut<br>";
  }
  if(lovligbrukernavn)
  {
    return true;
  }
  else
  {
    document.getElementById("melding").style.color="red";
    document.getElementById("melding").innerHTML=feilmelding;
    return false;
  }
}
