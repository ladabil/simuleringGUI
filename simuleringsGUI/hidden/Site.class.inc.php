<?php


class Site 
{
	function showMainFrame($content, $title="untitled")
	{
		require_once(dirname(__FILE__) . "/MyBlogInnlegg.class.inc.php");
	
		$tpl = new MySmarty();
	
		$tpl->assign('content', $content);
		$tpl->assign('title', $title);
	
		$tpl->assign('showLoginInfo', TRUE);
		$tpl->assign('fullname', $GLOBALS["authlib"]->getFullname());
		$tpl->assign('username', $GLOBALS["authlib"]->getUsername());
		$tpl->assign('accesslevel', $GLOBALS["authlib"]->getAccessLevelAsStr());
	
		$tpl->assign('infoMessage', $this->getInfoMessage());
		$tpl->assign('logMessages', ob_get_clean());
	
		return $tpl->fetch("main.tpl.html");
	}
}