<?php

class EnegrySimulator
{
	var $_inhabitantsWork = Array();
	var $_inhabitantsAge = Array();
	
	var $_houseTotalArea = 0;
	var $_housePrimaryArea = 0;

	var $_numLys = 0;
	var $_lightType = 0;
	
	var $_climateZone = 0;
	var $_numHvit = 0;
	var $_numBrun = 0;
	
	function __construct()
	{
		
		
	}
	
	function getEnergyUsage()
	{
		// antall pers * (watt lys * antall) + (normatall oppvarming klimasone 97 mod * total areal) / 1000 (kw) * 12 timer i døgnet * dager i året
		//$tmpResult = (($this->_numPersons*($this->_lightType*$this->_numLys)) + ($this->_climateZone*$this->_houseTotalArea) + (($this->_numHvit*50) + $this->_numBrun*25)) / 1000 *(12*365);
		
		return ((count($this->_inhabitantsAge)*($this->_lightType*$this->_numLys)) + ($this->_climateZone*$this->_houseTotalArea) + (($this->_numHvit*50) + $this->_numBrun*25)) / 1000 *(12*365);
		
		// eks:
		// antall i huset * styrke lys * antall lys * 12 timer i dÃ¸gnet * dager i Ã¥ret
		// anna ikke kordan man regna ut dettan doh.........
		// Omregner så til kWh --> antall i huset * styrke lys * antall lys / 1000 --> * 12 timer i døgnet * dager i året
		return (count($this->_inhabitantsAge)*($this->_lightType*$this->_numLys))/ 1000 *(12*365);		
	}
	
	static function getInhabitantWorkTypesAsArray()
	{
		$workType[-1] = "--- Velg yrke ---";
		$workType[2] = "Barnehage/Skole";
		$workType[9] = "Arbeid";
		$workType[24] = "Uf&oslash;retrygdet/Pensjonist";

		return $workType;
	}
}

?>