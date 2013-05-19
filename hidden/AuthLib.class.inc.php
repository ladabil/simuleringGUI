<?php

/*
 * Runar Lyngmo - Gruppe 2 - 5/5-2013
 */

define('AL_SC_CHECKSESSION_NOCOOKIE',100);
define('AL_SC_CHECKSESSION_NOTFOUNDINDBTABLE',110);
define('AL_SC_CHECKSESSION_TOOMANYRESULTS',120);
define('AL_SC_CHECKSESSION_INVALIDLASTTIME',130);
define('AL_SC_CHECKSESSION_FAILED_UNKNOWN',140);

define('AL_SC_CHECKSESSION_VALIDATED_OK',200);

define('AL_SC_AUTHUSER_FAILED_UNKNOWN', 302);
define('AL_SC_AUTHUSER_FAILED_NOT_FOUND',303);
define('AL_SC_AUTHUSER_FAILED_NOT_CONFIRMED',304);

define('AL_SC_CREATESESSION_FAILED_DATABASE', 400);
define('AL_SC_CREATESESSION_OK', 401);
define('AL_SC_CREATESESSION_FAILED_UNKNOWN', 402);
define('AL_SC_CHECKSESSION_INVALID_SESSIONDATA', 403);
define('AL_SC_CHECKSESSION_SECURITYCHECKS_FAILED', 404);

define('AL_SC_PROCESSLOGIN_MISSING_USERNAME', 500);
define('AL_SC_PROCESSLOGIN_MISSING_PASSWORD', 501);

require_once(dirname(__FILE__) . "/Base.class.inc.php");
require_once(dirname(__FILE__) . "/AuthLibUser.class.inc.php");

/* 
 * Security-TODO legg til domene og path i setcookie
 */
 
class AuthLib {
	public static $_statusCode = NULL;

	public static $accessLevelAdmin = 1;
	public static $accessLevelUser = 100;
	public static $accessLevelAnonymous = 1000;
	
	function __construct() {
	}
	
	static function setStatusCode($statusCode)
	{
		static::$_statusCode = intval($statusCode);
	}
	
	static function getStatusCode()
	{
		return static::$_statusCode;
	}
	
	static function getAccessLevelStr($accessLevel)
	{
		switch ( $accessLevel )
		{
			case static::$accessLevelAdmin:
				return "Administrator";
				break;
			case static::$accessLevelUser:
				return "Bruker";
				break;
			case static::$accessLevelAnonymous:
				return "Anonym";
				break;
		}
	}
	
	static function getAccessLevelAsStr()
	{
		return static::getAccessLevelStr(static::getAccessLevel());
	}
	
	static function getStatusMessage()
	{
		switch ( static::$_statusCode )
		{
			case AL_SC_CHECKSESSION_NOCOOKIE:
				return 'Sesjonsverifisering: fant ikke cookie';
			case AL_SC_CHECKSESSION_INVALIDLASTTIME:
				return 'Sesjonsverifisering: Timeout, vennligst logg inn på nytt igjen';
			case AL_SC_CHECKSESSION_INVALID_SESSIONDATA:
				return 'Sesjonsverifisering: Feil i sesjonsdata, vennligst logg inn på nytt igjen';
			case AL_SC_CHECKSESSION_FAILED_UNKNOWN:
				return 'Sesjonsverifisering: ukjent feil';
			case AL_SC_CHECKSESSION_SECURITYCHECKS_FAILED:
				return 'Sesjonsverifisering: en eller flere sikkerhetssjekker feilet, vennligst logg inn på nytt igjen';
			case AL_SC_CHECKSESSION_VALIDATED_OK:
				return 'Sesjonsverifisering: vellykket';
			case AL_SC_AUTHUSER_FAILED_UNKNOWN:
				return 'AuthUser: ukjent feil';
			case AL_SC_AUTHUSER_FAILED_NOT_FOUND:
				return 'AuthUser: brukeren finnes ikke';
			case AL_SC_AUTHUSER_FAILED_NOT_CONFIRMED:
				return 'AuthUser: brukerkontoen er ikke bekreftet/aktivert';
			case AL_SC_CREATESESSION_FAILED_DATABASE:
				return 'Sesjonsopprettelse: database feilet';
			case AL_SC_CREATESESSION_OK:
				return 'Sesjonsopprettelse: vellykket';
			case AL_SC_CREATESESSION_FAILED_UNKNOWN:
				return 'Sesjonsopprettelse: ukjent feil';
			case AL_SC_PROCESSLOGIN_MISSING_USERNAME:
				return 'Login: mangler brukernavn';
			case AL_SC_PROCESSLOGIN_MISSING_PASSWORD:
				return 'Login: mangler passord';
			default:
				return 'Statusbeskjed er ikke definert';
		}
	}
	
	static function registerUser($username, $clearTXTpassword, $fullname ="noName", $emailAddress = NULL, $accessLevel = NULL)
	{
		if ( $emailAddress === NULL )
		{
			return FALSE;
		}
		
		if ( strlen($username) < 5 )
		{
			return FALSE;
		}
		
		if ( strlen($clearTXTpassword) < 8 )
		{
			return FALSE;
		}
		
		if ( $accessLevel == NULL )
		{
			$accessLevel = static::$accessLevelUser;
		}
		
		$authLibUser = new AuthLibUser();
		
		if ( $authLibUser->loadFromDbByNonNumericKey("Username", $username) )
		{
			die("User already exists");
			return FALSE;
		}
		
		$authLibUser = new AuthLibUser();
		$authLibUser->setUsername($username);
		$authLibUser->setClearTxtPassword($clearTXTpassword);
		$authLibUser->setFullname($fullname);
		$authLibUser->setEmailAddress($emailAddress);
		$authLibUser->setAccessLevel($accessLevel);

		
		if ( $authLibUser->commitToDb() )
		{
	
			return TRUE;
		}
		
		return FALSE;
	}
	
	
	
	static function checkSession() {
		//
		// Leser cookie hos bruker og oppdaterer timestamp i sqldb
		//

//		print_r($_SESSION);
		
		// Er ikke authLibUser satt i sesjonen har det skjedd en timeout..
		if ( !isset($_SESSION['authLibUser']) || !is_object($_SESSION['authLibUser']) )
		{
//			static::setStatusCode(AL_SC_CHECKSESSION_INVALID_SESSIONDATA);
			static::setStatusCode(AL_SC_CHECKSESSION_INVALIDLASTTIME);
			return FALSE;
		}
			
//		static::setAuthLibUser($_SESSION['authLibUser']);
			
		// TODO sikkerhetssjekker (browser agent+++)
/*			
  		if ( Sikkerhetssjekker ) 
		{
			static::setStatusCode(AL_SC_CHECKSESSION_SECURITYCHECKS_FAILED);
			return FALSE;
		}
*/
			
		// Sesjon verifisert OK..
		static::setStatusCode(AL_SC_CHECKSESSION_VALIDATED_OK);
		return TRUE;

		// ikke logget inn, returner feilmelding
//		static::setStatusCode(AL_SC_CHECKSESSION_FAILED_UNKNOWN);
//		return FALSE;
	}

	static function processLogout() {
		return session_destroy();
	}

	static function processLogin() {
		//
		// Utfører login
		//

		if ( !isset($_REQUEST['alUsername']) || empty($_REQUEST['alUsername']) ) {
			static::setStatusCode(AL_SC_PROCESSLOGIN_MISSING_USERNAME);
			return FALSE;
		}

		if ( !isset($_REQUEST['alPassword']) || empty($_REQUEST['alPassword']) ) {
			static::setStatusCode(AL_SC_PROCESSLOGIN_MISSING_PASSWORD);
			return FALSE;
		}

		// Authenticate
		if ( ($authLibUser = static::authUser(strtolower($_REQUEST['alUsername']), $_REQUEST['alPassword'])) === FALSE ) {
			return FALSE;
		}
		
		$_SESSION['authLibUser'] = $authLibUser;
		$_SESSION['loginTime'] = time();
		
		return TRUE;
	}

	static function authUser($userName, $passWord) {
		
		$authLibUser = static::loadAuthLibUserByUserName($userName);
		
		if ( $authLibUser == NULL )
		{
			return FALSE;
		}
//		elseif ( $authLibUser->isConfirmed() !== TRUE )
//		{
//			static::setStatusCode(AL_SC_AUTHUSER_FAILED_NOT_CONFIRMED);
//			return FALSE;
//		}
		elseif ( $authLibUser->checkPassword($passWord) === TRUE )
		{
			return $authLibUser;
		}

		static::setStatusCode(AL_SC_AUTHUSER_FAILED_UNKNOWN);
		return FALSE;
	}
	
	static function loadAuthLibUserByUserName($userName)
	{
		$authLibUser = new AuthLibUser();
		
		if ( !$authLibUser->loadFromDbByNonNumericKey("Username", $userName) )
		{
			static::setStatusCode(AL_SC_AUTHUSER_FAILED_NOT_FOUND);
			return NULL;
		}
		else
		{
			return $authLibUser;
		}
	}
	
	static function getUsername()
	{
		if ( !isset($_SESSION['authLibUser']) )
		{
			return "Anonym";
		}
		else
		{
			return $_SESSION['authLibUser']->getUsername();
		}		
		return static::getUser();
	}
	
	static function getFullname()
	{
		if ( !isset($_SESSION['authLibUser']) )
		{
			return "Anonym";
		}
		else
		{
			return $_SESSION['authLibUser']->getFullname();
		}
		
	}
	
	static function getAccessLevel()
	{
		if ( !isset($_SESSION['authLibUser']) )
		{
			return static::$accessLevelAnonymous;
		}
		else
		{
			return $_SESSION['authLibUser']->getAccessLevel();
		}
	}

	/*
	 * Returnerer TRUE hvis innlogget bruker har bruker-rettigheter eller høyere
	 */
	static function isUser()
	{
		return static::checkAccessLevel(static::$accessLevelUser);
	}
	
	/*
	 * Returnerer TRUE hvis innlogget bruker har admin-rettigheter
	 */
	static function isAdmin()
	{
		return static::checkAccessLevel(static::$accessLevelAdmin);
	}
	
	/*
	 * Sjekker aksessnivået til brukeren
	 */
	static function checkAccessLevel($level)
	{
			// Sjekk om vi har ett gyldig
		if ( static::getAccessLevel() <= 0 )
		{
			return FALSE;
		}
		
		// Sjekk nivået
		if ( static::getAccessLevel() == intval($level) )
		{
			return TRUE;
		}
		
		return FALSE;
	}
	
	/*
	 * Sjekker om aksessnivået til den innloggde brukeren er minst $level
	*/
	static function checkAccessLevelEqualOrBetter($level)
	{
		// Sjekk om vi har ett gyldig
		if ( static::getAccessLevel() <= 0 )
		{
			return FALSE;
		}
		
		// Sjekk nivået
		if ( static::getAccessLevel() <= intval($level) )
		{
			return TRUE;
		}
	
		return FALSE;
	}
	
	
	/*
	 * returnerer brukerID for innlogget bruker..
	 */
	static function getUserId()
	{
		
		if ( !isset($_SESSION['authLibUser']) || !is_object($_SESSION['authLibUser']) )
		{
			return -1;
		}
	
		if ( $_SESSION['authLibUser']->getAuthLibUser() === NULL )
		{
			return -2;
		}
		
		return $_SESSION['authLibUser']->getAuthLibUser()->getDbId();
	}	
	
	/*
	 * Genererer en random string, default lengde er 20 tegn
	 * 
	 * Tegn inkludert:
	 * 	
	 */
	static function genRandomString($len = 20)
	{
		$randStr = "";
		
		for ( $i=0; $i<$len; $i++ ) {
			switch ( rand(1,3) ) {
				case 1:
					// A-Z
					$randStr .= chr(rand(65,90));
					break;
				case 2:
					// a-z
					$randStr .= chr(rand(97,122));
					break;
				case 3:
					// 0-9
					$randStr .= chr(rand(48,57));
					break;
			}
		}
		
		return $randStr;
	}
}

?>
