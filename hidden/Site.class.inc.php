<?php

require_once($GLOBALS["cfg_hiddendir"] . "/MySmarty.class.inc.php");

class Site 
{
	public static $funcShowDefault = "showDefault";
	public static $funcLoginForm = "loginForm";
	public static $funcLogin = "login";
	public static $funcLogout = "logout";
	public static $funcSetupEnergySimulator = "setupEnergySimulator";
	
	static function parseRequest()
	{
		$function = NULL;
	
		if ( isset($_REQUEST['function']) && strlen($_REQUEST['function']) > 0 )
		{
			$function = $_REQUEST['function'];
		}
		
		// Spesialh�ndtering av loginfunksjon..
		if ( strcmp($function, static::$funcLogin) == 0 )
		{
			static::processLogin();
			die();
		}
		else if ( strcmp($function, static::$funcLoginForm) == 0 )
		{
			// Og loginskjema..
			echo static::getLoginForm();
			die();
		}

		// Sjekk om brukeren er autentisert..
		if ( !AuthLib::checkSession() )
		{
			static::setInfoMessage(AuthLib::getStatusMessage());
			echo static::getLoginForm();
			die();
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
			case static::$funcLogout:
				echo static::logMeOut();
				break;
			case static::$funcSetupEnergySimulator:
				echo static::setupSimulator();
				break;
			default:
			case static::$funcShowDefault:
				if ( AuthLib::isUser() )
				{
					echo static::showDefault();
				}
				else if ( AuthLib::isAdmin() )
				{
					$content = "Admin siden kommer i l�pet av Sprint 3<br>";

					echo static::getMainFrame($content, "Admin-side");
				}
				break;
		}
	}	
	
	static function logMeOut()
	{
		AuthLib::processLogout();
		Base::redirectNow(static::$funcLoginForm);
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
		elseif ( !AuthLib::processLogin() )
		{
			static::setInfoMessage(AuthLib::getStatusMessage());
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
		$tpl->assign('inhabitantWorkTypesArr', EnegrySimulator::getInhabitantWorkTypesAsArray());
		$tpl->assign('function', static::$funcSetupEnergySimulator);
		
		return static::getMainFrame($tpl->fetch("wizard.tpl.html"), "Wizard");
	}
	
	static function setupSimulator()
	{
		require_once($GLOBALS["cfg_hiddendir"] . "/EnegrySimulator.class.inc.php");
		
		$tpl = new MySmarty();
		
		$es = new EnegrySimulator();
		$errMsg = "";

		// Verifiser token f�rst..
		Base::verifyTokenFromRequest("setupSimulator");
		
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
			// Default 35 �r
			$es->_personsAvgAge = 35;
		}
		
		if ( isset($_REQUEST['byggaar']) && intval($_REQUEST['byggaar']) > 0 )
		{
			$es->_houseBuildYear = intval($_REQUEST['byggaar']);
		}
		else
		{
			// Default 35 �r
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
			// Default 1 (S�r-norge?)
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
		
		// Prim�r Areal
		if ( isset($_REQUEST['housePrimaryArea']) && intval($_REQUEST['housePrimaryArea']) > 0 )
		{
			$es->_housePrimaryArea = intval($_REQUEST['housePrimaryArea']);
		}
		else
		{
			$errMsg .= "Mangler Prim�r Areal<br>\n";
		}
		
		if ( intval($_REQUEST['housePrimaryArea']) > intval($_REQUEST['houseTotalArea']) )
		{
			$errMsg .= "Prim�r Areal kan ikke v�re st�rre enn bruttoareal<br>\n";
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
			// Default 60 (Gl�dep�re)
			$es->_lightType = 60;
		}
		
		// Lyskilder
		
		if ( isset($_REQUEST['belysningstype']) && intval($_REQUEST['belysningstype']) > 0 )
		{
			$es->_lightType = intval($_REQUEST['belysningstype']);
		}
		else
		{
			// Default 60 (Gl�dep�re)
			$es->_lightType = 60;
		}
		
		if ( isset($_REQUEST['antall_lyskilder']) && intval($_REQUEST['antall_lyskilder']) >= 0 )
		{
			$es->_numLys = intval($_REQUEST['antall_lyskilder']);
		}
		else
		{
			// Default 2 lyskilder
			$es->_numLys = 2;
		}
		
		if ( isset($_REQUEST['antall_hvitevarer']) && intval($_REQUEST['antall_hvitevarer']) >= 0 )
		{
			$es->_numHvit = intval($_REQUEST['antall_hvitevarer']);
		}
		else
		{
			// Default 2 hvitevarer
			$es->_numHvit = 2;
		}
		
		if ( isset($_REQUEST['antall_brunevarer']) && intval($_REQUEST['antall_brunevarer']) >= 0 )
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
		
		return static::getEnergyWizard($es);
	}
}
