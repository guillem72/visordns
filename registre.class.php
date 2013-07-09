<?php
class Registre{

public $_nom;

public $_ip;

public $_domini;

public $_tipus;
//Constructor
public function Registre($nom,$ip,$domini,$tipus="A"){
	$this->_nom=$nom;
	$this->_ip=$ip;
	$this->_domini=$domini;	
	$this->_tipus=$tipus;
	}

}
?>
