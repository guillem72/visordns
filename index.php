<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd" >
<html>
<head>
<title>Dominis disponibles</title>
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<link rel="stylesheet" href="visordns.css" type="text/css" />

<script type="text/javascript" src="jquery.min.js"></script>
<script type="text/javascript" src="visordns.js"></script>
</head>
<body>
<div id="header"></div>
<div class="buit"></div>
<?php 
/*@autor Guillem LLuch Moll
 * 
 * Aquest script mostra una llista de tots els dominis que figuren a named.conf.local
 * Via ajax s'obtenen els registres d'aquest dominis
 * @TODO si lnia file Ès tipus file "algunacosa.db";  }; dÛna error
 * */
$config=fopen("/etc/bind/named.conf.local","r");
$directori="/etc/bind/";
$dominis=array();
$nom=NULL;
$arxius=array();
$senyalNom="zone ";
$senyalDB="file ";
$linies=0;
$espera=false; //si s'espera un arxiu BD=false si s'espera un nom de domini=true
//var_dump($config);

if ($config) {
    while (($linia = fgets($config, 4096)) !== false) {
        $linies++;
        //treiem els comentaris
        $pos=strpos($linia,"//");
        if ($pos===false ){			} //no hi ha un comentari
        else 
        {
			//echo "linia comentari ".$linies."<br/>";
			$linia=substr($linia,0,$pos);
			}
        //fi de treiem els comentaris
        
        // Cas obtenim el nom del domini
        if (strpos($linia,$senyalNom)===false  ){} else
        {
			//echo "hi ha zona ".$linies." linia √©s ".$linia."<br/>";
			if ($espera) {exit ("error al tractar named.conf.local a la linea ".$linies);}
			$noms=explode(" ",trim($linia));
			$nomp=trim($noms[1]); //aqu√≠ hi ha el nom del domini en cometes
			$nom=substr($nomp,1,strlen($nomp)-2);
			
			$dominis[]=$nom; 
			$espera=true;
		}
		
		//Cas obtenim la localitzaci√≥ dels arxius amb els registres
		if (strpos($linia,$senyalDB)===false ){} else
        {
			if (!$espera) {exit ("error al tractar named.conf.local a la linea ".$linies);}
			$localitzacions=explode(" ",trim($linia));
			$lloc=trim($localitzacions[1],"\"; ");
			//$arxius[$nom]=$directori.substr($lloc,1,strlen($lloc)-2);
			$pas=explode("/",$lloc);
			$lloc=$pas[count($pas)-1];
			$arxius[$nom]=$directori.$lloc;
			$espera=false;
		}
    }
    if (!feof($config)) {
        echo "Error de fgets()<br/>";
    }
    
}
//var_dump($arxius);
//Mostram la llista amb els dominis
echo "<div id='dominis' >";
echo "<h5>Dominis disponibles:</h5>";
echo "<ul >";
$i=0;
foreach ($arxius as $domini => $arxiu)
{
	$cars=strlen($domini);
	$darrer=substr($domini,$cars-1);
	$cars_arxiu=strlen($arxiu);
	$darrer_arxiu=substr($arxiu,$cars_arxiu-1);
	//echo $darrer;
	//echo $darrer_arxiu;
	if ($darrer=="\""){
	$domini=substr($domini,0,$cars-1);
	}
	if ($darrer_arxiu=="\""){
	$arxiu=substr($arxiu,0,cars_arxi1);
	}
	echo "<li><a href='#' id='link".$i."' >".$domini." <span class='ocult'>".$arxiu."</span></a></li>";
	$i++;
	}
echo "</ul>";
echo "</div>";
fclose($config);

?>
<div id="resultats"></div>
<div id="info" class="ocult">
<?php 
echo $i; 

?>
</div>
<div class="buit"></div>
<span >&nbsp;</span>
</body></html>
