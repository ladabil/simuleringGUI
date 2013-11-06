<?php

class SimStoring
{
	var $_row;
	
	var $_climateTemperatureOffset = NULL;
	var $_climateWeatherStation = NULL;
	
	var $_climateZone = NULL;
	var $_climateZoneTxt = NULL;
	
	// kj�rer database kolonne-resultat til variabler
	var $_name = NULL;
	var $_priHeat = NULL;
	var $_secHeat = NULL;
	var $_houseTotalArea = NULL;
	var $_housePrimaryArea = NULL;
	var $_houseBuildYear = NULL;
		
	var $_houseBuildYearParsed = NULL;

	var $_ytterveggAreal = NULL;
	var $_yttertakAreal = NULL;
	var $_vinduDorAreal = NULL;
	var $_luftVolum = NULL;
	var $_onsketTemp = NULL;
		
	var $_heatDiff = NULL;
	var $_floorHeatWa = NULL;
	var $_floorHeatEl = NULL;
	var $_priBoilerSize = NULL;
	var $_priBoilerPower = NULL;
	var $_numLight = NULL;
	var $_priLightType = NULL;
	var $_secLightType = NULL;
	var $_lightTime = NULL;
	var $_lightDiff = NULL;
	var $_numHvit = NULL;
	var $_numBrun = NULL;
		
	var $_startTime = NULL;
	var $_endTime = NULL;
	var $_opplosning = NULL;
	
	public function __construct($row)
	{
		$this->_row = $row;
		$this->parseRow();
	}
	
	public function parseRow()
	{
		$this->_name = $row['name'];
		$this->_priHeat = $row['priHeat'];
		$this->_secHeat = $row['secHeat'];
		$this->_houseTotalArea = $row['houseTotalArea'];
		$this->_housePrimaryArea = $row['housePrimaryArea'];
		$this->_houseBuildYear = $row['houseBuildYear'];
		
		$this->_houseBuildYearParsed = 0;
		
		$this->_ytterveggAreal = $row['ytterveggAreal'];
		$this->_yttertakAreal = $row['yttertakAreal'];
		$this->_vinduDorAreal = $row['vinduDorAreal'];
		$this->_luftVolum = $row['luftVolum'];
		$this->_onsketTemp = $row['onsketTemp'];
		
		$this->_heatDiff = $row['heatDiff'];
		$this->_floorHeatWa = $row['floorHeatWa'];
		$this->_floorHeatEl = $row['floorHeatEl'];
		$this->_priBoilerSize = $row['priBoilerSize'];
		$this->_priBoilerPower = $row['priBoilerPower'];
		$this->_numLight = $row['numLight'];
		$this->_priLightType = $row['priLightType'];
		$this->_secLightType = $row['secLightType'];
		$this->_lightTime = $row['lightTime'];
		$this->_lightDiff = $row['lightDiff'];
		$this->_numHvit = $row['numHvit'];
		$this->_numBrun = $row['numBrun'];
		
		$this->_startTime = $row['startTime'];
		$this->_endTime = $row['endTime'];
		$this->_opplosning = $row['opplosning'];		

		switch ( $this->_houseBuildYear )
		{
			case 1:
				$this->_houseBuildYearParsed = 1985;
				break;
			case 2:
				$this->_houseBuildYearParsed = 1995;
				break;
			case 3:
				$this->_houseBuildYearParsed = 2005;
				break;
			default:
				$this->_houseBuildYearParsed = $this->_houseBuildYear;
				break;
		}
		
		
		$this->_climateZone = $row['climateZone'];
		$this->_climateTemperatureOffset = $row['climateTemperatureOffset'];
		$this->_climateWeatherStation = $row['climateWeatherStation'];
		
		$this->_climateZone = $row['climateZone'];
		$this->_climateZoneTxt = "";
		
		
		if ( intval($this->_climateWeatherStation) > 0 )
		{
			$this->_climateWeatherStationTxt = Site::hentVaerstasjonsNavn($this->_climateWeatherStation);
		}
		else
		{
			$this->_climateWeatherStationTxt = "ikke valgt, benytter klimasone";
		}
		
		
		if ( intval($this->_climateZone) <= 0 || intval($this->_climateZone) > 7 )
		{
			// Default klimasone er 1 -> S�r-norge
			$this->_climateZone = 1;
		}
		
		switch ( $this->_climateZone )
		{
			case 1:
			default:
				$this->_climateZoneTxt = "Sør-Norge, kyst";
				break;
			case 2:
				$this->_climateZoneTxt = "Sør-Norge, innland";
				break;
			case 3:
				$this->_climateZoneTxt = "Sør-Norge, høyfjell";
				break;
			case 4:
				$this->_climateZoneTxt = "Midt-Norge, kyst";
				break;
			case 5:
				$this->_climateZoneTxt = "Midt-Norge, innland";
				break;
			case 6:
				$this->_climateZoneTxt = "Nord-Norge, kyst";
				break;
			case 7:
				$this->_climateZoneTxt = "Finnmark og innland Troms";
				break;
		}		
	}
}
