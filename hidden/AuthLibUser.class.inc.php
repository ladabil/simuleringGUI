<?php

/*
 * AuthLibUser
*
* Runar Lyngmo - Gruppe 2 - 5/5-2013
*
*/

require_once(dirname(__FILE__) . "/Base.class.inc.php");

class AuthLibUser extends Base
{
	public static $tableName = "AuthLibUser";
	
	var $_username = "";
	var $_password = "";
	var $_fullname = "";
	var $_accessLevel = "";
	var $_emailAddress = "";
	var $_passwordSalt = "";
	
	static function createTableIfNotExists() {
		$query = "CREATE TABLE IF NOT EXISTS `" . static::$tableName . "` (
							`" . static::$tableName . "Id` int(11) NOT NULL auto_increment,
							`Username` varchar(250),
							`Password` varchar(250),
							`PasswordSalt` varchar(250),
							`Fullname` varchar(250),
							`AccessLevel` integer(11) default " . AuthLib::$accessLevelAnonymous . ",
							`EmailAddress` varchar(250),
							`TimeCreated` datetime default NULL,
							`TimeUpdated` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
						PRIMARY KEY(`" . static::$tableName . "Id`),
						INDEX `Username` (`Username`),
						INDEX `EmailAddress` (`EmailAddress`)
								
						) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
					";
			
		echo "<pre>" . $query . "</pre>\n";
			
		if ( !Base::getMysqli()->query($query) )
		{
			die(Base::getMysqli()->error);
		}
	}
	
	function getBodyForInsertOrUpdate()
	{
		return "
						`" . static::$tableName . "`
				SET
						`Username` = '" . $this->getUsername() . "',
						`Password` = '" . $this->getPassword() . "',
						`PasswordSalt` = '" . $this->getPasswordSalt() . "',
						`Fullname` = '" . $this->getFullname() . "',
						`AccessLevel` = " . intval($this->getAccessLevel()) . ",
						`EmailAddress` = '" . $this->getEmailAddress() . "'
				";
	}	
	
	
	/*
	 * Autentisering og passordfunksjoner for brukeren
	 */
	
	/*
	 * Metode som tar et klartekstpassord som input, og generer ny password salt og hasher passordet i sha256
	*/
	function setClearTxtPassword($value)
	{
		$this->setPasswordSalt(AuthLib::genRandomString(150));
		$this->setPassword($this->getHash($value));
		
		echo "Hash: " . $this->getHash($value) . "<br>\n";
	}
	
	/*
	 * Metode som hasher passordet med saltet til brukeren.
	 */
	function getHash($clearTxtPassword)
	{
		return hash("sha256", $this->getPasswordSalt() . $clearTxtPassword);
	}
	
	/*
	 * Metode som sjekker om passordet er korrekt
	 */
	function checkPassword($clearTxtPassword)
	{
		if ( strcmp($this->getPassword(), $this->getHash($clearTxtPassword)) == 0 )
		{
			return TRUE;
		}
		
		return FALSE;
	}
	
	
	/*
	 * Diverse aksessmetoder
	 */
	
	
	// Diverse brukervariabler
	function setEmailAddress($value)
	{
		$this->_emailAddress = $value;
	}
	
	function getEmailAddress()
	{
		return $this->_emailAddress;
	}
	
	function setUsername($value)
	{
		$this->_username = $value;
	}
	
	function getUsername()
	{
		return $this->_username;
	}
		
	function setPassword($value)
	{
		$this->_password = $value;
	}
	
	function getPassword()
	{
		return $this->_password;
	}
	
	function setPasswordSalt($value)
	{
		$this->_passwordSalt = $value;
	}
	
	function getPasswordSalt()
	{
		return $this->_passwordSalt;
	}
	
	function setFullname($value)
	{
		$this->_fullname = $value;
	}
	
	function getFullname()
	{
		return $this->_fullname;
	}
	
	function setAccessLevel($value)
	{
		$this->_accessLevel = $value;
	}
	
	function getAccessLevel()
	{
		return $this->_accessLevel;
	}
	
}

?>