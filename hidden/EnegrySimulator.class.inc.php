<?php

class EnegrySimulator
{
	var $_inhabitantsWork = Array();
	var $_inhabitantsAge = Array();
	
	function __construct()
	{
		
		
	}
	
	static function getInhabitantWorkTypesAsArray()
	{
		$workType[-1] = "--- Velg yrke ---";
		$workType[2] = "Barnehage/Skole";
		$workType[9] = "Arbeid";
		$workType[24] = "Uføretrygdet/Pensjonist";

		return $workType;
	}
}

?>