<?php

require_once($GLOBALS["cfg_hiddendir"] . "/MySmarty.class.inc.php");

class Site 
{
	public static $funcShowDefault = "showDefault";
	public static $funcLoginForm = "loginForm";
	public static $funcLogin = "login";
	public static $funcLogout = "logout";
	public static $funcSetupEnergySimulator = "setupEnergySimulator";
	
	public static $funcShowUserDefault = "showUserDefault";
	public static $funcStartWizard = "startNewWizard";
	public static $funcShowWizardBuilding = "showWizBuilding";
	public static $funcParseWizardBuilding = "parseWizBuilding";
	public static $funcShowWizardHeat = "showWizHeat";
	public static $funcParseWizardHeat = "parseWizHeat";
	public static $funcShowWizardLight = "showWizLight";
	public static $funcParseWizardLight = "parseWizLight";
	public static $funcShowWizardInhabitants = "showWizInhabitants";
	public static $funcParseWizardInhabitants = "parseWizInhabitants";
	public static $funcShowWizardClimateZone = "showWizClimateZone";
	public static $funcParseWizardClimateZone = "parseWizClimateZone";
	public static $funcShowWizardResult = "showWizResult";
	
	public static $funcShowAdminDefault = "showAdminDefault";
	public static $funcShowUserMenu = "showUserMenu";
	public static $funcCreateNewUser =  "createNewUser";
	public static $funcCreateNewUserForm = "createNewUserForm";
	public static $funcDeleteUser = "deleteUser";
	
	public static $doDebug = FALSE;
	
	static function parseRequest()
	{
		$function = NULL;
	
		if ( isset($_REQUEST['function']) && strlen($_REQUEST['function']) > 0 )
		{
			$function = $_REQUEST['function'];
		}
		
		// Spesialhåndtering av loginfunksjon..
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
					echo static::showUserDefault();
				}
				else if ( AuthLib::isAdmin() )
				{
					echo static::showAdminDefault();
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
			Base::redirectNow(static::$funcShowUserDefault);
			die();
		}
	}
		
	static function showDefault()
	{
		return static::getEnergyWizard();
	}
	
	static function wizardInit()
	{
		if ( !isset($_SESSION["es"]) || !is_object($_SESSION["es"]) )
		{
			die("Invalid ES in session");
		}
	
		$tpl = new MySmarty();
	
		$tpl->assign('EnergySimulator', $_SESSION['es']);
	
		return $tpl;
	}
	
	static function showUserDefault()
	{
		if ( AuthLib::isUser() )
		{
			$function = NULL;
				
			if ( isset($_REQUEST['function']) && strlen($_REQUEST['function']) > 0 )
			{
				$function = $_REQUEST['function'];
			}
	
			$tpl = new MySmarty();
	
			switch ( $function )
			{
				case static::$funcStartWizard:
					echo static::startNewWizard();
					break;
				case static::$funcShowWizardBuilding:
					echo static::showWizBuilding();
					break;
				case static::$funcParseWizardBuilding:
					echo static::parseWizBuilding();
					break;
				case static::$funcShowWizardHeat:
					echo static::showWizHeat();
					break;
				case static::$funcParseWizardHeat:
					echo static::parseWizHeat();
					break;
 				case static::$funcShowWizardLight:
 					echo static::showWizLight();
 					break;
 				case static::$funcParseWizardLight:
 					echo static::parseWizLight();
 					break;
 				case static::$funcShowWizardInhabitants:
 					echo static::showWizInhabitants();
 					break;
 				case static::$funcParseWizardInhabitants:
 					echo static::parseWizInhabitants();
 					break;
 				case static::$funcShowWizardClimateZone:
 					echo static::showWizClimateZone();
 					break;
 				case static::$funcParseWizardClimateZone:
 					echo static::parseWizClimateZone();
 					break;
 				case static::$funcShowWizardResult:
 					echo static::showWizResult();
 					break;
				default:
					return static::getMainFrame($tpl->fetch("userMain.tpl.html"), "Energi simulatoren");
					break;
			}
	
		}
		else
		{
			return static::logMeOut();
		}
	
	}
	
	static function showAdminDefault()
	{
		if ( AuthLib::isAdmin() )
		{
			$function = NULL;
			
			if ( isset($_REQUEST['function']) && strlen($_REQUEST['function']) > 0 )
			{
				$function = $_REQUEST['function'];
			}
		
			$tpl = new MySmarty();
		
			switch ( $function )
			{
				case static::$funcShowUserMenu:
					echo static::showUserMenu();
					break;
 				case static::$funcCreateNewUser:
 					echo static::createNewUser();
 					break;
 				case static::$funcDeleteUser:
 					echo static::deleteUser();
 					break;
// 				case static::$funcKeyNumbers:
// 					echo static::logMeOut();
// 					break;
// 				case static::$funcLog:
// 					echo static::logMeOut();
// 					break;
				default:
					return static::getMainFrame($tpl->fetch("adminMain.tpl.html"), "Admin-side");
					break;
			}
		
		}
		else 
		{
			return static::logMeOut();
		}
		
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
	
	static function showUserMenu()
	{
		require_once (dirname(__FILE__) . "/AuthLibUser.class.inc.php"); 
		
		$tpl = new MySmarty();
		
		$query = "SELECT
						`" . AuthLibUser::$tableName . "`.*
					FROM
						`" . AuthLibUser::$tableName . "`
					";
		
		$registeredUsers = new AuthLibUser();
		
		$where = "";
		if ( strlen($where) > 0 )
		{
			$query .= " WHERE " . $where . " ";
		}
		
// 		echo $query;
		
		if ( ($res = Base::getMysqli()->query($query)) === FALSE )
		{
			die(Base::getMysqli()->error);
		}
		
		$userArr = Array();
		
		for ( $i=0; $i<$res->num_rows; $i++)
		{
		$registeredUsers = new AuthLibUser();
		$registeredUsers->setFromResult($res, $i);
		$userArr[] = $registeredUsers;
		}
		
// 		print_r($userArr);
		
		$tpl->assign('userArr', $userArr);
	
		return static::getMainFrame($tpl->fetch("adminUserMenu.tpl.html"), "Admin-side - Brukere");
	}
	
	static function createNewUser()
	{
		require_once(dirname(__FILE__) . "/AuthLib.class.inc.php");
		require_once(dirname(__FILE__) . "/AuthLibUser.class.inc.php");
		
		$tpl = new MySmarty();
		$tpl->assign('function', static::$funcCreateNewUser);
		
		$errMsg = "";
		$allVarsSet = TRUE;
		
		/* sjekk lengde navn */
		if (isset($_REQUEST["alName"]) )
		{
			$tpl -> assign("alName", $_REQUEST["alName"]);
			
			if ( strlen($_REQUEST["alName"]) < 6 )
			{
				$errMsg .= "Navnet må være mer en 6 tegn<br>\n";
			}
		}
		else
		{
			$tpl -> assign("alName", "");
			$allVarsSet = FALSE;
		}
		
		/* sjekk lengde brukernavn */
		if (isset($_REQUEST["alUsername"]) )
		{
			$tpl -> assign("alUsername", $_REQUEST["alUsername"]);
				
			if ( strlen($_REQUEST["alUsername"]) < 4 )
			{
				$errMsg .= "Brukernavnet må være mer en 4 tegn<br>\n";
			}
		}
		else
		{
			$tpl -> assign("alUsername", "");
			$allVarsSet = FALSE;
		}
		
		/* sjekk lengde e-post addresse */
		if (isset($_REQUEST["alEmail"]) )
		{
			$tpl -> assign("alEmail", $_REQUEST["alEmail"]);
				
			if ( strlen($_REQUEST["alEmail"]) < 6 || substr_count($_REQUEST["alEmail"], '@') !== 1 )
			{
				$errMsg .= "Feil i e-post addressen<br>\n";
			}
		}
		else
		{
			$tpl -> assign("alEmail", "");
			$allVarsSet = FALSE;
		}
		
		/* sjekk passordlengde og rett skrevet */
		if (isset($_REQUEST["alPassword"]) && isset($_REQUEST["alPassword2"]) )
		{
			if ( strlen($_REQUEST["alPassword"]) < 8 )
			{
				$errMsg .= "Passordet må være mer enn 8 tegn<br>\n";
			}
			else if ( strcmp($_REQUEST["alPassword"], $_REQUEST["alPassword2"]) != 0) 
			{
				$errMsg .= "Passordene er ikke like, forsøk igjen<br>\n";
			}
		}
		else
		{
			$allVarsSet = FALSE;
		}
		
		/*
		 * Register bruker viss kontrollen er OK
		 */
		if ( strlen($errMsg) <= 0 && $allVarsSet == TRUE)
		{
			if ( AuthLib::registerUser($_REQUEST["alUsername"], $_REQUEST["alPassword"], $_REQUEST["alName"], $_REQUEST["alEmail"] ) !== TRUE )
			{
				$errMsg = "Registrering feilet.." ;
			}
			else 
			{
				Base::redirectNow(static::$funcShowUserMenu
									,Array(
											"infoMessage"=>"Registrering OK"
											)
								);
				die('Registrering OK');
			}
		}
		
		static::setInfoMessage($errMsg);
		
		return static::getMainFrame($tpl->fetch("adminCreateUser.tpl.html"), "Admin-side - Brukere");
	}
	
	static function deleteUser()
	{
		require_once(dirname(__FILE__) . "/Base.class.inc.php");
		require_once(dirname(__FILE__) . "/AuthLibUser.class.inc.php");
		
		if ( intval($_REQUEST["userId"]) <= 1 )
		{
			Base::redirectNow(static::$funcShowUserMenu, Array("infoMessage"=>"Kan ikke slette bruker"));
			die("Dette er ikke lov");
		}
		
		$alu = new AuthLibUser($_REQUEST["userId"]);
		$alu->delete();
		
		Base::redirectNow(static::$funcShowUserMenu
							,Array(
								"infoMessage"=>"Brukeren ble slettet"
								,"userId"=>$alu->getDbId()
							)
						);
		die('Slett bruker OK');
		
	} 
	
	static function startNewWizard()
	{
		require_once($GLOBALS["cfg_hiddendir"] . "/EnergySimulator.class.inc.php");
		
		$tpl = new MySmarty();
		
		$_SESSION['es'] = new EnergySimulator();
		$errMsg = "";
		
		return static::showWizBuilding();
		
	}
	
	static function showWizBuilding()
	{
		require_once($GLOBALS["cfg_hiddendir"] . "/EnergySimulator.class.inc.php");
			
		$tpl = static::wizardInit();
		$tpl->assign('function', static::$funcParseWizardBuilding);
	
		return static::getMainFrame($tpl->fetch("wizard_Building.tpl.html"), "Wizard");
	}
	
	static function parseWizBuilding()
	{
		require_once($GLOBALS["cfg_hiddendir"] . "/EnergySimulator.class.inc.php");
		
		$errMsg = "";
		static::wizardInit();
		
		// Verifiser token først..
		Base::verifyTokenFromRequest("setupSimulator");
		
		// Spesifiser byggnings type
		if ( isset($_REQUEST['byggType']) && intval($_REQUEST['byggType']) > 0 )
		{
			$_SESSION['es']->_building = intval($_REQUEST['byggType']);
		}
		else
		{
			// Default 1 (Enebolig)
			$_SESSION['es']->_building = 1;
		}
		
		// Spesifiser byggeaar
		if ( isset($_REQUEST['byggaar']) && intval($_REQUEST['byggaar']) > 0 )
		{
			$_SESSION['es']->_houseBuildYear = intval($_REQUEST['byggaar']);
		}
		else
		{
			// Default byggår
			$_SESSION['es']->_houseBuildYear = 1980;
		}
		
		if ( isset($_REQUEST['klima']) && intval($_REQUEST['klima']) > 0 )
		{
			$_SESSION['es']->_climateZone = intval($_REQUEST['klima']);
		}
		else
		{
			// Default 1 (Sør-norge?)
			$_SESSION['es']->_climateZone = 1;
		}
		
		// Brutto Areal
		if ( isset($_REQUEST['houseTotalArea']) && intval($_REQUEST['houseTotalArea']) > 0 )
		{
			$_SESSION['es']->_houseTotalArea = intval($_REQUEST['houseTotalArea']);
		}
		else
		{
			$errMsg .= "Mangler Brutto Areal<br>\n";
		}
		
		// Primær Areal
		if ( isset($_REQUEST['housePrimaryArea']) && intval($_REQUEST['housePrimaryArea']) > 0 )
		{
			$_SESSION['es']->_housePrimaryArea = intval($_REQUEST['housePrimaryArea']);
		}
		else
		{
			$errMsg .= "Mangler Primær Areal<br>\n";
		}
		
		if ( intval($_REQUEST['housePrimaryArea']) > intval($_REQUEST['houseTotalArea']) )
		{
			$errMsg .= "Primær Areal kan ikke være større enn bruttoareal<br>\n";
		}
		
		if ( strlen($errMsg) > 0 )
		{
			static::addInfoMessage($errMsg);
			return static::showWizBuilding();
		}

		if ( static::$doDebug )
		{
			echo "<pre>\n";
			print_r($_SESSION['es']);
		}
		
		return static::showWizHeat();
	}
	
	static function showWizHeat()
	{
		require_once($GLOBALS["cfg_hiddendir"] . "/EnergySimulator.class.inc.php");

		$tpl = static::wizardInit();
		$tpl->assign('function', static::$funcParseWizardHeat);
		
		return static::getMainFrame($tpl->fetch("wizard_Heating.tpl.html"), "Wizard");
	}
	
	static function parseWizHeat()
	{
		require_once($GLOBALS["cfg_hiddendir"] . "/EnergySimulator.class.inc.php");
		
		$errMsg = "";
		static::wizardInit();
		
		// Verifiser token først..
		Base::verifyTokenFromRequest("setupSimulator");
		
		//Primær oppvarming
		if ( isset($_REQUEST['priVarme']) && intval($_REQUEST['priVarme']) > 0 )
		{
			$_SESSION['es']->_priHeat = intval($_REQUEST['priVarme']);
		}
		else
		{
			// Default 1
			$_SESSION['es']->_priHeat = 1;
		}
		
		// Sekundær oppvarming
		if ( isset($_REQUEST['secVarme']) && intval($_REQUEST['secVarme']) > 0 )
		{
			$_SESSION['es']->_secHeat = intval($_REQUEST['secVarme']);
		}
		else
		{
			// Default 1
			$_SESSION['es']->_secHeat = 0;
		}
		
		if ( isset($_REQUEST['secheatvalue']) && intval($_REQUEST['secheatvalue']) > 0 )
		{
			$_SESSION['es']->_heatDiff = intval($_REQUEST['secheatvalue']);
		}
		else
		{
			// Default 1
			$_SESSION['es']->_heatDiff = 1;
		}
		
		if ( isset($_REQUEST['floorheating2']) && intval($_REQUEST['floorheating2']) > 0 )
		{
			$_SESSION['es']->_floorHeatEl = intval($_REQUEST['floorheating2']);
		}
		else
		{
			// Default 1
			$_SESSION['es']->_floorHeatEl = 0;
		}
		
		// Primær varmtvannstank elektrisk 
		if ( isset($_REQUEST['priBoilerSize']) && intval($_REQUEST['priBoilerSize']) > 0 )
		{
			$_SESSION['es']->_priBoilerSize = intval($_REQUEST['priBoilerSize']);
		}
		else
		{
			// Default 0
			$_SESSION['es']->_priBoilerSize = 0;
		}
		
		if ( isset($_REQUEST['priBoilerPower']) && intval($_REQUEST['priBoilerPower']) > 0 )
		{
			$_SESSION['es']->_priBoilerPower = intval($_REQUEST['priBoilerPower']);
		}
		else
		{
			// Default 0
			$_SESSION['es']->_priBoilerPower = 0;
		}
		
		// Sekundær varmtvannstank elektrisk
		if ( isset($_REQUEST['secBoilerSize']) && intval($_REQUEST['secBoilerSize']) > 0 )
		{
			$_SESSION['es']->_secBoilerSize = intval($_REQUEST['secBoilerSize']);
		}
		else
		{
			// Default 0
			$_SESSION['es']->_priBoilerSize = 0;
		}
		
		if ( isset($_REQUEST['secBoilerPower']) && intval($_REQUEST['secBoilerPower']) > 0 )
		{
			$_SESSION['es']->_secBoilerPower = intval($_REQUEST['secBoilerPower']);
		}
		else
		{
			// Default 0
			$_SESSION['es']->_secBoilerPower = 0;
		}
		
		// Parse return and redirect
		if ( strlen($errMsg) > 0 )
		{
			static::addInfoMessage($errMsg);
			return static::showWizHeat();
		}
		
			if ( static::$doDebug )
		{
			echo "<pre>\n";
			print_r($_SESSION['es']);
		}
		
		return static::showWizLight();
		
	}
	
	static function showWizLight()
	{
		require_once($GLOBALS["cfg_hiddendir"] . "/EnergySimulator.class.inc.php");
		
		$tpl = static::wizardInit();
 		$tpl->assign('function', static::$funcParseWizardLight);
		
		return static::getMainFrame($tpl->fetch("wizard_Lightning.tpl.html"), "Wizard");
	}
	
	static function parseWizLight()
	{
		require_once($GLOBALS["cfg_hiddendir"] . "/EnergySimulator.class.inc.php");
		
		$errMsg = "";
		static::wizardInit();
		
		// Verifiser token først..
		Base::verifyTokenFromRequest("setupSimulator");
		
		// Primære lyskilder
		
		if ( isset($_REQUEST['pri_belysningstype']) && intval($_REQUEST['pri_belysningstype']) > 0 )
		{
			$_SESSION['es']->_priLightType = intval($_REQUEST['pri_belysningstype']);
		}
		else
		{
			// Default 60 (Glødepære)
			$_SESSION['es']->_priLightType = 60;
		}
		
		// Sekundære lyskilder
		
		if ( isset($_REQUEST['sek_belysningstype']) && intval($_REQUEST['sek_belysningstype']) > 0 )
		{
			$_SESSION['es']->_secLightType = intval($_REQUEST['sek_belysningstype']);
		}
		else
		{
			// Default 0 (ingen valgt)
			$_SESSION['es']->_secLightType = 0;
		}
		
		if ( isset($_REQUEST['antall_lyskilder']) && intval($_REQUEST['antall_lyskilder']) >= 0 )
		{
			$_SESSION['es']->_numLight = intval($_REQUEST['antall_lyskilder']);
		}
		else
		{
			// Default 2 lyskilder
			$_SESSION['es']->_numLight = 2;
		}
		
		if ( isset($_REQUEST['lys_brenntid']) && intval($_REQUEST['lys_brenntid']) >= 0 )
		{
			$_SESSION['es']->_lightTime = intval($_REQUEST['lys_brenntid']);
		}
		else
		{
			// Default 8 timer
			$_SESSION['es']->_lightTime = 8;
		}
		
		// Fordeling lyskilder
		
		if ( isset($_REQUEST['lys_fordeling']) && intval($_REQUEST['lys_fordeling']) > 0 )
		{
			$_SESSION['es']->_lightDiff = intval($_REQUEST['lys_fordeling']);
		}
		else
		{
			// Default 0 (ingen valgt)
			$_SESSION['es']->_lightDiff = 0;
		}
		
		// Parse return and redirect
		if ( strlen($errMsg) > 0 )
		{
			static::addInfoMessage($errMsg);
			return static::showWizLight();
		}

		if ( static::$doDebug )
		{
			echo "<pre>\n";
			print_r($_SESSION['es']);
		}
		
		return static::showWizInhabitants();
	}
	
	static function showWizInhabitants()
	{
		require_once($GLOBALS["cfg_hiddendir"] . "/EnergySimulator.class.inc.php");
	
		$tpl = static::wizardInit();
		$tpl->assign('inhabitantWorkTypesArr', EnergySimulator::getInhabitantWorkTypesAsArray());
		$tpl->assign('function', static::$funcParseWizardInhabitants);
	
		return static::getMainFrame($tpl->fetch("wizard_Inhabitants.tpl.html"), "Wizard");
	}
	
	static function parseWizInhabitants()
	{
		require_once($GLOBALS["cfg_hiddendir"] . "/EnergySimulator.class.inc.php");
		
		$errMsg = "";
		static::wizardInit();
		
		// Verifiser token først..
		Base::verifyTokenFromRequest("setupSimulator");
		
		if ( isset($_REQUEST['antall_i_hus']) && intval($_REQUEST['antall_i_hus']) > 0 )
		{
			$_SESSION['es']->_numPersons = intval($_REQUEST['antall_i_hus']);
		}
		else
		{
			// Default 1 person
			$_SESSION['es']->_numPersons = 1;
		}
		
		if ( isset($_REQUEST['gjen_alder']) && intval($_REQUEST['gjen_alder']) > 0 )
		{
			$_SESSION['es']->_personsAvgAge = intval($_REQUEST['gjen_alder']);
		}
		else
		{
			// Default 35 år
			$_SESSION['es']->_personsAvgAge = 35;
		}

		// Antall beboere og type tidsfordiv
		if ( isset($_REQUEST['inhabitantsAge'])
		&& isset($_REQUEST['inhabitantsWork'])
		&& count($_REQUEST['inhabitantsAge']) == count($_REQUEST['inhabitantsWork'])
		) {
			$_SESSION['es']->_inhabitantsWork = $_REQUEST['inhabitantsWork'];
			$_SESSION['es']->_inhabitantsAge = $_REQUEST['inhabitantsAge'];
		}
		else
		{
			$errMsg .= "Mangler beboere og deres yrker..<br>\n";
		}
		
		// Hvite og brunevarer
		if ( isset($_REQUEST['antall_hvitevarer']) && intval($_REQUEST['antall_hvitevarer']) >= 0 )
		{
			$_SESSION['es']->_numHvit = intval($_REQUEST['antall_hvitevarer']);
		}
		else
		{
			// Default 2 hvitevarer
			$_SESSION['es']->_numHvit = 2;
		}
		
		if ( isset($_REQUEST['antall_brunevarer']) && intval($_REQUEST['antall_brunevarer']) >= 0 )
		{
			$_SESSION['es']->_numBrun = intval($_REQUEST['antall_brunevarer']);
		}
		else
		{
			// Default 2 brunevarer
			$_SESSION['es']->_numBrun = 2;
		}
		
		// Parse return and redirect
		if ( strlen($errMsg) > 0 )
		{
			static::addInfoMessage($errMsg);
			return static::showWizInhabitants();
		}
		
		if ( static::$doDebug )
		{
			echo "<pre>\n";
			print_r($_SESSION['es']);
		}
		
		
		return static::showWizClimateZone();
	}
	
	static function showWizClimateZone()
	{
		require_once($GLOBALS["cfg_hiddendir"] . "/EnergySimulator.class.inc.php");
	
		$tpl = static::wizardInit();
		$tpl->assign('function', static::$funcParseWizardClimateZone);
	
		return static::getMainFrame($tpl->fetch("wizard_ClimateZone.tpl.html"), "Wizard");
	}
	
	static function parseWizClimateZone()
	{
		require_once($GLOBALS["cfg_hiddendir"] . "/EnergySimulator.class.inc.php");
	
		$errMsg = "";
		static::wizardInit();
	
		// Verifiser token først..
		Base::verifyTokenFromRequest("setupSimulator");
	
		if ( isset($_REQUEST['klima']) && intval($_REQUEST['klima']) > 0 )
		{
			$_SESSION['es']->_climateZone = intval($_REQUEST['klima']);
		}
		else
		{
			// Default 1 (Sør-norge?)
			$_SESSION['es']->_climateZone = 1;
		}
	
		// Parse return and redirect
		if ( strlen($errMsg) > 0 )
		{
			static::addInfoMessage($errMsg);
			return static::showWizClimateZone();
		}
	
		if ( static::$doDebug )
		{
			echo "<pre>\n";
			print_r($_SESSION['es']);
		}
	
		return static::showWizClimateZone();
	}
	
	/*
	 * Vis resultatet av beregningene
	 */
	static function showWizResult()
	{
		require_once($GLOBALS["cfg_hiddendir"] . "/EnergySimulator.class.inc.php");
	
		$tpl = static::wizardInit();
//		$tpl->assign('function', static::$funcParseWizardClimateZone);
	
		return static::getMainFrame($tpl->fetch("wizard_Result.tpl.html"), "Wizard");
	}	
	
	static function getEnergyWizard($EnergySimulator = NULL)
	{
		require_once($GLOBALS["cfg_hiddendir"] . "/EnergySimulator.class.inc.php");
		
		if ( $EnergySimulator == NULL )
		{
			$EnergySimulator = new EnergySimulator();
		}
			
		$tpl = new MySmarty();
				
		$tpl->assign('EnergySimulator', $EnergySimulator);
		$tpl->assign('inhabitantWorkTypesArr', EnergySimulator::getInhabitantWorkTypesAsArray());
		$tpl->assign('function', static::$funcSetupEnergySimulator);
		
 		return static::getMainFrame($tpl->fetch("wizard.tpl.html"), "Wizard");
	}
	
	static function setupSimulator()
	{
		require_once($GLOBALS["cfg_hiddendir"] . "/EnergySimulator.class.inc.php");
		
		$tpl = new MySmarty();
		
		$es = new EnergySimulator();
		$errMsg = "";
		
		$_SESSION['es'] = new EnergySimulator();

		// Verifiser token først..
		Base::verifyTokenFromRequest("setupSimulator");
		
		// Spesifiser byggnings type
		if ( isset($_REQUEST['byggType']) && intval($_REQUEST['byggType']) > 0 )
		{
			$es->_buildning = intval($_REQUEST['byggType']);
			$_SESSION['es']->_buildning = intval($_REQUEST['byggType']);
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
			$_SESSION['es']->_houseTotalArea = intval($_REQUEST['houseTotalArea']);
		}
		else
		{
			$errMsg .= "Mangler Brutto Areal<br>\n";
		}
		
		// Primær Areal
		if ( isset($_REQUEST['housePrimaryArea']) && intval($_REQUEST['housePrimaryArea']) > 0 )
		{
			$es->_housePrimaryArea = intval($_REQUEST['housePrimaryArea']);
			$_SESSION['es']->_housePrimaryArea = intval($_REQUEST['housePrimaryArea']);
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
		
		// Primære lyskilder
		
		if ( isset($_REQUEST['pri_belysningstype']) && intval($_REQUEST['pri_belysningstype']) > 0 )
		{
			$es->_priLightType = intval($_REQUEST['pri_belysningstype']);
		}
		else
		{
			// Default 60 (Glødepære)
			$es->_priLightType = 60;
		}
		
		// Sekundære lyskilder
		
		if ( isset($_REQUEST['sek_belysningstype']) && intval($_REQUEST['sek_belysningstype']) > 0 )
		{
			$es->_secLightType = intval($_REQUEST['sek_belysningstype']);
		}
		else
		{
			// Default 0 (ingen valgt)
			$es->_secLightType = 0;
		}
		
		if ( isset($_REQUEST['antall_lyskilder']) && intval($_REQUEST['antall_lyskilder']) >= 0 )
		{
			$es->_numLight = intval($_REQUEST['antall_lyskilder']);
		}
		else
		{
			// Default 2 lyskilder
			$es->_numLight = 2;
		}
		
		if ( isset($_REQUEST['lys_brenntid']) && intval($_REQUEST['lys_brenntid']) >= 0 )
		{
			$es->_lightTime = intval($_REQUEST['lys_brenntid']);
		}
		else
		{
			// Default 8 timer
			$es->_lightTime = 8;
		}
		
		// Fordeling lyskilder
		
		if ( isset($_REQUEST['lys_fordeling']) && intval($_REQUEST['lys_fordeling']) > 0 )
		{
			$es->_lightDiff = intval($_REQUEST['lys_fordeling']);
		}
		else
		{
			// Default 0 (ingen valgt)
			$es->_lightDiff = 0;
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
