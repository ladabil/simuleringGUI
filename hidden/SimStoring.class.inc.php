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
	
	public function __construct($this->_row)
	{
		$this->_row = $this->_row;
		$this->parseRow();
	}
	
	public function parseRow()
	{
		$this->_name = $this->_row['name'];
		$this->_priHeat = $this->_row['priHeat'];
		$this->_secHeat = $this->_row['secHeat'];
		$this->_houseTotalArea = $this->_row['houseTotalArea'];
		$this->_housePrimaryArea = $this->_row['housePrimaryArea'];
		$this->_houseBuildYear = $this->_row['houseBuildYear'];
		
		$this->_houseBuildYearParsed = 0;
		
		$this->_ytterveggAreal = $this->_row['ytterveggAreal'];
		$this->_yttertakAreal = $this->_row['yttertakAreal'];
		$this->_vinduDorAreal = $this->_row['vinduDorAreal'];
		$this->_luftVolum = $this->_row['luftVolum'];
		$this->_onsketTemp = $this->_row['onsketTemp'];
		
		$this->_heatDiff = $this->_row['heatDiff'];
		$this->_floorHeatWa = $this->_row['floorHeatWa'];
		$this->_floorHeatEl = $this->_row['floorHeatEl'];
		$this->_priBoilerSize = $this->_row['priBoilerSize'];
		$this->_priBoilerPower = $this->_row['priBoilerPower'];
		$this->_numLight = $this->_row['numLight'];
		$this->_priLightType = $this->_row['priLightType'];
		$this->_secLightType = $this->_row['secLightType'];
		$this->_lightTime = $this->_row['lightTime'];
		$this->_lightDiff = $this->_row['lightDiff'];
		$this->_numHvit = $this->_row['numHvit'];
		$this->_numBrun = $this->_row['numBrun'];
		
		$this->_startTime = $this->_row['startTime'];
		$this->_endTime = $this->_row['endTime'];
		$this->_opplosning = $this->_row['opplosning'];		

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
		
		
		$this->_climateZone = $this->_row['climateZone'];
		$this->_climateTemperatureOffset = $this->_row['climateTemperatureOffset'];
		$this->_climateWeatherStation = $this->_row['climateWeatherStation'];
		
		$this->_climateZone = $this->_row['climateZone'];
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
