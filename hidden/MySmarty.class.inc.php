<?php

require_once(dirname(__FILE__) . "/Smarty-3.1.13/Smarty.class.php");
require_once(dirname(__FILE__) . "/config.inc.php");

class MySmarty extends Smarty {
	function __construct($tpldir=NULL) {
		parent::__construct();

		$this->template_dir = dirname(__FILE__) . "/templates/";
		$this->compile_dir = dirname(__FILE__) . '/templates_compile/';
		$this->config_dir = dirname(__FILE__) . '/configs/';
		$this->cache_dir = dirname(__FILE__) . '/cache/';
		
		$this->assign('scriptURL', Base::getScripturl());
		
		$this->assign('isUser', AuthLib::isUser());
		$this->assign('isAdmin', AuthLib::isAdmin());
		
		if ( AuthLib::isUser() )
		{
			$this->assign('fullname', AuthLib::getFullname());
			$this->assign('username', AuthLib::getUsername());
			$this->assign('accesslevel', AuthLib::getAccessLevelAsStr());
		}
		else
		{
			$this->assign('fullname', "");
			$this->assign('username', "");
			$this->assign('accesslevel', "");
		}
		
		$this->caching = false;
	}
}

?>