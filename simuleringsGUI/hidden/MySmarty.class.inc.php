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
		
		$this->assign('fullname', $GLOBALS["authlib"]->getFullname());
		$this->assign('username', $GLOBALS["authlib"]->getUsername());
		$this->assign('accesslevel', $GLOBALS["authlib"]->getAccessLevelAsStr());
		
		$this->caching = false;
	}
}

?>