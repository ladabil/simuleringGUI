<?php
include("../hidden/config.inc.php");

class EnergySimulator
{
	var $_inhabitantsWork = Array();
	var $_inhabitantsAge = Array();
	
	var $_houseTotalArea = 0;
	var $_housePrimaryArea = 0;
	
	var $_priHeat = 0;
	var $_secHeat = 0;
	var $_heatDiff = 0;
	var $_floorHeatWa = 0;
	var $_floorHeatEl = 0;
	var $_priBoilerSize = 0;
	var $_priBoilerPower = 0;
	var $_secBoilerSize = 0;
	var $_secBoilerPower = 0;
	

	var $_numLight = 0;
	var $_priLightType = 0;
	var $_secLightType = 0;
	var $_lightTime = 0;
	var $_lightDiff = 0;
	
	var $_climateZone = 0;
	var $_numHvit = 0;
	var $_numBrun = 0;
	
	var $_storage_name = "";
	
	function __construct()
	{
		
		
	}
	
	function getEnergyUsage()
	{
// 		antall pers * noe
// 		+ (watt prim�r belysning * (antall / prosentvis fordeling) * brenntid (timer/24) 
// 		+ (watt sekund�r belysning * (antall / prosentvis fordeling) * brenntid (timer/24)
// 		+ (normatall oppvarming klimasone 97 mod * total areal) 
// 		/ 1000 (kw) * 12 timer i d�gnet * dager i �ret for omregning til kWh
		//$tmpResult = (($this->_numPersons*($this->_lightType*$this->_numLys)) + ($this->_climateZone*$this->_houseTotalArea) + (($this->_numHvit*50) + $this->_numBrun*25)) / 1000 *(12*365);
		
		return ((count($this->_inhabitantsAge)) * 1
					+ (($this->_priBoilerPower * 12) + ($this->_secBoilerPower * 12) + ($this->_floorHeatEl * 8))
					+ ((($this->_priHeat)/100) * (100 - ($this->_heatDiff))) + ((($this->_secHeat)/100) * ($this->_heatDiff))
				 	+ ($this->_priLightType * ($this->_numLight / ((100 - $this->_lightDiff) / 100)) * ($this->_lightTime /24)) 
// 					+ ($this->_secLightType * ($this->_numLight / ($this->_lightDiff) / 100) * ($this->_lightTime / 24)) <-- mangler sjekk for 0, gir feilmedling kan ikke dele 0 p�
					+ ($this->_climateZone * $this->_houseTotalArea) 
					+ (($this->_numHvit*50) + $this->_numBrun*25)) 
					/ 1000 *(12*365);
		
		// eks:
		// antall i huset * styrke lys * antall lys * 12 timer i døgnet * dager i året
		// anna ikke kordan man regna ut dettan doh.........
		// Omregner s� til kWh --> antall i huset * styrke lys * antall lys / 1000 --> * 12 timer i d�gnet * dager i �ret
		return (count($this->_inhabitantsAge)*($this->_priLightType*$this->_numLight))/ 1000 *(12*365);		
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