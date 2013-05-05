<?php

/*
 * AuthLibSession
 * 
 * Runar Lyngmo - Gruppe 2 - 5/5-2013
 *
 */
class AuthLibSession extends Base
{
	public static $tableName = "AuthLibSession";
	
	static function createTableIfNotExists() {
		$query = "CREATE TABLE IF NOT EXISTS `" . static::$tableName . "` (
							`" . static::$tableName . "Id` int(11) NOT NULL auto_increment,
							`SID` varchar(250),
							`Username` varchar(250),
							`UserId` int(11) default NULL,
							`LoginDateTime` datetime default NULL,
							`LastuseDateTime` datetime default NULL,
							`TimeCreated` datetime default NULL,
							`TimeUpdated` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
						PRIMARY KEY(`" . static::$tableName . "Id`),
						INDEX `SID` (`SID`),
						INDEX `UserId` (`UserId`),
	
						FOREIGN KEY
							(`UserId`)
							REFERENCES `AuthLibUser` (`AuthLibUserId`)
								ON DELETE CASCADE
								ON UPDATE CASCADE
	
						) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
					";
			
		echo "<pre>" . $query . "</pre>\n";
			
		if ( !Base::getMysqli()->query($query) )
		{
			die(Base::getMysqli()->error);
		}
	}	
	
	function loadFromDbByUserNameAndSid($userName, $Sid)
	{
		$query = "
					SELECT * FROM 
						`" . static::$tableName . "`
					WHERE
						`Username` = '" . $userName . "'
						AND
						`SID` = '" . $Sid . "'
					LIMIT 1
				";
		
		$res = $this->doQuery($query, SQL_DOQUERY_ONE);
		
		if ( $res === FALSE )
		{
			return FALSE;
		}
		
		return $this->setFromResult($res);
	}
	
	function getBodyForInsertOrUpdate()
	{
		return "
						`" . static::$tableName . "`
				SET
						`Username` = '" . $this->getUsername() . "',
						`SID` = '" . $this->getSID() . "',
						`UserId` = '" . $this->getUserId() . "',
						`LoginDateTime` = '" . $this->getLoginDateTimeAsFormat(SQL_FORMAT_DATETIME) . "',
						`LastuseDateTime` = '" . $this->getLastuseDateTimeAsFormat(SQL_FORMAT_DATETIME) . "'
				";
	}
	
	function setSID($value)
	{
		$this->_sID = $value;
	}
	
	function getSID()
	{
		return $this->_sID;
	}
	
	function setUsername($value)
	{
		$this->_username = $value;
	}
	
	function getUsername()
	{
		return $this->_username;
	}
	
	function setLoginDateTime($value = NULL)
	{
		if ( $value == NULL )
			$value = 'now';
	
		$this->_loginDateTime = new DateTime($value);
	}	
	
	function getLoginDateTime()
	{
		if ( $this->_loginDateTime == NULL )
		{
			$this->setLoginDateTime();
		}
	
		return $this->_loginDateTime;
	}
	
	function getLoginDateTimeForWeb()
	{
		return $this->getLoginDateTime()->format(WWW_DATETIME_FORMAT_TITLE);
	}
	
	function getLoginDateTimeAsFormat($fmt = SQL_FORMAT_DATETIME)
	{
		return $this->getLoginDateTime()->format($fmt);
	}
	
	function setLastuseDateTime($value = NULL)
	{
		if ( $value == NULL )
			$value = 'now';
	
		$this->_lastuseDateTime = new DateTime($value);
	}
	
	function getLastuseDateTime()
	{
		if ( $this->_lastuseDateTime == NULL )
		{
			$this->setLastuseDateTime();
		}
	
		return $this->_lastuseDateTime;
	}
	
	function getLastuseDateTimeForWeb()
	{
		return $this->getLastuseDateTime()->format(WWW_DATETIME_FORMAT_TITLE);
	}
	
	function getLastuseDateTimeAsFormat($fmt = SQL_FORMAT_DATETIME)
	{
		return $this->getLastuseDateTime()->format($fmt);
	}
	
}
?>