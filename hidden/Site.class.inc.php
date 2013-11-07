<?php

require_once($GLOBALS["cfg_hiddendir"] . "/MySmarty.class.inc.php");
require_once($GLOBALS["cfg_hiddendir"] . "/SimStoring.class.inc.php");

include($GLOBALS["cfg_hiddendir"] . "/config.inc.php");

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
	public static $funcParseStoreSimZone = "storeSim";
	public static $funcShowStoreZone = "storeZone";
	public static $funcParseStoreSimZoneDone = "parseSimSaveDone";
	public static $funcShowStoreDone = "SimSaveDone";
	public static $funcParseGetSim = "parseGetSim";
	public static $funcShowGetSim = "showGetSim";
	public static $funcParseStoreBuilding = "parseStoreBuilding";
	public static $funcParseStoreBuildingDone = "parseStoreBuildingDone";
	public static $showStoreBuilding = "showStoreBuilding";
	public static $funcShowStoreBuilding = "funcShowStoreBuilding";
	public static $funcParseGetStoreBuilding = "funcParseGetStoreBulding";
	public static $funcParseStoreBeboere = "funcParseStoreBeboere";
	public static $funcParseGetBeboere = "funcParseGetBeboere";
		
	public static $funcShowAdminDefault = "showAdminDefault";
	public static $funcShowUserMenu = "showUserMenu";
	public static $funcCreateNewUser =  "createNewUser";
	public static $funcCreateNewUserForm = "createNewUserForm";
	public static $funcDeleteUser = "deleteUser";
	public static $funcShowValue = "showValue";
	public static $funcUpdateKeyValue = "updateKeyValue";
	
	public static $funcCreateSimulatorTask = "createSimulatorTask";
	public static $funcShowSimulatorTaskList = "showSimulatorTaskList";
	
	public static $value_array;
	public static $storedSim_array;
	public static $weatherstation_array;
	public static $storedBuilding_array;
	public static $storedBeboere_array;
	
	public static $funcUpdateWeatherStationList = "funcUpdateWeatherStationList";
	
	public static $doDebug = false;
	
	//simhentings variabler
	public static $fetchedName = "0";
	public static $fetchedHouseTotalArea = "0"; 
	public static $fetchedHousePrimaryArea = "0";
	public static $fetchedbyggstandard = "0";
	public static $fetchedytterveggAreal = "0";
	public static $fetchedyttertakAreal = "0";
	public static $fetchedvinduDorAreal = "0";
	public static $fetchedluftVolum = "0";
	public static $fetchedonsketTemp = "0";
	public static $fetchedPriHeat = "0";
	public static $fetchedSecHeat = "0";
	public static $fetchedHeatDiff = "0";
	public static $fetchedFloorHeatWa = "0";
	public static $fetchedFloorHeatEl = "0";
	public static $fetchedPriBoilerSize = "0";
	public static $fetchedPriBoilerPower = "0";
	public static $fetchedSecBoilerSize = "0";
	public static $fetchedSecBoilerPower = "0";
	public static $fetchedNumLight = "0";
	public static $fetchedPriLightType = "0";
	public static $fetchedSecLightType = "0";
	public static $fetchedLightTime = "0";
	public static $fetchedLightDiff = "0";
	public static $fetchedClimateZone = 0;
	public static $fetchedClimateTemperatureOffset = 0;
	public static $fetchedClimateWeatherStation = 0;
	public static $fetchedStartTime = "0";
	public static $fetchedEndTime = "0";
	public static $fetchedopplosning = "0";
	public static $fetchedNumHvit = "0";
	public static $fetchedNumBrun = "0";
	
	// variabler for videresending av bygginformasjon til lagring
	public static $StoreBuildByggType = "0";
	public static $StoreBuildByggAar = "0";		
	public static $StoreBuildTotalArea = "0"; 
	public static $StoreBuildBygg = "0";
	public static $StoreYtterVeggAreal = "0";
	public static $StoreYtterTakAreal = "0";
	public static $StoreVinduDorAreal = "0";
	public static $StoreLuftVolum = "0";
	public static $StoreOnsketTemp = "0";
	
	public static $families = array
 	(
  		"Yrke"=>array(),
		"Alder"=>array()
  	);
	
	
	static function parseRequest()
	{
		$function = NULL;
	
		if ( isset($_REQUEST['function']) && strlen($_REQUEST['function']) > 0 )
		{
			$function = $_REQUEST['function'];
		}
		
		// Spesialhï¿½ndtering av loginfunksjon..
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
			case static::$funcShowValue:
				echo static::showValue();
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
 				case static::$funcShowStoreZone:
 					echo static::showStoreSim();
 					break;
				case static::$funcParseStoreSimZone:
 					echo static::parseStoreSimZone();
 					break;
				case static::$funcShowStoreDone:
 					echo static::showStoreDone();
 					break;
				case static::$funcParseStoreSimZoneDone:
 					echo static::parseStoreSimZoneDone();
 					break;
				case static::$funcParseGetSim:
					echo static::parseGetSim();
					break;
				case static::$funcShowGetSim:
					echo static::showGetSim();
					break;
				case static::$funcShowStoreBuilding:
					echo static::showStoreBuilding();
					break;
				case static::$funcParseStoreBuildingDone:
 					echo static::parseStoreBuildingDone();
					break;
				case static::$funcParseGetStoreBuilding:
					echo static::parseGetStoreBuilding();
					break;
				case static::$funcParseStoreBeboere:
					echo static::StoreBeboere();
					break;
				case static::$funcParseGetBeboere:
					echo static::parseGetBeboere();
					break;
				case static::$funcCreateSimulatorTask:
					echo static::createSimulatorTask();
					break;
				case static::$funcShowSimulatorTaskList:
					echo static::showSimulatorTaskList();
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
				case static::$funcUpdateWeatherStationList:
					echo static::updateWeatherStationList();
					break;
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
				
			if ( strlen($_REQUEST["alUsername"]) <= 4 )
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
	
	static function showValue()
	{
		$tpl = new MySmarty();
		$errMsg = "";
	
		$getSQL = "SELECT * FROM simValue";
	
		if ( ($res = Base::getMysqli()->query($getSQL)) === FALSE )
		{
			die(Base::getMysqli()->error);
		}
	
		static::$value_array = Array();
	
		//Looper igjennom og sender tags til smarty
		while($row = mysqli_fetch_Assoc($res))
		{	
			static::$value_array[] = $row;
		}
		
// 		$tpl->assign('function', static::$funcUpdateKeyValue);
		$tpl->assign('simValue' , static::$value_array);
		$tpl->assign('function', static::$funcShowValue);
		
		if ( isset($_REQUEST['mybutton']) && strcasecmp($_REQUEST['mybutton'],"Lagre endringer") == 0)
		{
			for ( $i=0; $i<count($_REQUEST['id']); $i++ )
			{
      			static::updateKeyValue($_REQUEST['id'][$i], $_REQUEST['value'][$i], $_REQUEST['time'][$i]);
			}
			Base::redirectNow(static::$funcShowValue);
		}
// 		echo "<pre>\n";
// 		print_r($_REQUEST);

		$tpl->assign('simValue' , static::$value_array);
		$tpl->assign('function', static::$funcShowValue);
	
		return static::getMainFrame($tpl->fetch("adminValue.tpl.html"), "Admin-side - Nøkkelverdier");
	}
	
	static function updateKeyValue($id, $value, $time)
	{
		$query = "UPDATE simValue
    		SET
       		`Value`='" . $value . "',
       		`timeuse`='" . $time . "'
    		WHERE
       		`id`=" . $id . "
    
      		 ";
	
		if ( ($res = Base::getMysqli()->query($query)) === FALSE )
		{
 			echo "<pre>\n";
 			echo $query;
			die(Base::getMysqli()->error);
		}
	
		return TRUE;
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
		$tpl->assign('storeHouse', static::$funcParseStoreBuilding);
	
		return static::getMainFrame($tpl->fetch("wizard_Building.tpl.html"), "Wizard");
	}
	
	static function parseWizBuilding()
	{
		require_once($GLOBALS["cfg_hiddendir"] . "/EnergySimulator.class.inc.php");
		
		$errMsg = "";
		static::wizardInit();
		
		// Verifiser token fï¿½rst..
		Base::verifyTokenFromRequest("setupSimulator");
		
		$submit = $_POST['submit'];
		if($submit == "Hent Bolig")
		{
			$_SESSION['es']->_houseTotalArea = 99;
			$_SESSION['es']->_housePrimaryArea = 99;
			return static::GetStoreBuilding();
		}
		
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
			// Default byggï¿½r
			$_SESSION['es']->_houseBuildYear = 1980;
		}
		
		// Spesifiser ytterVeggAreal
		if ( isset($_REQUEST['ytterveggAreal']) && intval($_REQUEST['ytterveggAreal']) > 0 )
		{
			$_SESSION['es']->_ytterveggAreal = intval($_REQUEST['ytterveggAreal']);
		}
		else
		{
			// Default ytterVeggAreal
			$_SESSION['es']->_ytterveggAreal = 0;
		}
		
		// Spesifiser ytterTakAreal
		if ( isset($_REQUEST['yttertakAreal']) && intval($_REQUEST['yttertakAreal']) > 0 )
		{
			$_SESSION['es']->_yttertakAreal = intval($_REQUEST['yttertakAreal']);
		}
		else
		{
			// Default yttertakAreal
			$_SESSION['es']->_yttertakAreal = 0;
		}
		
		// Spesifiser vinduDorAreal
		if ( isset($_REQUEST['vinduDorAreal']) && intval($_REQUEST['vinduDorAreal']) > 0 )
		{
			$_SESSION['es']->_vinduDorAreal = intval($_REQUEST['vinduDorAreal']);
		}
		else
		{
			// Default vinduDorAreal
			$_SESSION['es']->_vinduDorAreal = 0;
		}
		
		// Spesifiser luftVolum
		if ( isset($_REQUEST['luftVolum']) && intval($_REQUEST['luftVolum']) > 0 )
		{
			$_SESSION['es']->_luftVolum = intval($_REQUEST['luftVolum']);
		}
		else
		{
			// Default luftVolum
			$_SESSION['es']->_luftVolum = 0;
		}
		
		// Spesifiser onsketTemp
		if ( isset($_REQUEST['onsketTemp']) && intval($_REQUEST['onsketTemp']) > 0 )
		{
			$_SESSION['es']->_onsketTemp = intval($_REQUEST['onsketTemp']);
		}
		else
		{
			// Default onsketTemp
			$_SESSION['es']->_onsketTemp = 20;
		}
		
		
		if ( isset($_REQUEST['klima']) && intval($_REQUEST['klima']) > 0 )
		{
			$_SESSION['es']->_climateZone = intval($_REQUEST['klima']);
		}
		else
		{
			// Default 1 (Sï¿½r-norge?)
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
		
		// Primï¿½r Areal
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
		
		
		if($submit == "Lagre Bolig")
		{
			static::$StoreBuildByggType = $_REQUEST['byggType'];
			static::$StoreBuildByggAar = $_REQUEST['byggaar'];
			static::$StoreBuildTotalArea = $_REQUEST['houseTotalArea'];
			static::$StoreBuildBygg = $_REQUEST['housePrimaryArea'];
			static::$StoreYtterVeggAreal = $_REQUEST['ytterveggAreal'];
			static::$StoreYtterTakAreal = $_REQUEST['yttertakAreal'];
			static::$StoreVinduDorAreal = $_REQUEST['vinduDorAreal'];
			static::$StoreLuftVolum = $_REQUEST['luftVolum'];
			static::$StoreOnsketTemp = $_REQUEST['onsketTemp'];	
			
			return static::showStoreBuilding();
		}
		
		
		else return static::showWizHeat();
	}
	
	static function GetStoreBuilding()
	{
		require_once($GLOBALS["cfg_hiddendir"] . "/EnergySimulator.class.inc.php");
		
		
		$tpl = static::wizardInit();
		$getSQL = "SELECT id, StoredName FROM preDef";
		
		if ( ($res = Base::getMysqli()->query($getSQL)) === FALSE )
		{
			die(Base::getMysqli()->error);
		}
		
		//Looper igjennom og sender tags til smarty.
		while($row = mysqli_fetch_array($res))
		{
			static::$storedBuilding_array[] = $row;
		}
		//$tpl->assign('dump', var_dump($row));
		$tpl->assign('storedBuilding' , static::$storedBuilding_array);
		$tpl->assign('function', static::$funcParseGetStoreBuilding);
		
		return static::getMainFrame($tpl->fetch("wizard_GetStoreBuilding.tpl.html"), "Wizard");
	}

	static function parseGetStoreBuilding()
	{
		require_once($GLOBALS["cfg_hiddendir"] . "/EnergySimulator.class.inc.php");
		static::wizardInit();
		
		// Verifiser token fï¿½rst..
		Base::verifyTokenFromRequest("setupSimulator");
		
		
		$tpl = new MySmarty();
		
		$es = new EnergySimulator();
		$errMsg = "";
		
		$_SESSION['es'] = new EnergySimulator();
		
		$sql = "SELECT * FROM preDef WHERE id = '".intval($_REQUEST['simValgt'])."'";
		
		if ( ($res = Base::getMysqli()->query($sql)) === FALSE )
		{
			die(Base::getMysqli()->error);
		}
		$tmpRes = $res->fetch_Assoc();
		$_SESSION['es']->_building = $tmpRes['building'];
		$_SESSION['es']->_houseBuildYear = $tmpRes['houseBuildYear'];
		$_SESSION['es']->_houseTotalArea = $tmpRes['houseTotalArea'];
		$_SESSION['es']->_housePrimaryArea = $tmpRes['housePrimaryArea'];
		$_SESSION['es']->_ytterveggAreal = $tmpRes['ytterVeggAreal'];
		$_SESSION['es']->_yttertakAreal = $tmpRes['ytterTakAreal'];
		$_SESSION['es']->_vinduDorAreal = $tmpRes['vinduDorAreal'];
		$_SESSION['es']->_luftVolum = $tmpRes['luftVolum'];
		$_SESSION['es']->_onsketTemp = $tmpRes['onsketTemp'];
		
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
		
		// Verifiser token fï¿½rst..
		Base::verifyTokenFromRequest("setupSimulator");
		
		//Primï¿½r oppvarming
		if ( isset($_REQUEST['priVarme']) && intval($_REQUEST['priVarme']) > 0 )
		{
			$_SESSION['es']->_priHeat = intval($_REQUEST['priVarme']);
		}
		else
		{
			// Default 1
			$_SESSION['es']->_priHeat = 1;
		}
		
		// Sekundï¿½r oppvarming
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
		
		// Primï¿½r varmtvannstank elektrisk 
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
		
		// Sekundï¿½r varmtvannstank elektrisk
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
		
		// Verifiser token fï¿½rst..
		Base::verifyTokenFromRequest("setupSimulator");
		
		// Primï¿½re lyskilder
		
		if ( isset($_REQUEST['pri_belysningstype']) && intval($_REQUEST['pri_belysningstype']) > 0 )
		{
			$_SESSION['es']->_priLightType = intval($_REQUEST['pri_belysningstype']);
		}
		else
		{
			// Default 60 (Glï¿½depï¿½re)
			$_SESSION['es']->_priLightType = 60;
		}
		
		// Sekundï¿½re lyskilder
		
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
	
	static function parseGetBeboere()
	{
		require_once($GLOBALS["cfg_hiddendir"] . "/EnergySimulator.class.inc.php");
		static::wizardInit();
		
		// Verifiser token fï¿½rst..
		Base::verifyTokenFromRequest("setupSimulator");
		
		
		$tpl = new MySmarty();
		
		$sql = "SELECT * FROM FamilyStore WHERE StoredName = '".$_REQUEST['beboereValgt']."'";

		if ( static::$doDebug )
		{
			echo "Query: " . $sql . "<br>\n";
		}
		
		if ( ($res = Base::getMysqli()->query($sql)) === FALSE )
		{
			die(Base::getMysqli()->error);
		}
		// Her mÃ¥ det kodes en mÃ¥te Ã¥ hente ut fra DB og rett inn i session arrayet
		
		//$tmpRes = $res->fetch_Assoc();
		while($row = mysqli_fetch_array($res,MYSQLI_ASSOC))
		{
			if ( static::$doDebug )
			{
				echo "<pre>\n";
				print_r($row);
				echo "</pre>\n";
			}
			array_push(static::$families["Yrke"], $row['work']);
			array_push(static::$families["Alder"], $row['age']);
			//array_push($_SESSION['es']->_inhabitantsWork, $row['work']);
			//array_push($_SESSION['es']->_inhabitantsAge, $row['age']);	
		}
		
		if ( static::$doDebug )
		{
			echo "<pre>\n";
			print_r(static::$families["Yrke"]);
			print_r(static::$families["Alder"]);
			echo "</pre>\n";
		}
		
		$_SESSION['es']->_inhabitantsWork = static::$families["Yrke"];
		$_SESSION['es']->_inhabitantsAge = static::$families["Alder"];
		
		return static::showWizClimateZone();
	}
	
	static function showGetBeboere()
	{
		require_once($GLOBALS["cfg_hiddendir"] . "/EnergySimulator.class.inc.php");
	
		$tpl = static::wizardInit();
		
		$getSQL = "SELECT id, StoredName FROM FamilyStoreName";
		
		if ( ($res = Base::getMysqli()->query($getSQL)) === FALSE )
		{
			die(Base::getMysqli()->error);
		}
		
		//Looper igjennom og sender tags til smarty.
		while($row = mysqli_fetch_array($res))
		{
			static::$storedBeboere_array[] = $row;
		}
		//$tpl->assign('dump', var_dump($row));
		$tpl->assign('storedBeboere' , static::$storedBeboere_array);
		
		//$tpl->assign('inhabitantWorkTypesArr', EnergySimulator::getInhabitantWorkTypesAsArray());
		$tpl->assign('function', static::$funcParseGetBeboere);
		//$tpl->assign('array', static::$families);
		if ( static::$doDebug )
		{
			echo "<pre>\n";
			print_r($_SESSION['es']);
			
		}
	
		return static::getMainFrame($tpl->fetch("GetBeboere.tpl.html"), "Wizard");
	}
	
	static function showStoreBeboere()
	{
		require_once($GLOBALS["cfg_hiddendir"] . "/EnergySimulator.class.inc.php");
	
		$tpl = static::wizardInit();
		//$tpl->assign('inhabitantWorkTypesArr', EnergySimulator::getInhabitantWorkTypesAsArray());
		$tpl->assign('function', static::$funcParseStoreBeboere);
		//$tpl->assign('array', static::$families);
		if ( static::$doDebug )
		{
			echo "<pre>\n";
			print_r($_SESSION['es']);
			
		}
	
		return static::getMainFrame($tpl->fetch("array_test.tpl.html"), "Wizard");
	}
	
	static function StoreBeboere()
	{
		if ( isset($_REQUEST['StorageName']) && strlen($_REQUEST['StorageName']) > 0 )
		{
			foreach ($_SESSION['es']->_inhabitantsWork as $i => $value)
			{
			
				$sql = "INSERT INTO FamilyStore
				(
					work,
					age,	
					StoredName
				) 
				VALUES
				(
					".$_SESSION['es']->_inhabitantsWork[$i].",
					".$_SESSION['es']->_inhabitantsAge[$i].", 	
					'".$_REQUEST['StorageName']."'
				)";
				
				if ( ($res = Base::getMysqli()->query($sql)) === FALSE )
				{
					die(Base::getMysqli()->error);
				}
			}
		
			$sql = "INSERT INTO FamilyStoreName
			(	
				StoredName
			) 
				VALUES
			( 	
				'".$_REQUEST['StorageName']."'
			)";
					
			if ( ($res = Base::getMysqli()->query($sql)) === FALSE )
			{
				die(Base::getMysqli()->error);
			}
			
		}
		else
		{
			$errMsg .= "Mangler lagringsnavn.<br>\n";
		}
	
		return static::showWizClimateZone();
	}
	
	static function parseWizInhabitants()
	{
		require_once($GLOBALS["cfg_hiddendir"] . "/EnergySimulator.class.inc.php");
		
		$errMsg = "";
		static::wizardInit();
		
		// Verifiser token fï¿½rst..
		Base::verifyTokenFromRequest("setupSimulator");
		
		if( isset($_REQUEST['getInhabSubmit']) && strcasecmp($_REQUEST['getInhabSubmit'],"Hent Beboere") == 0)
		{
			return static::showGetBeboere();
		}
		
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
			// Default 35 ï¿½r
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

		if(isset($_REQUEST['storeInhabSubmit']) && strcasecmp($_REQUEST['storeInhabSubmit'],"Lagre Beboere") == 0)
		{
			return static::showStoreBeboere();
		}
		
		return static::showWizClimateZone();
	}
	
	static function showWizClimateZone()
	{
		require_once($GLOBALS["cfg_hiddendir"] . "/EnergySimulator.class.inc.php");
	
		$tpl = static::wizardInit();
		$tpl->assign('function', static::$funcParseWizardClimateZone);
		
		$getSQL = "SELECT stnr, name, department FROM weatherStations";
		
		if ( ($res = Base::getMysqli()->query($getSQL)) === FALSE )
		{
			die(Base::getMysqli()->error);
		}
		
		static::$weatherstation_array = Array();
		
		//Looper igjennom og sender tags til smarty
		while($row = mysqli_fetch_Assoc($res))
		{
			static::$weatherstation_array[] = $row;
		}

		$tpl->assign('climateWeatherStation' , static::$weatherstation_array);
		return static::getMainFrame($tpl->fetch("wizard_ClimateZone.tpl.html"), "Wizard");
	}
	
	static function parseWizClimateZone()
	{
		require_once($GLOBALS["cfg_hiddendir"] . "/EnergySimulator.class.inc.php");
	
		$errMsg = "";
		static::wizardInit();
	
		// Verifiser token fï¿½rst..
		Base::verifyTokenFromRequest("setupSimulator");
	
		if ( isset($_REQUEST['klima']) && intval($_REQUEST['klima']) > 0 )
		{
			$_SESSION['es']->_climateZone = intval($_REQUEST['klima']);
		}
		else
		{
			// Default 0
			$_SESSION['es']->_climateZone = 0;
		}
		
		if ( isset($_REQUEST['climateWeatherStation']) && intval($_REQUEST['climateWeatherStation']) > 0 )
		{
			$_SESSION['es']->_climateWeatherStation = intval($_REQUEST['climateWeatherStation']);
		}
		else
		{
			// Default 0
			$_SESSION['es']->_climateWeatherStation = 0;
		}
		
		if ( isset($_REQUEST['klima']) && intval($_REQUEST['klima']) > 0 )
		{
			$_SESSION['es']->_climateZone = intval($_REQUEST['klima']);
		}
		else
		{
			// Default 1 (Sï¿½r-norge?)
			$_SESSION['es']->_climateZone = 1;
		}
		if ( isset($_REQUEST['startTime']) && intval($_REQUEST['startTime']) >= 0 )
		{
			$_SESSION['es']->_startTime = ($_REQUEST['startTime']);
		}
		else
		{
			// Default starttid
			$_SESSION['es']->_startTime = '2012-05-19 10:00:00';
		}
		if ( isset($_REQUEST['endTime']) && intval($_REQUEST['endTime']) >= 0 )
		{
			$_SESSION['es']->_endTime = ($_REQUEST['endTime']);
		}
		else
		{
			// Default slutttid
			$_SESSION['es']->_endTime = '2012-06-23 10:00:00';
		}
		if ( isset($_REQUEST['opplosning']) && intval($_REQUEST['opplosning']) >= 0 )
		{
			$_SESSION['es']->_opplosning = intval($_REQUEST['opplosning']);
		}
		else
		{
			// Default opplosning
			$_SESSION['es']->_opplosning = 10;
		}
		
		if ( isset($_REQUEST['climateTemperatureOffset']) && floatval($_REQUEST['climateTemperatureOffset']) <> 0 )
		{
			$_SESSION['es']->_climateTemperatureOffset = intval($_REQUEST['climateTemperatureOffset']);
		}
		else
		{
			// Default temperatur offsett
			$_SESSION['es']->_climateTemperatureOffset = 0;
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
	
		return static::showStoreSim();
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
	
	/*
	 * Vis en liste over alle mine simuleringer
	 */
	static function showSimulatorTaskList()
	{
		$tpl = new MySmarty();
		
		$errMsg = "";
		
		$query = "SELECT 
						`SimStoring`.*
						,`SimTask`.`id` AS `stId`
						,`SimTask`.`xmlId`
						,`SimTask`.`TimeStarted`
						,`SimTask`.`TimeEnded`
						,`SimTask`.`TimeCreated`
						,`SimTask`.`TimeUpdated`
					FROM 
						`SimTask`
						LEFT JOIN `SimStoring` ON (`SimTask`.`SimStoringId`=`SimStoring`.`id`)
				";
//					WHERE 
//						`SimTask`.`AuthLibUserId`=" . intval(AuthLib::getUserId()) . "
		$query .= "ORDER BY
						`SimTask`.`TimeCreated` DESC
						,`SimStoring`.`id` DESC
					";
		
		if ( ($res = Base::getMysqli()->query($query)) === FALSE )
		{
			die(Base::getMysqli()->error);
		}
		
		$simTaskArr = Array();
		
		while($row = $res->fetch_assoc())
		{
			$simTaskArr[] = $row;
		}

		$tpl->assign('simTaskArr', $simTaskArr);
		$tpl->assign('resultURL', dirname(Base::getScripturl()) . "/resultater/");
		//		$tpl->assign('function', static::$funcParseGetSim);
		
		return static::getMainFrame($tpl->fetch("ShowSimulatorTaskList.tpl.html"), "Wizard");
	}
	
	/*
	 * Opprett en SimTask for en kjøring av simuleringen..
	 */
	static function createSimulatorTask($simStoringId = NULL)
	{
		if ( $simStoringId == NULL )
		{
			if ( !isset($_REQUEST['simStoringId']) || intval($_REQUEST['simStoringId']) < 0 )
			{
				die("Invalid simStoringId");
			}
			
			$simStoringId = intval($_REQUEST['simStoringId']);
		}
		
		$xmlId = date("Ymd") . "_" . time() . "_" . rand(1000,9999);

		$timeEstimate = static::createXMLForSimMotor($simStoringId, $xmlId);
		$simTaskId = static::insertSimulatorTask($simStoringId, $xmlId, AuthLib::getUserId());
		
		$infoMsg = "Simuleringen er lagt til i køen, kjøreId: " . $simTaskId . "<br>\n";
//		$infoMsg .= "Estimert simuleringstid er: : " . $timeEstimate . " sekunder<br>\n";
//		$infoMsg .= "<i>med forbehold om kø i beregning og kommunikasjonsfeil</i><br>\n";
		
		Base::redirectNow(static::$funcShowSimulatorTaskList
								,Array(
										"infoMessage"=>urlencode($infoMsg)
								)
		);
		
		die($infoMsg);
	}
	
	static function getDataForSimMotor($simStoringId)
	{
		$retStr = "";
		
		$sql = "SELECT * FROM `SimStoring` WHERE `id`=" . intval($simStoringId) . " LIMIT 1";
		
		if ( ($result = Base::getMysqli()->query($sql)) === FALSE )
		{
			die(Base::getMysqli()->error);
		}
		
		if ( !$result || mysqli_num_rows($result) !== 1 )
		{
			die("Fant ingen oppføring i databasen for: " . $simStoringId . " - Query: '" . $sql . "'");
		}
		
		$row = $result->fetch_assoc();
		
		$simStoring = new SimStoring($row);
		
		return $simStoring;
	}
	
	static function createXMLForSimMotor($simStoringId, $xmlId)
	{
		$simStoring = static::getDataForSimMotor($simStoringId);
		
		// Lager XML
		$xml = "<?xml version=\"1.0\"?>";
		$xml .= '<simulering>';
		$xml .= "\n\t";	// For DOM - human readable \n <- line break, \t <- tab for each class
		
		// Beboere
		$xml .= "<Familie type=\"class\">\n\t\t";
		$xml .= static::hentNokkelVerdiForXML("Familie");
		$xml .= "<Person type=\"class\"> \n\t\t\t";
		$xml .= static::hentNokkelVerdiForXML("Person");
		$xml .= "<Alder>50</Alder> \n\t\t\t";
		$xml .= "<Kjonn>Kvinne</Kjonn> \n\t\t";
		$xml .= "<Person type=\"class\"> \n\t\t";
		$xml .= "</Person> \n\t\t\t";
		$xml .= "<Alder>60</Alder> \n\t\t\t";
		$xml .= "<Kjonn>Mann</Kjonn> \n\t\t";
		$xml .= "</Person> \n\t";
		$xml .= "</Familie>\n\t";
		
		// Boligtyp>
		$xml .= "<Enebolig type=\"class\">\n\t\t\t";
		$xml .= static::hentNokkelVerdiForXML("Enebolig");
		$xml .= "<bruttoAreal>". ($simStoring->_houseTotalArea) . "</bruttoAreal> \n\t\t\t";
		$xml .= "<pRomAreal>". ($simStoring->_housePrimaryArea)."</pRomAreal> \n\t\t";
		$xml .= "<Varmetap type=\"class\"> \n\t\t\t";
		$xml .= static::hentNokkelVerdiForXML("Varmetap");
		$xml .= "<byggstandard>" . ($simStoring->_houseBuildYearParsed) . "</byggstandard>\n\t\t\t";			// Hardkodet ihht testData.xml TODO: Legg inn felter i bygning
		$xml .= "<ytterveggAreal>". ($simStoring->_ytterveggAreal) . "</ytterveggAreal>\n\t\t\t";
		$xml .= "<yttertakAreal>". ($simStoring->_yttertakAreal) . "</yttertakAreal>\n\t\t\t";
		$xml .= "<vinduDorAreal>". ($simStoring->_vinduDorAreal) . "</vinduDorAreal>\n\t\t\t";
		$xml .= "<luftVolum>". ($simStoring->_luftVolum)."</luftVolum>\n\t\t\t";
		$xml .= "<onsketTemp>". ($simStoring->_onsketTemp)."</onsketTemp>\n\t\t";
		$xml .= "</Varmetap> \n\t\t";
		$xml .= "<Soltilskudd type=\"class\">\n\t\t";
		$xml .= static::hentNokkelVerdiForXML("Soltilskudd");
		
		$xml .= "</Soltilskudd> \n\t\t";
		$xml .= "<ForbrukVann type=\"class\"> \n\t\t\t";
		$xml .= static::hentNokkelVerdiForXML("ForbrukVann");
		$xml .= "<priHeat>". ($simStoring->_priHeat)."</priHeat> \n\t\t\t";
		$xml .= "<secHeat>". ($simStoring->_secHeat)."</secHeat> \n\t\t\t";
		$xml .= "<heatDiff>". ($simStoring->_heatDiff)."</heatDiff> \n\t\t\t";
		$xml .= "<floorHeatWa>". ($simStoring->_floorHeatWa)."</floorHeatWa> \n\t\t\t";
		$xml .= "<floorHeatEl>". ($simStoring->_floorHeatEl)."</floorHeatEl> \n\t\t\t";
		$xml .= "<priBoilerSize>". ($simStoring->_priBoilerSize)."</priBoilerSize> \n\t\t\t";
		$xml .= "<priBoilerPower>". ($simStoring->_priBoilerPower)."</priBoilerPower> \n\t\t\t";
		$xml .= "</ForbrukVann>  \n\t\t";
		$xml .= "<Belysning type=\"class\"> \n\t\t\t";
		$xml .= static::hentNokkelVerdiForXML("Belysning");
		// 			$xml .= "<antLys>". $simStoring->_numLight."</antLys> \n\t\t\t";
		// 			$xml .= "<priLysType>". $simStoring->_priLightType."</priLysType> \n\t\t\t";
		// 			$xml .= "<secLysType>". $simStoring->_secLightType."</secLysType> \n\t\t\t";
		$xml .= "<brenntid>". ($simStoring->_lightTime)."</brenntid> \n\t\t\t";
		$xml .= "<lysDiff>". ($simStoring->_lightDiff)."</lysDiff> \n\t\t";
		$xml .= "</Belysning>  \n\t\t";
		$xml .= "<ForbrukHvitevare type=\"class\"> \n\t\t";
		$xml .= static::hentNokkelVerdiForXML("ForbrukHvitevare");
		// 			$xml .= "<hvite>". $simStoring->_hvite."</hvite> \n\t\t";
		$xml .= "</ForbrukHvitevare> \n\t\t";
		$xml .= "<ForbrukBrunevare type=\"class\"> \n\t\t";
		$xml .= static::hentNokkelVerdiForXML("ForbrukBrunevare");
		// 			$xml .= "<brune>". $simStoring->_brun."</brune> \n\t\t\t";
		$xml .= "</ForbrukBrunevare>  \n\t";
		$xml .= "</Enebolig>\n\t";
		
		// Klima
		$xml .= "<Klima type=\"class\">\n\t\t";
		$xml .= static::hentNokkelVerdiForXML("Klima");
		if ( floatval($climateTemperatureOffset) <> 0.0 )
		{
			$xml .= "<temperatureoffset>" . $climateTemperatureOffset . "</temperatureoffset>\n\t\t";
		}
		
		if ( intval($climateWeatherStation) > 0 )
		{
			$xml .= "<maalestasjon>" . intval($climateWeatherStation) . "</maalestasjon>\n\t\t";
		}
		else
		{
			if ( intval($climateZone) <= 0 || intval($climateZone) > 7 )
			{
				// Default klimasone er 1 -> Sør-norge
				$climateZone = 1;
			}
				
			$xml .= "<sone>".intval($climateZone)."</sone>\n\t";
		}
		$xml .= "</Klima>\n\t";
		
		// Tidsrom
		$xml .= "<Tidsrom type=\"class\">\n\t\t";
		$xml .= static::hentNokkelVerdiForXML("Tidsrom");
		$xml .= "<startDateTime>". ($simStoring->_startTime) ." CET</startDateTime>\n\t\t";
		$xml .= "<endDateTime>". ($simStoring->_endTime)." CET</endDateTime>\n\t\t";
		if ( intval($opplosning) > 0 )
		{
			$xml .= "<opplosning>". ($simStoring->_opplosning)."</opplosning>\n\t";
		}
		$xml .= "</Tidsrom>\n";
		
		
		$xml .= "</simulering>\n\r";
		$xmlobj = new SimpleXMLElement($xml);
		// 	$xmlobj -> asXML ("testData.xml");
		
		// DOMDocument for output in human readable file
		$dom = new DOMDocument('1.0');
		$dom->preserveWhiteSpace = true;
		$dom->formatOutput = true;
		$dom->loadXML($xmlobj->asXML());
		//echo $dom->saveXML(); // For test -> viser xml datene
		$filename = "/home/gruppe2/new/" . $xmlId . ".xml";
		$dom->save($filename);
		
		$datetime1 = new DateTime($simStoring->_startTime);
		$datetime2 = new DateTime($simStoring->_endTime);
		$interval = $datetime1->diff($datetime2);
		$tidsforbruk = ( ( ($datetime2->getTimestamp()/1973963) - ($datetime1->getTimestamp()/1973963) )  ) * 2.65 ; //973963

		return $tidsforbruk;
	}

	static function hentNokkelVerdiForXML($className)
	{
		$retStr = "";
	
		$sql = "SELECT * FROM simValue WHERE Class LIKE '" . strtolower($className) . "'";
	
		if ( ($result = Base::getMysqli()->query($sql)) === FALSE )
		{
			die(Base::getMysqli()->error);
		}
		
		if ( !$result || mysqli_num_rows($result) <= 0 )
		{
			return "";
		}
	
		while ( $row = $result->fetch_assoc() )
		{
			$retStr .= "<" . $row['Name'] . ">";
			$retStr .= $row['Value'];
			$retStr .= "</" . $row['Name'] . ">\n";
		}
	
		return $retStr;
	}
	
	
	static function hentVaerstasjonsNavn($wsId)
	{
		$retStr = "";
	
		$sql = "SELECT `name` FROM `weatherStations` WHERE `stnr`=" . intval($wsId) . " LIMIT 1";
		
		if ( ($result = Base::getMysqli()->query($sql)) === FALSE )
		{
			die(Base::getMysqli()->error);
		}
	
		if ( !$result || mysqli_num_rows($result) !== 1 )
		{
			return "Ukjent";
		}
		
		$row = $result->fetch_assoc();
		
		return $row['name'];
	}	
	
	
	
	/*
	 * lagre SimTask i databasen...
	 */
	static function insertSimulatorTask($storingId, $xmlId, $authLibUserId)
	{
		if ( intval($authLibUserId) <= 0 )
		{
			die(__LINE__ . ": Invalid authLibUserId: '" . $authLibUserId . "'<br>\n");
		}
		
		$query = "
				INSERT INTO
						`SimTask`
					SET
						`xmlId`='" . $xmlId . "'
						,`SimStoringId`=" . $storingId . "
						,`AuthLibUserId`=" . $authLibUserId . "
						,`TimeCreated`=NOW()
				";
		
		if ( ($res = Base::getMysqli()->query($query)) === FALSE )
		{
			die(Base::getMysqli()->error);
		}
		
		return Base::getMysqli()->insert_id;
	}
	
	// storing to DB
	static function storeDB($definedStoreName)
	{
		$sql = "INSERT INTO SimStoring
		(
			building,
			houseBuildYear,
			houseTotalArea, 
			housePrimaryArea, 
			ytterveggAreal,
			yttertakAreal,
			vinduDorAreal,
			luftVolum,
			onsketTemp,
			priHeat, 
			secHeat, 
			heatDiff, 
			floorHeatWa, 
			floorHeatEl, 
			priBoilerSize, 
			priBoilerPower, 
			secBoilerSize, 
			secBoilerPower, 
			numLight, 
			priLightType, 
			secLightType, 
			lightTime, 
			lightDiff, 
			climateZone,
			climateTemperatureOffset,
			climateWeatherStation,
			startTime,
			endTime,
			opplosning, 
			numHvit, 
			numBrun,
			name
		) 
		VALUES
		(
			'".$_SESSION['es']->_building."',
			'".$_SESSION['es']->_houseBuildYear."',		
			'".$_SESSION['es']->_houseTotalArea."', 
			'".$_SESSION['es']->_housePrimaryArea."',
			'".$_SESSION['es']->_ytterveggAreal."',
			'".$_SESSION['es']->_yttertakAreal."',
			'".$_SESSION['es']->_vinduDorAreal."',
			'".$_SESSION['es']->_luftVolum."',
			'".$_SESSION['es']->_onsketTemp."', 
			'".$_SESSION['es']->_priHeat."', 
			'".$_SESSION['es']->_secHeat."', 
			'".$_SESSION['es']->_heatDiff."', 
			'".$_SESSION['es']->_floorHeatWa."', 
			'".$_SESSION['es']->_floorHeatEl."', 
			'".$_SESSION['es']->_priBoilerSize."', 
			'".$_SESSION['es']->_priBoilerPower."', 
			'".$_SESSION['es']->_secBoilerSize."', 
			'".$_SESSION['es']->_secBoilerPower."', 
			'".$_SESSION['es']->_numLight."', 
			'".$_SESSION['es']->_priLightType."', 
			'".$_SESSION['es']->_secLightType."', 
			'".$_SESSION['es']->_lightTime."', 
			'".$_SESSION['es']->_lightDiff."', 
			'".$_SESSION['es']->_climateZone."', 
			'".$_SESSION['es']->_climateTemperatureOffset."', 
			'".$_SESSION['es']->_climateWeatherStation."', 
			'".$_SESSION['es']->_startTime."',
			'".$_SESSION['es']->_endTime."',
			'".$_SESSION['es']->_opplosning."',
			'".$_SESSION['es']->_numHvit."', 
			'".$_SESSION['es']->_numBrun."',
			'".$definedStoreName."'
			
		)";
		
		if ( ($res = Base::getMysqli()->query($sql)) === FALSE )
		{
			die(Base::getMysqli()->error);
		}
		
		return 0;
	}
	
	// Hente sim Resultat
	static function getSimResult($definedSimName)
	{
		$sql = "SELECT name FROM SimStoring WHERE id = '".$definedSimName."'";
		
		if ( ($res = Base::getMysqli()->query($sql)) === FALSE )
		{
			die(Base::getMysqli()->error);
		}
		$tmpRes = $res->fetch_Assoc();
		$fetchedName = $tmpRes['name'];
		
		//return $fetchedName;
	}
	
	
	/*
	 * Vis resultatet av beregningene
	 */
	static function showWizResult()
	{
		require_once($GLOBALS["cfg_hiddendir"] . "/EnergySimulator.class.inc.php");
	
		$tpl = static::wizardInit();
		$tpl->assign('function', static::$funcParseStoreSimZone);
		//static::storeDB("test");
		
		return static::getMainFrame($tpl->fetch("wizard_Result.tpl.html"), "Wizard");
	}	
	
	/*
	 *	Lagring av bolig 
	 */
	
	static function parseStoreBuilding()
	{
		require_once($GLOBALS["cfg_hiddendir"] . "/EnergySimulator.class.inc.php");
		$errMsg = "";
		static::wizardInit();
		
		// Verifiser token fï¿½rst..
		Base::verifyTokenFromRequest("setupSimulator");
		
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
			// Default byggï¿½r
			$_SESSION['es']->_houseBuildYear = 1980;
		}
		
		if ( isset($_REQUEST['klima']) && intval($_REQUEST['klima']) > 0 )
		{
			$_SESSION['es']->_climateZone = intval($_REQUEST['klima']);
		}
		else
		{
			$errMsg .= "Vennligst velg klimasone<br>\n";
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
		
		// Primï¿½r Areal
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
		
		
		static::$StoreBuildByggType = $_REQUEST['byggType'];
		static::$StoreBuildByggAar = $_REQUEST['byggaar'];
		static::$StoreBuildTotalArea = $_REQUEST['houseTotalArea'];
		static::$StoreBuildBygg = $_REQUEST['housePrimaryArea'];	
		
		
		
		return static::showStoreBuilding();
	}
	
	static function showStoreBuilding()
	{
		require_once($GLOBALS["cfg_hiddendir"] . "/EnergySimulator.class.inc.php");
	
		$tpl = static::wizardInit();
		$tpl->assign('function', static::$funcParseStoreBuildingDone);
		
		
		return static::getMainFrame($tpl->fetch("wizard_StoreBuilding.tpl.html"), "Wizard");
	}
	
	static function parseStoreBuildingDone()
	{
		require_once($GLOBALS["cfg_hiddendir"] . "/EnergySimulator.class.inc.php");
	
		$errMsg = "";
		static::wizardInit();
	
		// Verifiser token fï¿½rst..
		Base::verifyTokenFromRequest("setupSimulator");
	
		if ( isset($_REQUEST['StorageName']) && strlen($_REQUEST['StorageName']) > 0 )
		{
			$sql = "INSERT INTO preDef
			(
				building,
				houseBuildYear,
				houseTotalArea, 
				housePrimaryArea,
				ytterVeggAreal,
				ytterTakAreal,
				vinduDorAreal,
				luftVolum,
				onsketTemp, 
				StoredName
			) 
			VALUES
			(
				'".$_SESSION['es']->_building."',
				'".$_SESSION['es']->_houseBuildYear."',		
				'".$_SESSION['es']->_houseTotalArea."', 
				'".$_SESSION['es']->_housePrimaryArea."', 
				'".$_SESSION['es']->_ytterveggAreal."',
				'".$_SESSION['es']->_yttertakAreal."',		
				'".$_SESSION['es']->_vinduDorAreal."', 
				'".$_SESSION['es']->_luftVolum."',
				'".$_SESSION['es']->_onsketTemp."',		
				'".$_REQUEST['StorageName']."'
			)";
			
			if ( ($res = Base::getMysqli()->query($sql)) === FALSE )
			{
				die(Base::getMysqli()->error);
			}
			
		}
		else
		{
			$errMsg .= "Mangler lagringsnavn.<br>\n";
		}
	
		if ( strlen($errMsg) > 0 )
		{
			static::addInfoMessage($errMsg);
			return static::showStoreBuilding();
		}
		
		return static::showWizBuilding();
	}
	
	/*
	 * 	Lagring av Simulering
	 */
	
	static function parseStoreSimZone()
	{
		require_once($GLOBALS["cfg_hiddendir"] . "/EnergySimulator.class.inc.php");
	
		$errMsg = "";
		static::wizardInit();
	
		// Verifiser token fï¿½rst..
		Base::verifyTokenFromRequest("setupSimulator");
		
		return static::showStoreSim();
	}
	
	static function showStoreSim()
	{
		require_once($GLOBALS["cfg_hiddendir"] . "/EnergySimulator.class.inc.php");
	
		$tpl = static::wizardInit();
		$tpl->assign('function', static::$funcParseStoreSimZoneDone);
	
		return static::getMainFrame($tpl->fetch("wizard_Store.tpl.html"), "Wizard");
	}
	
	static function showStoreDone()
	{
		require_once($GLOBALS["cfg_hiddendir"] . "/EnergySimulator.class.inc.php");
	
		$tpl = static::wizardInit();
		$tpl->assign('function', static::$funcParseWizardClimateZone);
		//static::storeDB("test");
		
		return static::getMainFrame($tpl->fetch("wizard_Result.tpl.html"), "Wizard");
	}	
		
	static function parseStoreSimZoneDone()
	{
		require_once($GLOBALS["cfg_hiddendir"] . "/EnergySimulator.class.inc.php");
	
		$errMsg = "";
		static::wizardInit();
	
		// Verifiser token fï¿½rst..
		Base::verifyTokenFromRequest("setupSimulator");
	
		if ( isset($_REQUEST['StorageName']) && strlen($_REQUEST['StorageName']) > 0 )
		{
			static::storeDB($_REQUEST['StorageName']);
		}
		else
		{
			$errMsg .= "Mangler lagringsnavn.<br>\n";
		}
	
		if ( strlen($errMsg) > 0 )
		{
			static::addInfoMessage($errMsg);
			return static::showStoreSim();
		}
		
		Base::redirectNow(static::$funcParseGetSim
			,Array(
				"simValgt"=>Base::getMysqli()->insert_id
				)
				);
		
		die($errMsg);
		
// 		return static::showGetSim();
	}
	
	static function showGetSim()
	{
		require_once($GLOBALS["cfg_hiddendir"] . "/EnergySimulator.class.inc.php");
		
		$tpl = new MySmarty();
		
		$es = new EnergySimulator();
		$errMsg = "";
		
		$_SESSION['es'] = new EnergySimulator();
		
		$getSQL = "SELECT id, name FROM SimStoring";
		
		if ( ($res = Base::getMysqli()->query($getSQL)) === FALSE )
		{
			die(Base::getMysqli()->error);
		}
		
		//Looper igjennom og sender tags til smarty.
		while($row = mysqli_fetch_array($res))
		{
			static::$storedSim_array[] = $row;
		}
		//$tpl->assign('dump', var_dump($row));
		$tpl->assign('storedSim' , static::$storedSim_array);
 		$tpl->assign('function', static::$funcParseGetSim);
		
		return static::getMainFrame($tpl->fetch("GetSim.tpl.html"), "Hent Simulering");
	}
	
	static function updateWeatherStationList()
	{
/*		$fileContents = file_get_contents("http://eklima.met.no/met/MetService?invoke=getStationsProperties&stations=&username=");
	
		if ( strlen($fileContents) <= 0 )
		{
			die("Kunne ikke laste værstasjonsdata");
		}
		*/
	
		echo "<pre>\n";
		
		$wsKlimaSoapClient = new SoapClient("http://eklima.met.no/met/MetService?WSDL");
		
//		print_r($wsKlimaSoapClient->__getFunctions());
		
		$weatherStations =  $wsKlimaSoapClient->getStationsProperties("", "");

		if ( count($weatherStations) <= 0 )
		{
			die("Fant ingen værstasjoner");
		}
		
		/*
		 *     [2824] => stdClass Object
        (
            [name] => JAN MAYEN
            [stnr] => 99950
            [wmoNo] => 1001
            [amsl] => 10
            [department] => JAN MAYEN
            [municipalityNo] => 2211
            [fromYear] => 1908
            [fromMonth] => 8
            [fromDay] => 1
            [toYear] => 0
            [toMonth] => 0
            [toDay] => 0
            [utm_e] => -343205
            [utm_n] => 8038142
            [utm_zone] => 33
            [latDec] => 70.9394
            [lonDec] => -8.669
            [latLonFmt] => decimal_degrees
        )
		 */
		
		$numUpdated = 0;
		
		foreach ( $weatherStations as $weatherStationObj )
		{
			/*
			 * 
			 */
			
			$query = "INSERT INTO `weatherStations` 
					SET
							`stnr`=" . intval($weatherStationObj->stnr) . ", 
							`name`='" . utf8_decode($weatherStationObj->name) . "', 
							`wmoNo`=" . intval($weatherStationObj->stnr) . ", 
							`department`='" . utf8_decode($weatherStationObj->department) . "', 
							`fromYear`=" . intval($weatherStationObj->fromYear) . ", 
							`fromMonth`=" . intval($weatherStationObj->fromMonth) . ", 
							`fromDay`=" . intval($weatherStationObj->fromDay) . ", 
							`toYear`=" . intval($weatherStationObj->toYear) . ", 
							`toMonth`=" . intval($weatherStationObj->toMonth) . ", 
							`toDay`=" . intval($weatherStationObj->toDay) . "
					ON DUPLICATE KEY UPDATE 
							`name`='" . utf8_decode($weatherStationObj->name) . "', 
							`wmoNo`=" . intval($weatherStationObj->stnr) . ", 
							`department`='" . utf8_decode($weatherStationObj->department) . "', 
							`fromYear`=" . intval($weatherStationObj->fromYear) . ", 
							`fromMonth`=" . intval($weatherStationObj->fromMonth) . ", 
							`fromDay`=" . intval($weatherStationObj->fromDay) . ", 
							`toYear`=" . intval($weatherStationObj->toYear) . ", 
							`toMonth`=" . intval($weatherStationObj->toMonth) . ", 
							`toDay`=" . intval($weatherStationObj->toDay) . "
					";
			
			if ( ($res = Base::getMysqli()->query($query)) === FALSE )
			{
				echo "<pre>\n";
				echo $query;
				die(Base::getMysqli()->error);
			}
			
			$numUpdated += Base::getMysqli()->affected_rows;
		}
		
//		print_r($weatherStations);

		$infoMsg = "Oppdatering/innlegging av værstasjoner gikk bra ";
		$infoMsg .= " (Antall værstasjoner: " . count($weatherStations) . ")";
//		$infoMsg = "(Nye: ";
//		$infoMsg .= (count($weatherStations) - $numUpdated) . " - Oppdaterte: " . $numUpdated . ")";
		
//		die($infoMsg);
		
		Base::redirectNow(static::$funcShowAdminDefault
									,Array(
											"infoMessage"=>$infoMsg 
											)
						);
		
		die($infoMsg);
	}
	
	
	// henting av simuleringer
	static function parseGetSim()
	{
		require_once($GLOBALS["cfg_hiddendir"] . "/EnergySimulator.class.inc.php");
		
		$tpl = new MySmarty();
		
		$es = new EnergySimulator();
		$errMsg = "";
		
		$_SESSION['es'] = new EnergySimulator();
		
		$sql = "SELECT * FROM SimStoring WHERE id = '".intval($_REQUEST['simValgt'])."'";
		
		if ( ($res = Base::getMysqli()->query($sql)) === FALSE )
		{
			die(Base::getMysqli()->error);
		}
		$tmpRes = $res->fetch_Assoc();
		$fetchedID = $tmpRes['id'];
		$fetchedName = $tmpRes['name'];
		$fetchedHouseTotalArea = $tmpRes['houseTotalArea']; 
		$fetchedHousePrimaryArea = $tmpRes['housePrimaryArea'];
		$fetchedytterveggAreal = $tmpRes['ytterveggAreal'];
		$fetchedyttertakAreal = $tmpRes['yttertakAreal'];
		$fetchedvinduDorAreal = $tmpRes['vinduDorAreal'];
		$fetchedluftVolum = $tmpRes['luftVolum'];
		$fetchedonsketTemp = $tmpRes['onsketTemp'];
		$fetchedPriHeat = $tmpRes['priHeat'];
		$fetchedSecHeat = $tmpRes['secHeat'];
		$fetchedHeatDiff = $tmpRes['heatDiff'];
		$fetchedFloorHeatWa = $tmpRes['floorHeatWa'];
		$fetchedFloorHeatEl = $tmpRes['floorHeatEl'];
		$fetchedPriBoilerSize = $tmpRes['priBoilerSize'];
		$fetchedPriBoilerPower = $tmpRes['priBoilerPower'];
		$fetchedSecBoilerSize = $tmpRes['secBoilerSize'];
		$fetchedSecBoilerPower = $tmpRes['secBoilerPower'];
		$fetchedNumLight = $tmpRes['numLight'];
		$fetchedPriLightType = $tmpRes['priLightType'];
		$fetchedSecLightType = $tmpRes['secLightType'];
		$fetchedLightTime = $tmpRes['lightTime'];
		$fetchedLightDiff = $tmpRes['lightDiff'];
		$fetchedClimateZone = $tmpRes['climateZone'];
		$fetchedClimateTemperatureOffset = $tmpRes['climateTemperatureOffset'];
		$fetchedClimateWeatherStation = $tmpRes['climateWeatherStation'];
		$fetchedStartTime = $tmpRes['startTime'];
		$fetchedEndTime = $tmpRes['endTime'];
		$fetchedopplosning = $tmpRes['opplosning'];
		$fetchedNumHvit = $tmpRes['numHvit'];
		$fetchedNumBrun = $tmpRes['numBrun'];
		$fetchedBuilding = $tmpRes['building'];
		$fetchedHouseBuildYear = $tmpRes['houseBuildYear'];
		
		if($fetchedBuilding == '1') {$fetchedBuilding = "Enebolig";}
		if($fetchedBuilding == '2') {$fetchedBuilding = "Leilighet";}
		if($fetchedBuilding == '3') {$fetchedBuilding = "Rekkehus";}
		
		if($fetchedHouseBuildYear == '1') {$fetchedHouseBuildYear = "F&oslash;r 1987";}
		if($fetchedHouseBuildYear == '2') {$fetchedHouseBuildYear = "Mellom 1987 og 1997";}
		if($fetchedHouseBuildYear == '3') {$fetchedHouseBuildYear = "Etter 1997";}
		
		if($fetchedClimateZone == '1'){ $fetchedClimateZone = "S&oslash;r-Norge, kyst";}
		if($fetchedClimateZone == '2'){ $fetchedClimateZone = "S&oslash;r-Norge, innland";}
		if($fetchedClimateZone == '3'){ $fetchedClimateZone = "S&oslash;r-Norge, h&oslash;yfjell";}
		if($fetchedClimateZone == '4'){ $fetchedClimateZone = "Midt-Norge, kyst";}
		if($fetchedClimateZone == '5'){ $fetchedClimateZone = "Midt-Norge, innland";}
		if($fetchedClimateZone == '6'){ $fetchedClimateZone = "Nord-Norge, kyst";}
		if($fetchedClimateZone == '7'){ $fetchedClimateZone = "Finnmark og innland Troms";}	
				
		$tpl->assign('simID', $fetchedID);
		$tpl->assign('simName', $fetchedName);
		$tpl->assign('building', $fetchedBuilding);
		$tpl->assign('houseBuildYear', $fetchedHouseBuildYear);
		$tpl->assign('houseTotalArea', $fetchedHouseTotalArea);
		$tpl->assign('housePrimaryArea', $fetchedHousePrimaryArea);
		$tpl->assign('ytterveggAreal', $fetchedytterveggAreal);
		$tpl->assign('yttertakAreal', $fetchedyttertakAreal);
		$tpl->assign('vinduDorAreal', $fetchedvinduDorAreal);
		$tpl->assign('luftVolum', $fetchedluftVolum);
		$tpl->assign('onsketTemp', $fetchedonsketTemp);
		$tpl->assign('priHeat', $fetchedPriHeat);
		$tpl->assign('secHeat', $fetchedSecHeat);
		$tpl->assign('heatDiff', $fetchedHeatDiff);
		$tpl->assign('floorHeatWa', $fetchedFloorHeatWa);
		$tpl->assign('floorHeatEl', $fetchedFloorHeatEl);
		$tpl->assign('priBoilerSize', $fetchedPriBoilerSize);
		$tpl->assign('priBoilerPower', $fetchedPriBoilerPower);
		$tpl->assign('secBoilerSize', $fetchedSecBoilerSize);
		$tpl->assign('secBoilerPower', $fetchedSecBoilerPower);
		$tpl->assign('numLight', $fetchedNumLight);
		$tpl->assign('priLightType', $fetchedPriLightType);
		$tpl->assign('secLightType', $fetchedSecLightType);
		$tpl->assign('lightTime', $fetchedLightTime);
		$tpl->assign('lightDiff', $fetchedLightDiff);
		$tpl->assign('climateZone', $fetchedClimateZone);
		$tpl->assign('climateTemperatureOffset', $fetchedClimateTemperatureOffset);
		$tpl->assign('climateWeatherStation', $fetchedClimateWeatherStation);
		$tpl->assign('startTime', $fetchedStartTime);
		$tpl->assign('endTime', $fetchedEndTime);
		$tpl->assign('opplosning', $fetchedopplosning);
		$tpl->assign('numHvit', $fetchedNumHvit);
		$tpl->assign('numBrun', $fetchedNumBrun);
		
		return static::getMainFrame($tpl->fetch("ShowSim.tpl.html"), "Vis Tidligere Simulering");
	}
	
	static function setupSimulator()
	{
		require_once($GLOBALS["cfg_hiddendir"] . "/EnergySimulator.class.inc.php");
		
		$tpl = new MySmarty();
		
		$es = new EnergySimulator();
		$errMsg = "";
		
		$_SESSION['es'] = new EnergySimulator();

		// Verifiser token fï¿½rst..
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
			// Default 35 ï¿½r
			$es->_personsAvgAge = 35;
		}
		
		if ( isset($_REQUEST['byggaar']) && intval($_REQUEST['byggaar']) > 0 )
		{
			$es->_houseBuildYear = intval($_REQUEST['byggaar']);
		}
		else
		{
			// Default 35 ï¿½r
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
			// Default 1 (Sï¿½r-norge?)
			$es->_climateZone = 1;
		}
		
		if ( isset($_REQUEST['startTime']) && intval($_REQUEST['startTime']) >= 0 )
		{
			$_SESSION['es']->_startTime = ($_REQUEST['startTime']);
		}
		else
		{
			// Default starttid
			$_SESSION['es']->_startTime = '2012-05-19 10:00:00';
		}
		
		if ( isset($_REQUEST['endTime']) && intval($_REQUEST['endTime']) >= 0 )
		{
			$_SESSION['es']->_endTime = ($_REQUEST['endTime']);
		}
		else
		{
			// Default slutttid
			$_SESSION['es']->_endTime = '2012-06-23 10:00:00';
		}
		
		if ( isset($_REQUEST['opplosning']) && intval($_REQUEST['opplosning']) >= 0 )
		{
			$_SESSION['es']->_opplosning = intval($_REQUEST['opplosning']);
		}
		else
		{
			// Default opplosning
			$_SESSION['es']->_opplosning = 3600;
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
		
		// Primï¿½r Areal
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
		
		// Primï¿½re lyskilder
		
		if ( isset($_REQUEST['pri_belysningstype']) && intval($_REQUEST['pri_belysningstype']) > 0 )
		{
			$es->_priLightType = intval($_REQUEST['pri_belysningstype']);
		}
		else
		{
			// Default 60 (Glï¿½depï¿½re)
			$es->_priLightType = 60;
		}
		
		// Sekundï¿½re lyskilder
		
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
