<?php /* Smarty version Smarty-3.1.13, created on 2013-05-05 13:21:51
         compiled from "C:\Program Files (x86)\Zend\Apache2\htdocs\simuleringGUI\simuleringsGUI\hidden\templates\loginForm.tpl.html" */ ?>
<?php /*%%SmartyHeaderCode:4665518640cf970622-62389498%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '6aa3c12adff94b0bc85113e6e508fb4a80a21dc6' => 
    array (
      0 => 'C:\\Program Files (x86)\\Zend\\Apache2\\htdocs\\simuleringGUI\\simuleringsGUI\\hidden\\templates\\loginForm.tpl.html',
      1 => 1367751575,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '4665518640cf970622-62389498',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'scriptURL' => 0,
    'function' => 0,
    'alUsername' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_518640cfb9ce73_25115305',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_518640cfb9ce73_25115305')) {function content_518640cfb9ce73_25115305($_smarty_tpl) {?><form method="POST" action="<?php echo $_smarty_tpl->tpl_vars['scriptURL']->value;?>
">
<input type="hidden" name="function" value="<?php echo $_smarty_tpl->tpl_vars['function']->value;?>
" />
<div id="loginForm">
	<div>Brukernavn</div>
	<div><input type="text" name="alUsername" value="<?php echo $_smarty_tpl->tpl_vars['alUsername']->value;?>
"></div>
	<div>Passord</div>
	<div><input type="password" name="alPassword" value=""></div>
	<div><input type="submit" value="Logg inn"></div>
</div>
</form><?php }} ?>