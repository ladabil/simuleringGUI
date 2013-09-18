<?php

define("SQL_FORMAT_DATETIME", "Y-m-d H:i:s");
define("WWW_DATETIME_DATEONLYFORMAT", "d.m.Y");
define("WWW_DATETIME_FORMAT", "d.m.Y H:i:s");
define("WWW_DATETIME_FORMAT_TITLE", "l \\d\\e\\n d. F, Y \k\l\. H:i:s");

define('SQL_DOQUERY_ZERO', 100);
define('SQL_DOQUERY_ONE', 200);
define('SQL_DOQUERY_POSITIVE', 300);
define('SQL_DOQUERY_ZERO_INVERTED', 400);
define('SQL_DOQUERY_NON_FAILED', 500);

define('LOGL_CRIT', 50);
define('LOGL_ERR', 100);
define('LOGL_INFO', 200);
define('LOGL_INF', LOGL_INFO);
define('LOGL_DBG99', 999);

define('SQL_AND', 1);
define('SQL_OR', 2);

require_once(dirname(__FILE__) . "/config.inc.php");

class Base
{
	var $_dbId = NULL;
	var $_createDateTime = NULL;
	var $_updateDateTime = NULL;
	
	var $_myDbHandler = NULL;
	
	var $_markForDeletion = FALSE;
	
	var $_userId = 0;
	
	const SQL_AND = 1;
	const SQL_OR = 2;
	
	function __construct($dbId = NULL)
	{
		$this->setDbId($dbId);
	}
	
	static function log($logLevel, $formatStr)
	{
		if ( func_num_args() < 3 ) {
			return FALSE;
		}
		
		$argArr[] = '%s - %s: ' . $formatStr;
		$argArr[] = date(WWW_DATETIME_FORMAT, time());
		$argArr[] = Base::getLogLevelName($logLevel);
	
		for ( $i=2; $i<func_num_args(); $i++) {
			$argArr[] = func_get_arg($i);
		}
	
		$debugStr = call_user_func_array('sprintf', $argArr);
		$errStr = '';
	
		if ( $logLevel <= Base::getLogLevelToShow() ) {
			if ( $logLevel == LOGL_ERR ) {
				$errStr = "<span style=\"color: red; font-size: 14px;\">" . $debugStr . "</span><br>\n";
			} else {
				$errStr = $debugStr . "<br>\n";
			}
		}
		
		if ( strlen($errStr) > 0 )
			echo $errStr;
		
		return TRUE;
	}
	
	static function getLogLevelToShow()
	{
		return LOGL_INFO;
	}
	
	static function getLogLevelName($logLevel)
	{
		switch ( $logLevel ) {
			case LOGL_CRIT:
				return 'Critical';
			case LOGL_ERR:
				return 'Error';
			case LOGL_INFO:
			case LOGL_INF:
				return 'Info';
			case LOGL_DBG99:
				return 'Debug99';
			default:
				return 'Ukjent';
		}
	
		return 'Ukjent';
	}
	
	static function getScripturl($func = NULL, $varArr = NULL) {
		$newurl = Base::getServerUrl();
		$newurl .= $_SERVER['SCRIPT_NAME'];
			
		$numvar = 0;
	
		if ( $func != NULL ) {
			if ( $numvar > 0 )
				$newurl .= '&';
			else
				$newurl .= '?';
				
			$newurl .= 'function=' . $func;
			$numvar++;
		}
			
		if ( isset($varArr) && is_array($varArr) ) {
			foreach ( $varArr as $key=>$value ) {
				if ( $numvar > 0 )
					$newurl .= '&';
				else
					$newurl .= '?';
					
				$newurl .= $key . '=' . $value;
				$numvar++;
			}
		}
			
		return $newurl;
	}
	
	static function getServerUrl() {
		$retstr = '';
	
		$port = NULL;
		
		if ( isset($_SERVER['HTTPS']) && strcmp($_SERVER['HTTPS'], 'on') == 0 ) {
			$retstr .= 'https://';
			
			if ( isset($_SERVER['SERVER_PORT']) && intval($_SERVER['SERVER_PORT']) != 443 )
			{
				$port = intval($_SERVER['SERVER_PORT']);
			}
		} else {
			$retstr .= 'http://';

			if ( isset($_SERVER['SERVER_PORT']) && intval($_SERVER['SERVER_PORT']) != 80 )
			{
				$port = intval($_SERVER['SERVER_PORT']);
			}
		}
	
		$retstr .= $_SERVER['SERVER_NAME'];
		
		if ( $port !== NULL ) 
		{
			$retstr .= ":" . intval($port);
		}
			
		return $retstr;
	}
	
	static function getDomainNameForCookie()
	{
		return $_SERVER['SERVER_NAME'];
	}
	
	// TODO: Fix
	static function getRelativePathForCookie()
	{
		return "/";
	}
	
	static  function redirectNow($func = NULL, $varArr = NULL) {
		$newurl = Base::getScripturl($func, $varArr);
		$numvar = 0;
	
		for ( $i=ob_get_level(); $i>0; $i-- ) {
			ob_end_clean();
		}
	
		header('Refresh: 0; URL=' . $newurl);
		die();
	}
	
	function getDbId()
	{
		return $this->_dbId;
	}
	
	function setDbId($dbId = NULL, $loadFromDb = TRUE)
	{
		$this->_dbId = $dbId;
		
		if (  $loadFromDb == TRUE && $dbId !== NULL && intval($dbId) >= 0 )
		{
			$this->loadFromDbById();
		}
	}
	
	function markForDeletion($doDelete = TRUE)
	{
		$this->_markForDeletion = $doDelete;
	}
	
	/*
	 * Skriver endringene til databasen
	 */
	function commitToDb()
	{
		if ( $this->_markForDeletion == TRUE )
		{
			if ( ( $this->getDbId() == NULL || intval($this->getDbId()) <= 0 ) )
			{
				return TRUE;
			}
			else
			{
				return $this->delete();
			}
		} 
		elseif ( $this->getDbId() == NULL || intval($this->getDbId()) <= 0 )
		{
			return $this->insert();
		}
		else
		{
			return $this->update();
		}
	}
	
	function doQuery($query, $mode = SQL_DOQUERY_ZERO) {
		if ( !isset($GLOBALS['numDoQuery']) ) {
			$GLOBALS['numDoQuery'] = 0;
		}
			
		$GLOBALS['numDoQuery']++;
			
		$res = Base::getMysqli()->query($query);
	
		if ( !$res ) {
			die(get_class($this) . ":" . __FUNCTION__ . ": " . Base::getMysqli()->error . " - Query: " . $query . "<br>\n");
		}
	
		if ( ($mode == SQL_DOQUERY_ZERO || $mode == SQL_DOQUERY_ZERO_INVERTED) && $res->num_rows == 0 ) {
			$res->free();
			if ( $mode == SQL_DOQUERY_ZERO_INVERTED ) {
				//				echo "Return TRUE<br>\n";
				return FALSE;
			} else {
				//				echo "Return TRUE<br>\n";
				return TRUE;
			}
		} else if ( $mode == SQL_DOQUERY_ONE && $res->num_rows == 1 ) {
			return $res;
		} else if ( $mode == SQL_DOQUERY_POSITIVE && $res->num_rows > 0 ) {
			return $res;
		} else if ( $mode == SQL_DOQUERY_NON_FAILED && $res->num_rows >= 0 ) {
			return $res;
		} else {
			$res->free();

			if ( $mode == SQL_DOQUERY_ZERO_INVERTED )
				return TRUE;
	
			return FALSE;
		}
			
		return FALSE;
	}
	
	function doInsert($query) {
		if ( !Base::getMysqli()->query($query) ) {
//			$this->writeDebug(LOGL_ERR,"%s(): INSERT:\n<pre>%s\n</pre>Feilet!<br>\nMysql-rapporterer: %s<br>\nAvbryter.",__FUNCTION__, $query, Base::getMysqli()->error );
			die("Query: " . $query . "<br>\r\nError: " . Base::getMysqli()->error);
		}
	
//		$this->setUnchanged();
		$this->setDbid($this->getLastid());
	
		return TRUE;
	}
	
	function doUpdate($query) {
		if ( !Base::getMysqli()->query($query) ) {
			die(get_class($this) . ":" . __FUNCTION__ . ": " . Base::getMysqli()->error . " - Query: " . $query . "<br>\n");
		}
	
//		$this->setUnchanged();
	
		return TRUE;
	}
	
	function doDelete($query) {
		if ( !Base::getMysqli()->query($query) ) {
			die(get_class($this) . ":" . __FUNCTION__ . ": " . Base::getMysqli()->error . " - Query: " . $query . "<br>\n");
		}
	
		return TRUE;
	}	
	
	function getLastid() {
		$query = "SELECT LAST_INSERT_ID()";
		$res = Base::getMysqli()->query($query);
	
		if ( !$res || $res->num_rows <= 0 ) {
			return FALSE;
		}
	
		$row = $res->fetch_row();
		return $row[0];
	}
	
	function delete()
	{
		$query = "DELETE FROM `" . static::$tableName . "` WHERE `" . static::$tableName . "Id`=" . $this->getDbid() . " LIMIT 1";
	
		return $this->doDelete($query);
	}
	
	function insert()
	{
		$query = "INSERT INTO
				" . $this->getBodyForInsertOrUpdate() . "
				";
	
		return $this->doInsert($query);
	}
	
	function update()
	{
		$query = "UPDATE
				" . $this->getBodyForInsertOrUpdate() . "
					WHERE
						`" . static::$tableName . "Id`=" . $this->getDbid() . "
					LIMIT 1
				";
	
		return $this->doDelete($query);
	}
	
	/*
	 * legge til standard kolonner og deres data
	 */
	function getBodyForInsertOrUpdate()
	{
		return "
		`TimeCreated` = '" . $this->getTimeCreatedAsStr(SQL_FORMAT_DATETIME) . "',
		`TimeUpdated` = '" . $this->getTimeUpdatedAsStr(SQL_FORMAT_DATETIME) . "',
		`UserId` = " . intval($this->getUserId(TRUE)) . "	
		";
	}
	
	/*
	 * Setter resultatet fra en mysqli-sp�rring til metoder p� meg selv med samme navn som kolonnene
	 */
	function setFromResult($res, $rowIdx=0)
	{
		if ( !$res ) 
		{
			die('Invalid res: ' . $res);
		}
		
		if ( $res->data_seek($rowIdx) === FALSE )
		{
			Base::log(LOGL_ERR, "%s::%s:%d: data_seek feilet (seek to: %d - numrows: %d)!", __CLASS__, __FUNCTION__, __LINE__, $rowIdx, $res->num_rows);
			die();
		}
		
		
		$row = $res->fetch_assoc();
		
		foreach ( $row as $columnName=>$value )
		{
			if ( strcasecmp($columnName, static::$tableName . "Id") == 0 )
			{
				$this->setDbId($value, FALSE);
				continue;
			}
			
			$methodName = "set" . $columnName;
			if ( !method_exists($this, $methodName) )
			{
				Base::log(LOGL_ERR, "%s::%s:%d: Fant ikke metoden %s i klassen %s.. legger til tomt felt!", __CLASS__, __FUNCTION__, __LINE__, $methodName, get_class($this));
			}
			else
			{
				call_user_func(array($this, $methodName), $value);
			}
		}
	}
	
	/*
	 * Sletter tabellen jeg representerer
	 */
	static function dropTable()
	{
		$query = "DROP TABLE `" . static::$tableName . "`";
		
		if ( !Base::getMysqli()->query($query) )
		{
			echo "Error: " . Base::getMysqli()->error . "<br>\n";
			return FALSE;
		}
		
		return TRUE;
	}
	

	/*
	 * Returner et mysqli-objekt.
	 * 
	 * Hvis det ikke eksisterer fra f�r, opprett det og koble til
	 * Finnes det et eksisterende, returner det.
	 */
	
	static function getMysqli()
	{
		if ( !isset($GLOBALS["mysqli"]) || !is_object($GLOBALS["mysqli"]) )
		{
			$GLOBALS["mysqli"] = new mysqli($GLOBALS["mysqli_hostname"], $GLOBALS["mysqli_username"], $GLOBALS["mysqli_password"], $GLOBALS["mysqli_database"]);
			
			if ($GLOBALS["mysqli"]->connect_errno) {
				die("Failed to connect to MySQL: (" . $GLOBALS["mysqli"]->connect_errno . ") " . $GLOBALS["mysqli"]->connect_error);
			}
		}
		
		return $GLOBALS["mysqli"];
	}
	
	function getTableName()
	{
		return self::$tableName;
	}
	
	/*
	 * Felleskolonner / n�kler for alle tabeller i databasen
	 */
	function setTimeCreated($createDateTime = NULL)
	{
		if ( $createDateTime == NULL )
			$createDateTime = 'now';
	
		$this->_createDateTime = new DateTime($createDateTime);
	}
	
	function getTimeCreated()
	{
		if ( $this->_createDateTime == NULL )
		{
			$this->setTimeCreated();
		}
	
		return $this->_createDateTime;
	}
	
	function getTimeCreatedForWeb()
	{
		return $this->getTimeCreatedAsStr(WWW_DATETIME_FORMAT_TITLE);
	}
	
	function getTimeCreatedAsStr($fmt = WWW_DATETIME_FORMAT_TITLE)
	{
		if ( $this->_createDateTime == NULL )
		{
			$this->setTimeCreated();
		}
		
		return $this->_createDateTime->format($fmt);
	}
	
	function setTimeUpdated($updateDateTime = NULL)
	{
		if ( $updateDateTime == NULL )
			$updateDateTime = 'now';
	
		$this->_updateDateTime = new DateTime($updateDateTime);
	}
	
	function getTimeUpdated()
	{
		if ( $this->_updateDateTime == NULL )
		{
			$this->setTimeUpdated();
		}
	
		return $this->_updateDateTime;
	}
	
	function getTimeUpdatedAsStr($fmt = WWW_DATETIME_FORMAT_TITLE)
	{
		if ( $this->_updateDateTime == NULL )
		{
			$this->setTimeUpdated();
		}
	
		return $this->_updateDateTime->format($fmt);
	}
	
	function setUserId($userId)
	{
		$this->_userId = $userId;
	}
	
	function getUserId($getDefaultIfEmpty = FALSE)
	{
		// quickfix: hvis 
		if ( intval($this->_userId) <= 0 && $getDefaultIfEmpty == TRUE )
		{
			return AuthLib::getUserId();
		}
		
		return $this->_userId;
	}	
	
	function getAuthLibUserByUserId($userId)
	{
		require_once(dirname(__FILE__) . "/AuthLibUser.class.inc.php");
// 		echo "<pre>";
		$alu = new AuthLibUser(NULL, $userId);
// 		print_r($alu);
// 		die($userId);
		return $alu;
	}
	
	function getAuthLibUser()
	{
		return $this->getAuthLibUserByUserId($this->getUserId());
	}
	
	/*
	 * Standard load-functions
	 */
	function loadFromDbById()
	{
		$query = "SELECT
						*
					FROM
						`" . static::$tableName . "`
					WHERE
						`" . static::$tableName . "`.`" . static::$tableName . "Id`=" . $this->getDbId() . "
					";
		
		if ( ($res = $this->doQuery($query, SQL_DOQUERY_ONE) )=== FALSE )
		{
			return NULL;
		}
		
		$this->setFromResult($res);
		
		return $this->getDbId();
	}
	
	function loadFromDbByNonNumericKey($key, $value)
	{
		$query = "SELECT
						*
					FROM
						`" . static::$tableName . "`
					WHERE
						`" . static::$tableName . "`.`" . $key . "`='" . $value . "'
					LIMIT 1
					";
		
		if ( ($res = $this->doQuery($query, SQL_DOQUERY_ONE)) === FALSE )
		{
			return NULL;
		}
		
		$this->setFromResult($res);
		
		return $this->getDbId();
	}
	
	function loadFromDbByNonNumericKeyAndValue()
	{
		if ( func_num_args() < 2 ) {
			return FALSE;
		}
		
		/*
		0=1
		2 3=4
		5 6=7
		8 9=10
		11 12=13
		
		numargs = 14
		i = 11
		i + 3 = 14
		 */
		
		$numArgs = func_num_args();
		
		$where = "";
		
		for ( $i=0; $i<$numArgs; $i+=3) {
/*			echo "\$i=" . $i . "<br>\n";
			
			echo "i: " . func_get_arg($i) . "<br>\n";
			echo "i+1: " . func_get_arg($i+1) . "<br>\n";
			echo "i+2: " . func_get_arg($i+2) . "<br>\n";
			
			echo "i+4= " . ($i + 3) . "<br>\n";
			echo "numArgs=" . $numArgs . "<br>\n";	
			echo "(i - 2)%3=" . $i % 3 . "<br>\n";	
*/			
			if ( $i > 0 && (($i + 1) % 3 ) == 0 )
			{
				if ( ($i + 3) > $numArgs)
				{
					break;
				}
				
				// Operand
				switch ( func_get_arg($i) )
				{
					case Base::SQL_OR:
						$where .= "OR";
						break;
					case Base::SQL_AND:
					default:
						$where .= "AND";
						break;
				}
			}

			if ( $i == 0 )
			{
				$i = -1;
			}
				
			$where .= " `" . func_get_arg($i + 1) . "`='" . func_get_arg($i + 2) . "' ";
		}
		
		
		$query = "SELECT
						*
					FROM
						`" . static::$tableName . "`
					WHERE
						" . $where . "
					";
		
		//echo $query;
		
		if ( ($res = $this->doQuery($query, SQL_DOQUERY_ONE) ) === FALSE )
		{
			return NULL;
		}
		
		$this->setFromResult($res);
		
		return $this->getDbId();
						
	}
	
	/*
	 * tokens security functions
	 */
	
	// Generer en token
	static function getToken($functionName = "DefaultFunction")
	{
		if ( !isset($GLOBALS["cfg_tokentype"]) || strlen($GLOBALS["cfg_tokentype"]) <= 0 )
		{
			$type = "sha256";
		}
		else
		{
			$type = $GLOBALS["cfg_tokentype"];
		}

		if ( !isset($GLOBALS["cfg_tokensecret"]) || strlen($GLOBALS["cfg_tokensecret"]) <= 0 )
		{
			$secret = "defaultNonSecureToken";
		}
		else
		{
			$secret = $GLOBALS["cfg_tokensecret"];
		}
		
		if ( strlen($functionName) <= 0 )
		{
			die("getToken(): Invalid functionName");
		}
		
		return hash_hmac($type , $functionName + $secret , session_id());
	}
	
	// Sjekk en token mot den vi generere i getToken..
	static function verifyToken($token, $functionName = "DefaultFunction")
	{
		$correctToken = static::getToken($functionName);
		
		if ( strcmp($correctToken, $token) == 0 )
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}
	
	// Sjekker en token mot en request variabel ($GLOBALS["cfg_tokenName"]).
	// D�r ved feil i token..
	
	static function verifyTokenFromRequest($functionName = "DefaultFunction")
	{
		if ( !isset($_REQUEST[$GLOBALS["cfg_tokenName"]]) || strlen($_REQUEST[$GLOBALS["cfg_tokenName"]]) <= 0 )
		{
			die("Token (" . $GLOBALS["cfg_tokenName"] . ") not set");
		}
		
		if ( static::verifyToken($_REQUEST[$GLOBALS["cfg_tokenName"]], $functionName) !== TRUE )
		{
			die("HACK STOPPED!");
		}
		
		return TRUE;
	}
	
	// Returner input hidden for web-skjema..
	static function getTokenforFORM($functionName = "DefaultFunction")
	{
		return '<input type="hidden" name="' . $GLOBALS["cfg_tokenName"] . '" value="' . static::getToken($functionName) . '" />';
	}
	
}

?>