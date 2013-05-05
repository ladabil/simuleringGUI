<?php /* Smarty version Smarty-3.1.13, created on 2013-05-05 14:39:47
         compiled from "C:\Program Files (x86)\Zend\Apache2\htdocs\simuleringGUI\simuleringsGUI\hidden\templates\main.tpl.html" */ ?>
<?php /*%%SmartyHeaderCode:24868518641cba46fe0-93161965%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '6c6efa9d01701b389997e001bd05711ed129307f' => 
    array (
      0 => 'C:\\Program Files (x86)\\Zend\\Apache2\\htdocs\\simuleringGUI\\simuleringsGUI\\hidden\\templates\\main.tpl.html',
      1 => 1367757581,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '24868518641cba46fe0-93161965',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_518641cbaa5f65_54526021',
  'variables' => 
  array (
    'title' => 0,
    'fullname' => 0,
    'username' => 0,
    'accesslevel' => 0,
    'infoMessage' => 0,
    'logMessages' => 0,
    'content' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_518641cbaa5f65_54526021')) {function content_518641cbaa5f65_54526021($_smarty_tpl) {?><!DOCTYPE HTML>

<HTML>
<HEAD>
<META http-equiv="Content-type" content="text/html;charset=UTF-8">

<link href="css/style.css" rel="stylesheet" type="text/css" media="screen" />
<title><?php echo $_smarty_tpl->tpl_vars['title']->value;?>
</title>
<SCRIPT type="text/javascript">
 
function ConfirmChoice(question) {
	 answer = confirm(question)
	 if (answer !=0)
	  	return true
	 else
	  	return false
}
</SCRIPT>


</HEAD>
<BODY>

<div class="sim">
	<img class="logo" src="gfx/logo.png" />
	<div id="header" class="header">SimulatorGUI</div>
	<div id="loginInfo">Innlogget som: <?php echo $_smarty_tpl->tpl_vars['fullname']->value;?>
 (<?php echo $_smarty_tpl->tpl_vars['username']->value;?>
) - Tilgangsnivå: <?php echo $_smarty_tpl->tpl_vars['accesslevel']->value;?>
</div>
</div>

<?php if (isset($_smarty_tpl->tpl_vars['infoMessage']->value)&&strlen($_smarty_tpl->tpl_vars['infoMessage']->value)>0){?>
<div id="infoMessage" class="infomsg"><?php echo $_smarty_tpl->tpl_vars['infoMessage']->value;?>
</div>
<?php }?>

<?php if (isset($_smarty_tpl->tpl_vars['logMessages']->value)&&strlen($_smarty_tpl->tpl_vars['logMessages']->value)>0){?>
<div id="logMessages" class="infomsg"><?php echo $_smarty_tpl->tpl_vars['logMessages']->value;?>
</div>
<?php }?>

<div id="content" class="sim"><?php echo $_smarty_tpl->tpl_vars['content']->value;?>
</div>

<div id="footer" class="sim">Copyright &copy; Gruppe2IT - 2013</div>

</BODY>
</HTML>
<?php }} ?>