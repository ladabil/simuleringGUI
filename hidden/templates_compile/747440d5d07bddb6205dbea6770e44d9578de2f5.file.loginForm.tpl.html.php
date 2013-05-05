<?php /* Smarty version Smarty-3.1.13, created on 2013-05-05 15:15:04
         compiled from "C:\Program Files (x86)\Zend\Apache2\htdocs\simuleringGUI\hidden\templates\loginForm.tpl.html" */ ?>
<?php /*%%SmartyHeaderCode:1043151865b585b3072-27908592%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '747440d5d07bddb6205dbea6770e44d9578de2f5' => 
    array (
      0 => 'C:\\Program Files (x86)\\Zend\\Apache2\\htdocs\\simuleringGUI\\hidden\\templates\\loginForm.tpl.html',
      1 => 1367759492,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1043151865b585b3072-27908592',
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
  'unifunc' => 'content_51865b586e18e5_15920069',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_51865b586e18e5_15920069')) {function content_51865b586e18e5_15920069($_smarty_tpl) {?><form method="POST" action="<?php echo $_smarty_tpl->tpl_vars['scriptURL']->value;?>
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