<?php

/*
 * AuthLibUser
*
* Runar Lyngmo - Gruppe 2 - 5/5-2013
*
*/
class AuthLibUser extends Base
{
	public static $tableName = "AuthLibUser";
	
	var $_username = "";
	var $_password = "";
	var $_fullname = "";
	var $_accessLevel = "";
	var $_emailAddress = "";
	var $_isConfirmed = FALSE;
	var $_confirmCode = "";
	var $_passwordSalt = "";
	
	static function createTableIfNotExists() {
		$query = "CREATE TABLE IF NOT EXISTS `" . static::$tableName . "` (
							`" . static::$tableName . "Id` int(11) NOT NULL auto_increment,
							`Username` varchar(250),
							`Password` varchar(250),
							`PasswordSalt` varchar(250),
							`Fullname` varchar(250),
							`AccessLevel` integer(11) default " . AuthLib::$accessLevelAnonymous . ",
							`IsConfirmed` tinyint(1) default 0,
							`ConfirmCode` varchar(250),
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
						`IsConfirmed` = " . intval($this->isConfirmed()) . ",
						`ConfirmCode` = '" . $this->getConfirmCode() . "',
						`EmailAddress` = '" . $this->getEmailAddress() . "'
				";
	}	
	
	/*
	 * Bekreft / aktiveringsfunksjoner
	 */
	
	function sendConfirmMail()
	{
		$headers = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		
		$headers .= "To: " . $this->getFullname() . " <" . $this->getEmailAddress() . ">\r\n";
		$headers .= "From: " . $GLOBALS["cfg_blogname"] . " <" . $GLOBALS["cfg_blogmail"] . ">\r\n";
		
		mail(
				$this->getEmailAddress()
				,"Bekreft din konto"
				,"Vennligst bekreft din konto ved å klikke på følgende URL: <a href=\"" . Base::getScripturl(MyBlog::$funcConfirmUser, Array("ConfirmCode"=>$this->getConfirmCode())) . "\">aktiver</a>"
				,$headers
			);
		
//		echo "link: <a href=\"" . Base::getScripturl(MyBlog::$funcConfirmUser, Array("ConfirmCode"=>$this->getConfirmCode())) . "\">Aktiver</a>\n";
//		die();
	}
	
	function generateAndSendNewPassword()
	{
		$newPassword = AuthLib::genRandomString(12);
		
		$headers = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		
		$headers .= "To: " . $this->getFullname() . " <" . $this->getEmailAddress() . ">\r\n";
		$headers .= "From: " . $GLOBALS["cfg_blogname"] . " <" . $GLOBALS["cfg_blogmail"] . ">\r\n";
		
		 mail(
		 		$this->getEmailAddress()
		 		,"Nytt passord"
		 		,"Ditt nye passord er: " . $newPassword
		 		,$headers
		 );
		
		$this->setPassword($newPassword);
		$this->commitToDb();
		
//		echo "Nytt passord: '" . $newPassword . "'\n";
//		die();		
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
	
	// isConfirmed bestemmer om brukeren har aktivert / bekreftet kontoen via e-post
	function setIsConfirmed($confirmed = TRUE)
	{
		if ( intval($confirmed) == 1 )
		{
			$confirmed = TRUE;
		}
		
		if ( $confirmed !== TRUE )
		{
			$confirmed = FALSE;
		}
		
		$this->_isConfirmed = $confirmed;
	}
	
	function isConfirmed()
	{
		return $this->_isConfirmed;
	}

	// ConfirmCode - hemmelig kode som kommer på e-post til brukeren ved nyregistrering, denne benyttes for å bekrefte / aktivere kontoen
	function setConfirmCode($value)
	{
		$this->_confirmCode = $value;
	}
	
	function getConfirmCode()
	{
		return $this->_confirmCode;
	}
	
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