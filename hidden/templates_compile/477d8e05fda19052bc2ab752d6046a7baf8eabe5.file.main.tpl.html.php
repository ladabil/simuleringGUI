<?php /* Smarty version Smarty-3.1.13, created on 2013-05-05 15:15:04
         compiled from "C:\Program Files (x86)\Zend\Apache2\htdocs\simuleringGUI\hidden\templates\main.tpl.html" */ ?>
<?php /*%%SmartyHeaderCode:226251865b58717921-67351433%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '477d8e05fda19052bc2ab752d6046a7baf8eabe5' => 
    array (
      0 => 'C:\\Program Files (x86)\\Zend\\Apache2\\htdocs\\simuleringGUI\\hidden\\templates\\main.tpl.html',
      1 => 1367759491,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '226251865b58717921-67351433',
  'function' => 
  array (
  ),
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
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_51865b5876c5e1_55200324',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_51865b5876c5e1_55200324')) {function content_51865b5876c5e1_55200324($_smarty_tpl) {?><!DOCTYPE HTML>

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