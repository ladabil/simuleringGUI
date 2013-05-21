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
	public static $funcShowWizard = "getEnergyWizard";
	public static $funcShowWizardHeat = "showWizHeat";
	public static $funcShowWizardLight = "showWizLight";
	
	public static $funcShowAdminDefault = "showAdminDefault";
	public static $funcShowUserMenu = "showUserMenu";
	public static $funcCreateNewUser =  "createNewUser";
	public static $funcCreateNewUserForm = "createNewUserForm";
	public static $funcDeleteUser = "deleteUser";
	
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
				case static::$funcShowWizard:
					echo static::showDefault();
					break;
				case static::$funcShowWizardHeat:
					echo static::showWizHeat();
					break;
 				case static::$funcShowWizardLight:
 					echo static::showWizLight();
 					break;
// 				case static::$funcLog:
// 					echo static::logMeOut();
// 					break;
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
			die("døøø ikke lov");
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
	
	static function showWizHeat($enegrySimulator = NULL)
	{
		require_once($GLOBALS["cfg_hiddendir"] . "/EnegrySimulator.class.inc.php");

		$tpl = new MySmarty();
		
		$tpl->assign('enegrySimulator', $enegrySimulator);
// 		$tpl->assign('inhabitantWorkTypesArr', EnegrySimulator::getInhabitantWorkTypesAsArray());
		$tpl->assign('function', static::$funcSetupEnergySimulator);
		
		return static::getMainFrame($tpl->fetch("wizard_Heating.tpl.html"), "Wizard");
	}
	
	static function showWizLight($enegrySimulator = NULL)
	{
		require_once($GLOBALS["cfg_hiddendir"] . "/EnegrySimulator.class.inc.php");
		
		if ( $enegrySimulator == NULL )
		{
			$enegrySimulator = new EnegrySimulator();
		}
		
		$tpl = new MySmarty();
		
		$tpl->assign('enegrySimulator', $enegrySimulator);
// 		$tpl->assign('inhabitantWorkTypesArr', EnegrySimulator::getInhabitantWorkTypesAsArray());
 		$tpl->assign('function', static::$funcSetupEnergySimulator);
		
		return static::getMainFrame($tpl->fetch("wizard_Lightning.tpl.html"), "Wizard");
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

		// Verifiser token først..
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
