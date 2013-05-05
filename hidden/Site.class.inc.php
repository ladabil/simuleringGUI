<?php

require_once($GLOBALS["cfg_hiddendir"] . "/MySmarty.class.inc.php");

class Site 
{
	public static $funcShowDefault = "showDefault";
	public static $funcLogin = "login";
	public static $funcSetupEnergySimulator = "setupEnergySimulator";
	
	static function parseRequest()
	{
		$function = NULL;
	
		if ( isset($_REQUEST['function']) && strlen($_REQUEST['function']) > 0 )
		{
			$function = $_REQUEST['function'];
		}
	
		if ( isset($_REQUEST["infoMessage"]) && strlen($_REQUEST["infoMessage"]) > 0 )
		{
			static::setInfoMessage($_REQUEST["infoMessage"]);
		}
	
		switch ( $function )
		{
			case static::$funcLogin:
				echo static::processLogin();
				break;
			case static::$funcSetupEnergySimulator:
				echo static::setupSimulator();
				break;
			default:
			case static::$funcShowDefault:
				echo static::showDefault();
				break;
		}
	}	
	
	static function logMeOut()
	{
		if ( !isset($GLOBALS["authlib"]) || !is_object($GLOBALS["authlib"]) )
		{
			die("Logout failed");
			return FALSE;
		}
	
		$GLOBALS["authlib"]->deleteSession();
		Base::redirectNow("showDefault");
	}	
	
	static function getLoginForm()
	{
		$tpl = new MySmarty();
		
		if ( isset($_REQUEST["alUsername"]) && strlen($_REQUEST["alUsername"]) > 0 )
		{
			$tpl->assign("alUsername", $_REQUEST["alUsername"]);
		}
		else
		{
			$tpl->assign("alUsername", "");
		}
		
		$tpl->assign("function", static::$funcLogin);
		
		return static::getMainFrame($tpl->fetch("loginForm.tpl.html"), "Login");
	}
	
	static function processLogin()
	{
		if (
				(!isset($_REQUEST['alUsername']) || strlen($_REQUEST['alUsername']) <= 0)
				|| (!isset($_REQUEST['alPassword']) || strlen($_REQUEST['alPassword']) <= 0)
		) {
			return static::getLoginForm();
		}
		elseif ( !$GLOBALS["authlib"]->processLogin() )
		{
			static::setInfoMessage($GLOBALS["authlib"]->getStatusMessage());
			return static::getLoginForm();
			die();
		}
		else
		{
			Base::redirectNow(static::$funcShowDefault);
			die();
		}
	}
		
	static function showDefault()
	{
		return static::getEnergyWizard();
	}
	
	static function getMainFrame($content, $title="untitled")
	{
		$tpl = new MySmarty();
	
		$tpl->assign('content', $content);
		$tpl->assign('title', $title);
	
		$tpl->assign('showLoginInfo', TRUE);
	
		$tpl->assign('infoMessage', static::getInfoMessage());
		$tpl->assign('logMessages', ob_get_clean());
	
		return $tpl->fetch("main.tpl.html");
	}
	
	static function getInfoMessage()
	{
		if ( !isset($GLOBALS["siteInfoMessage"]) )
		{
			static::setInfoMessage("");
		}
		
		return $GLOBALS["siteInfoMessage"];
	}
	
	static function setInfoMessage($value)
	{
		$GLOBALS["siteInfoMessage"] = $value;
	}
	//
	static function addInfoMessage($value)
	{
		if ( !isset($GLOBALS["siteInfoMessage"]) )
		{
			static::setInfoMessage("");
		}
		
		$GLOBALS["siteInfoMessage"] .= $value;
	}
	
	static function getEnergyWizard($enegrySimulator = NULL)
	{
		require_once($GLOBALS["cfg_hiddendir"] . "/EnegrySimulator.class.inc.php");
		
		if ( $enegrySimulator == NULL )
		{
			$enegrySimulator = new EnegrySimulator();
		}
			
		$tpl = new MySmarty();
		
		$tpl->assign('enegrySimulator', $enegrySimulator);
		$tpl->assign('inhabitantWorkTypesArr', $enegrySimulator->getInhabitantWorkTypesAsArray());
		$tpl->assign('function', static::$funcSetupEnergySimulator);
		
		return static::getMainFrame($tpl->fetch("wizard.tpl.html"), "Wizard");
	}
	
	static function setupSimulator()
	{
		require_once($GLOBALS["cfg_hiddendir"] . "/EnegrySimulator.class.inc.php");
		
		$tpl = new MySmarty();
		
		$es = new EnegrySimulator();
		$errMsg = "";
		
		// Spesifiser byggnings type
		if ( isset($_REQUEST['byggType']) && intval($_REQUEST['byggType']) > 0 )
		{
			$es->_buildning = intval($_REQUEST['byggType']);
		}
		else
		{
			// Default 1 (Enebolig)
			$es->_buildning = 1;
		}
		
		if ( isset($_REQUEST['antall_i_hus']) && intval($_REQUEST['antall_i_hus']) > 0 )
		{
			$es->_numPersons = intval($_REQUEST['antall_i_hus']);
		}
		else
		{
			// Default 1 person
			$es->_numPersons = 1;
		}
		
		if ( isset($_REQUEST['gjen_alder']) && intval($_REQUEST['gjen_alder']) > 0 )
		{
			$es->_personsAvgAge = intval($_REQUEST['gjen_alder']);
		}
		else
		{
			// Default 35 år
			$es->_personsAvgAge = 35;
		}
		
		if ( isset($_REQUEST['byggaar']) && intval($_REQUEST['byggaar']) > 0 )
		{
			$es->_houseBuildYear = intval($_REQUEST['byggaar']);
		}
		else
		{
			// Default 35 år
			$es->_houseBuildYear = 1980;
		}
		
		if ( isset($_REQUEST['priVarme']) && intval($_REQUEST['priVarme']) > 0 )
		{
			$es->_priHeat = intval($_REQUEST['priVarme']);
		}
		else
		{
			// Default 1
			$es->_priHeat = 1;
		}
				
		if ( isset($_REQUEST['klima']) && intval($_REQUEST['klima']) > 0 )
		{
			$es->_climateZone = intval($_REQUEST['klima']);
		}
		else
		{
			// Default 1 (Sør-norge?)
			$es->_climateZone = 1;
		}
		
		// Brutto Areal
		if ( isset($_REQUEST['houseTotalArea']) && intval($_REQUEST['houseTotalArea']) > 0 )
		{
			$es->_houseTotalArea = intval($_REQUEST['houseTotalArea']);
		}
		else
		{
			$errMsg .= "Mangler Brutto Areal<br>\n";
		}
		
		// Primær Areal
		if ( isset($_REQUEST['housePrimaryArea']) && intval($_REQUEST['housePrimaryArea']) > 0 )
		{
			$es->_housePrimaryArea = intval($_REQUEST['housePrimaryArea']);
		}
		else
		{
			$errMsg .= "Mangler Primær Areal<br>\n";
		}
		
		if ( intval($_REQUEST['housePrimaryArea']) > intval($_REQUEST['houseTotalArea']) )
		{
			$errMsg .= "Primær Areal kan ikke være større enn bruttoareal<br>\n";
		}

		// Antall beboere og type tidsfordiv
		if ( isset($_REQUEST['inhabitantsAge'])
				&& isset($_REQUEST['inhabitantsWork'])
				&& count($_REQUEST['inhabitantsAge']) == count($_REQUEST['inhabitantsWork'])
		) {
			$es->_inhabitantsWork = $_REQUEST['inhabitantsWork'];
			$es->_inhabitantsAge = $_REQUEST['inhabitantsAge'];
		}
		else
		{
			$errMsg .= "Mangler beboere og deres yrker..<br>\n";
		}

		// Lyskilder
		
		if ( isset($_REQUEST['belysningstype']) && intval($_REQUEST['belysningstype']) > 0 )
		{
			$es->_lightType = intval($_REQUEST['belysningstype']);
		}
		else
		{
			// Default 60 (Glødepære)
			$es->_lightType = 60;
		}
		
		// Lyskilder
		
		if ( isset($_REQUEST['belysningstype']) && intval($_REQUEST['belysningstype']) > 0 )
		{
			$es->_lightType = intval($_REQUEST['belysningstype']);
		}
		else
		{
			// Default 60 (Glødepære)
			$es->_lightType = 60;
		}
		
		if ( isset($_REQUEST['antall_lyskilder']) && intval($_REQUEST['antall_lyskilder']) > 0 )
		{
			$es->_numLys = intval($_REQUEST['antall_lyskilder']);
		}
		else
		{
			// Default 2 lyskilder
			$es->_numLys = 2;
		}
		
		if ( isset($_REQUEST['antall_hvitvarer']) && intval($_REQUEST['antall_hvitvarer']) > 0 )
		{
			$es->_numHvit = intval($_REQUEST['antall_hvitvarer']);
		}
		else
		{
			// Default 2 hvitevarer
			$es->_numHvit = 2;
		}
		
		if ( isset($_REQUEST['antall_brunevarer']) && intval($_REQUEST['antall_brunevarer']) > 0 )
		{
			$es->_numBrun = intval($_REQUEST['antall_brunevarer']);
		}
		else
		{
			// Default 2 brunevarer
			$es->_numBrun = 2;
		}
		
		
		if ( strlen($errMsg) > 0 )
		{
			static::addInfoMessage($errMsg);
			return static::getEnergyWizard($es);
		}
		
		// antall pers * (watt lys * antall) + (normatall oppvarming klimasone 97 mod * total areal) / 1000 (kw) * 12 timer i døgnet * dager i året
		//$tmpResult = (($es->_numPersons*($es->_lightType*$es->_numLys)) + ($es->_climateZone*$es->_houseTotalArea) + (($es->_numHvit*50) + $es->_numBrun*25)) / 1000 *(12*365);
		
		// eks:
		// antall i huset * styrke lys * antall lys * 12 timer i dÃ¸gnet * dager i Ã¥ret
		// anna ikke kordan man regna ut dettan doh.........
		// Omregner så til kWh --> antall i huset * styrke lys * antall lys / 1000 --> * 12 timer i døgnet * dager i året
		$tmpResult = ($es->_numPersons*($es->_lightType*$es->_numLys))/ 1000 *(12*365);
		
		return static::getEnergyWizard($tmpResult);
	}
}
