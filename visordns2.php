<?php
/*@autor Guillem LLuch Moll
 * 
 * Aquest script mostra un arxiu de text. El nom de l'arxiu es passa per com a paràmetre
 * 
 * */
 include_once("registre.class.php");
$trobar="A"; //cercam registres A
$regitres=array();
$dom2=NULL;
$nom=NULL; $ip=NULL;
$domini="domini";
//$config=fopen("/etc/bind/db.home.lan","r");
//echo "domini: ".$_GET['domini'];
if (isset($_GET['domini']))
{
	$cosa=explode(" ",$_GET['domini']);
	$fitxer=$cosa[1];
	$domini=$cosa[0];
	$config=fopen($fitxer,"r");
}
if ($config)
{
	echo "<h4>Hosts del domini: </h4>";
	echo "<h2>".$domini."</h2><hr/><br/>";
	while (($linia = fgets($config, 4096)) !== false) {
		$elements=explode(".",$linia);
		//var_dump($elements);
		
		if (count($elements)>1 AND $elements[1]) $dom2=$elements[0].".".$elements[1];
		else $dom2=NULL;
		
		$paraules=str_word_count($linia,1); //no afaga les ips ni els nombres
		//var_dump($paraules)."<br><hr/><br/>";
		if (in_array($trobar,$paraules)) { //hem trobat un registre A
		$ip=getIP(trim($linia));
		//echo "linia: ".$linia." ip=".$ip."<hr/>";
			if ($paraules[0]=="IN" OR $paraules[0]=="A" OR $paraules[0]."."
.$paraules[1]==$domini OR $dom2==$domini){//és el domini mateix
				
					$registres[]=new Registre($domini,$ip,$domini);
									
				}
			else {
				$char = mb_substr($paraules[0], mb_strlen($paraules[0])-1, 1);
				
				//si acaba en punt està bé:
				if ($char==".")
				$registres[]=new Registre($paraules[0],$ip,$domini);
				//si no acaba en punt, li hem d'afegir el domini
				else $registres[]=new Registre($paraules[0].".".$domini,$ip,$domini);
				}
			} //if (in_array($trobar,$paraules
		
		}
		//var_dump($registres);
festaula($registres);
	
	}
	
function festaula($registres){
echo "<table>";
echo "<tr>";
echo "<th>Nom</th>"; echo "<th>https?</th>"; 
echo "<th>IP</th>"; 
//echo "<th>Estat</th>";
echo "</tr>";
foreach ($registres as $equip){
	//$serveis=getServeis($equip->_ip);
	echo "<tr>";
echo "<td><a href='http://".$equip->_nom."'>".$equip->_nom."</a></td>"; 
echo "<td><a href='https://".$equip->_nom."'>https://".$equip->_nom."</a></td>";
echo "<td>".$equip->_ip."</td>"; //echo "<td>".$serveis."</td>";
echo "</tr>";
	}
echo"</table><br/>";	
}

function getServeis($ip){
$torna="-";
$ip="127.0.0.1";//per fer proves
 $ping_ex = exec("/bin/ping -n 1 127.0.0.1", $ping_result, $pr);
if (count($ping_result) > 1){
$torna="alive";
} 
/*
$ports=array(53=>"DNS",80=>"HTTP", 443=>"HTTPS", 25=>"SMTP" ,143=>"IMAP" );
	
	$primer=true;
	if (@fsockopen("tcp://".$ip,80,$errno,$errstr)) $torna="Web";
	/*
	foreach ($ports as $port=>$servei)
	{
	$ip="173.194.41.223";
	$fp = @fsockopen("tcp://".$ip,$port,$errno,$errstr,10);
	if ($fp AND $primer) {$torna=$servei;$primer=false;}
	if ($fp AND !$primer) $torna.=" - ".$servei;
	}*/
	return $torna;
	}

function getIP($reg)
{
	//$tros=explode(" ",$reg);
	//return $tros[count($tros)-1];
	preg_match('/\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}/', $reg, $matches);
	return $matches[0];
}
?>
