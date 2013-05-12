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

define('AL_SC_PROCESSLOGIN_MISSING_USERNAME', 500);
define('AL_SC_PROCESSLOGIN_MISSING_PASSWORD', 501);

require_once(dirname(__FILE__) . "/Base.class.inc.php");
require_once(dirname(__FILE__) . "/AuthLibUser.class.inc.php");
require_once(dirname(__FILE__) . "/AuthLibSession.class.inc.php");

/* 
 * Security-TODO legg til domene og path i setcookie
 */
 
class AuthLib {
	var $_sqlfd;
	var $_usr;
	var $_pwd;
	var $_sid;
	var $_ldapds = NULL;
	var $_statusCode = NULL;
	var $_authLibUser = NULL;

	public static $accessLevelAdmin = 1;
	public static $accessLevelUser = 100;
	public static $accessLevelAnonymous = 1000;
	
	public static $sessionTimeout = 180;
	
	function __construct() {
	}
	
	function setStatusCode($statusCode)
	{
		$this->_statusCode = intval($statusCode);
	}
	
	function getStatusCode()
	{
		return $this->_statusCode;
	}
	
	function setAuthLibUser($value)
	{
		$this->_authLibUser = $value;
	}
	
	function getAuthLibUser()
	{
		return $this->_authLibUser;
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
	
	function getAccessLevelAsStr()
	{
		return static::getAccessLevelStr($this->getAccessLevel());
	}
	
	function getStatusMessage()
	{
		switch ( $this->_statusCode )
		{
			case AL_SC_CHECKSESSION_NOCOOKIE:
				return 'Sesjonsverifisering: fant ikke cookie';
			case AL_SC_CHECKSESSION_NOTFOUNDINDBTABLE:
				return 'Sesjonsverifisering: fant ikke sesjon i databasen';
			case AL_SC_CHECKSESSION_TOOMANYRESULTS:
				return 'Sesjonsverifisering: for mange treff i databasen';
			case AL_SC_CHECKSESSION_INVALIDLASTTIME:
				return 'Sesjonsverifisering: Timeout, vennligst logg inn på nytt igjen';
			case AL_SC_CHECKSESSION_FAILED_UNKNOWN:
				return 'Sesjonsverifisering: ukjent feil';
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
	
	static function registerUser($username, $clearTXTpassword, $fullname ="noName", $emailAddress = NULL, $accessLevel = NULL, $isConfirmed = FALSE)
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
			$accessLevel = AuthLib::$accessLevelUser;
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
		$authLibUser->setConfirmCode(AuthLib::genRandomString(50));
		$authLibUser->setIsConfirmed($isConfirmed);
		
		if ( $authLibUser->commitToDb() )
		{
			if ( $isConfirmed === FALSE )
			{
				$authLibUser->sendConfirmMail();
			}
			
			return TRUE;
		}
		
		return FALSE;
	}
	
	static function confirmUser($confirmCode)
	{
		if ( strlen($confirmCode) < 10 )
		{
			die('Invalid confirmcode');
		}
		
		$authLibUser = new AuthLibUser();
		
		if ( $authLibUser->loadFromDbByNonNumericKey("ConfirmCode", $confirmCode) !== NULL )
		{
			$authLibUser->setIsConfirmed(TRUE);
			
			if ( $authLibUser->commitToDb() )
			{
				return TRUE;
			}
		}
		
		return FALSE;
	}
	
	static function genAndSendNewPasswordByEmailAddress($emailAddress)
	{
		if ( strlen($emailAddress) < 5 )
		{
			return "ugyldig e-postadresse";
		}
	
		$authLibUser = new AuthLibUser();
	
		if ( $authLibUser->loadFromDbByNonNumericKey("EmailAddress", $emailAddress) !== NULL )
		{
			$authLibUser->generateAndSendNewPassword();
				
			if ( $authLibUser->commitToDb() )
			{
				return TRUE;
			}
		}
	
		return "Fant ikke brukeren";
	}	

	static function genAndSendNewPasswordByUsername($username)
	{
		if ( strlen($username) < 5 )
		{
			return "ugyldig brukernavn";
		}
	
		$authLibUser = new AuthLibUser();
	
		if ( $authLibUser->loadFromDbByNonNumericKey("Username", $username) !== NULL )
		{
			$authLibUser->generateAndSendNewPassword();
	
			if ( $authLibUser->commitToDb() )
			{
				return TRUE;
			}
		}
	
		return "Fant ikke brukeren";
	}	
	
	function checkSession() {
		//
		// Leser cookie hos bruker og oppdaterer timestamp i sqldb
		//

		if ( !isset($_SESSION['user']) || !is_array($_COOKIE['user']) ) {
			$this->setStatusCode(AL_SC_CHECKSESSION_NOCOOKIE);
			return FALSE;
		}
		
		$this->_usr = strtolower($_SESSION['user']);
		
		if ( isset($_SESSION['name']) ) {
			$this->_name = $_SESSION['name'];
		} else {
			$this->_name = '';
		}
		
		if ( $_SESSION['isLoggedIn'] == TRUE )
		{
			if ( $_SESSION['lastUseTimeAsLong'] > (time() - static::$sessionTimeout) )
			{
				$_SESSION['lastUseTimeAsLong'] = time();
				$this->setStatusCode(AL_SC_CHECKSESSION_VALIDATED_OK);
				return TRUE;
			}
		}
		
		setcookie("authlib[user]", NULL, -1, Base::getRelativePathForCookie(), Base::getDomainNameForCookie());
		setcookie("authlib[sid]", NULL, -1, Base::getRelativePathForCookie(), Base::getDomainNameForCookie());
		
		$this->setStatusCode(AL_SC_CHECKSESSION_INVALIDLASTTIME);
		return FALSE;
	}

	function getUser() {
		return $this->_usr;
	}

	function deleteSession() {
		//
		// Leser cookie hos bruker og oppdaterer timestamp i sqldb
		//

		if ( !isset($_COOKIE['authlib']) || !is_array($_COOKIE['authlib']) ) {
			return FALSE;
		}
		
		$this->_usr = strtolower($_COOKIE['authlib']['user']);
		$this->_sid = $_COOKIE['authlib']['sid'];
		if ( isset($this->_COOKIE['authlib']['name']) ) {
			$this->_name = $_COOKIE['authlib']['name'];
		} else {
			$this->_name = '';
		}

		$authLibSession = new AuthLibSession();
		
		if ( $authLibSession->loadFromDbByNonNumericKeyAndValue("Username", $this->_usr, Base::SQL_AND, "SID", $this->_sid) == NULL )
		{
			$this->setStatusCode(AL_SC_CHECKSESSION_NOTFOUNDINDBTABLE);
			return FALSE;
		}
		
		$authLibSession->markForDeletion();
		setcookie("authlib[user]", NULL, -1, Base::getRelativePathForCookie(), Base::getDomainNameForCookie());
		setcookie("authlib[sid]", NULL, -1, Base::getRelativePathForCookie(), Base::getDomainNameForCookie());

		return $authLibSession->commitToDb();
	}

	function processLogout() {
		return $this->deleteSession();
	}

	function processLogin() {
		//
		// Utfører login
		//

		if ( !isset($_REQUEST['alUsername']) || empty($_REQUEST['alUsername']) ) {
			$this->setStatusCode(AL_SC_PROCESSLOGIN_MISSING_USERNAME);
			return FALSE;
		}

		if ( !isset($_REQUEST['alPassword']) || empty($_REQUEST['alPassword']) ) {
			$this->setStatusCode(AL_SC_PROCESSLOGIN_MISSING_PASSWORD);
			return FALSE;
		}

		$this->_usr = strtolower($_REQUEST['alUsername']);
		$this->_pwd = $_REQUEST['alPassword'];

		// Authenticate

		if ( $this->authUser() != TRUE ) {
			return FALSE;
		}

		if ( $this->createSession() != TRUE) {
			return FALSE;
		}

		return TRUE;
	}

	function authUser() {
		$authLibUser = new AuthLibUser();
		
		if ( !$this->loadAuthLibUser() )
		{
			$this->setStatusCode(AL_SC_AUTHUSER_FAILED_NOT_FOUND);
			return FALSE;
		}
		elseif ( $this->getAuthLibUser()->isConfirmed() !== TRUE )
		{
			$this->setStatusCode(AL_SC_AUTHUSER_FAILED_NOT_CONFIRMED);
			return FALSE;
		}
		elseif ( $this->getAuthLibUser()->checkPassword($this->_pwd) === TRUE )
		{
			return TRUE;
		}
		else
		{
			$this->setStatusCode(AL_SC_AUTHUSER_FAILED_UNKNOWN);
			return FALSE;
		}
	}
	
	function loadAuthLibUser()
	{
		$authLibUser = new AuthLibUser();
		
		if ( !$authLibUser->loadFromDbByNonNumericKey("Username", $this->_usr) )
		{
			$this->setStatusCode(AL_SC_AUTHUSER_FAILED_NOT_FOUND);
			$this->setAuthLibUser(NULL);
			return FALSE;
		}
		else
		{
			$this->setAuthLibUser($authLibUser);
			return TRUE;
		}
	}
	
	function getUsername()
	{
		return $this->getUser();
	}
	
	function getFullname()
	{
		if ( $this->getAuthLibUser() === NULL )
		{
			return "Anonym";
		}
		else
		{
			return $this->getAuthLibUser()->getFullname();
		}
		
	}
	
	function getAccessLevel()
	{
		if ( $this->getAuthLibUser() === NULL )
		{
			return static::$accessLevelAnonymous;
		}
		else
		{
			return $this->getAuthLibUser()->getAccessLevel();
		}
	}

	function createSession() {
		//
		// Oppretter session i sqldb og setter cookie hos bruker
		//

		$this->deleteOldSessions();

		$sid = "";

		for ( $i=1; $i<20; $i++ ) {
			switch ( rand(1,3) ) {
				case 1:
					$sid .= chr(rand(65,90));
					break;
				case 2:
					$sid .= chr(rand(97,122));
					break;
				case 3:
					$sid .= chr(rand(48,57));
					break;
			}
		}

		$authLibSession = new AuthLibSession();
		$authLibSession->setSID($sid);
		$authLibSession->setUsername($this->getUser());
		$authLibSession->setUserId($this->getAuthLibUser()->getDbId());
		$authLibSession->setLastuseDateTime("now");
		$authLibSession->setLoginDateTime("now");
		
		if ( $authLibSession->commitToDb() == TRUE && intval($authLibSession->getDbId()) > 0 )
		{
			setcookie("authlib[user]", strtolower($this->_usr), time() + 64800, Base::getRelativePathForCookie(), Base::getDomainNameForCookie());
			setcookie("authlib[sid]", $sid, time() + 64800, Base::getRelativePathForCookie(), Base::getDomainNameForCookie());
			$this->_sid = $sid;
			$this->setStatusCode(AL_SC_CREATESESSION_OK);
			return TRUE;
		}
		else
		{
			$this->setStatusCode(AL_SC_CREATESESSION_FAILED_DATABASE);
			return FALSE;
		}
		
		$this->setStatusCode(AL_SC_CREATESESSION_FAILED_UNKNOWN);
		return FALSE;
	}

	function deleteOldSessions() {
		//
		// Slette evt. gamle sesjoner for brukeren
		//

		// TODO: implement
		
		return TRUE;
	}

	function return_loginform() {
		$tpl = new Smarty_Intranett('templates');

		$tpl->caching = false;

		$tpl->assign('tittel', 'Logg på');

		$tpl->assign('STATUSMSG_LABEL', 'Feilmelding');
		$tpl->assign('USERNAME_LABEL', 'Brukernavn');
		$tpl->assign('PASSWORD_LABEL', 'Passord');

		if ( isset($_REQUEST['al_username']) ) {
			$tpl->assign('al_username', $_REQUEST['al_username']);
		}

//		if ( isset($_REQUEST['al_passwd']) ) {
//			$tpl->assign('al_passwd', $_REQUEST['al_passwd']);
//		}

		$tpl->assign('al_passwd', '');
		
		if ( $this->getStatusCode() != 500 && $this->getStatusCode() != 501 && $this->getStatusCode() != AL_SC_CHECKSESSION_NOCOOKIE && $this->getStatusCode() !== NULL )
		{
			$tpl->assign('al_statusmsg', $this->getStatusMessage());
		}
		else
		{
			$tpl->assign('al_statusmsg', '');
		}
		
		$tpl->assign('submit_text','Logg inn');

		return $tpl->fetch('authlib_loginform.tpl');
	}
	
	static function checkAccessLevel($level)
	{
		if ( !isset($GLOBALS["authlib"]) || !is_object($GLOBALS["authlib"]) )
		{
			return FALSE;
		}
	
		if ( $GLOBALS["authlib"]->getAccessLevel() == $level )
		{
			return TRUE;
		}
	
		return FALSE;
	}
	
	/*
	 * Returnerer TRUE hvis innlogget bruker har bruker-rettigheter eller høyere
	 */
	static function isUser()
	{
		return ( AuthLib::checkAccessLevel(AuthLib::$accessLevelAdmin) || AuthLib::checkAccessLevel(AuthLib::$accessLevelUser) );
	}
	
	/*
	 * Returnerer TRUE hvis innlogget bruker har admin-rettigheter
	 */
	static function isAdmin()
	{
		return AuthLib::checkAccessLevel(AuthLib::$accessLevelAdmin);
	}
	
	/*
	 * returnerer brukerID for innlogget bruker..
	 */
	static function getUserId()
	{
		
		if ( !isset($GLOBALS["authlib"]) || !is_object($GLOBALS["authlib"]) )
		{
			return -1;
		}
	
		if ( $GLOBALS["authlib"]->getAuthLibUser() === NULL )
		{
			return -2;
		}
		
		return $GLOBALS["authlib"]->getAuthLibUser()->getDbId();
	}	
	static function genRandomString($len = 20)
	{
		$randStr = "";
		
		for ( $i=0; $i<$len; $i++ ) {
			switch ( rand(1,3) ) {
				case 1:
					$randStr .= chr(rand(65,90));
					break;
				case 2:
					$randStr .= chr(rand(97,122));
					break;
				case 3:
					$randStr .= chr(rand(48,57));
					break;
			}
		}
		
		return $randStr;
	}
}

?>
