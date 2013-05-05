<?php /* Smarty version Smarty-3.1.13, created on 2013-05-05 14:07:26
         compiled from "C:\Program Files (x86)\Zend\Apache2\htdocs\simuleringGUI\simuleringsGUI\hidden\templates\wizard.tpl.html" */ ?>
<?php /*%%SmartyHeaderCode:197805186440f0173b6-29430266%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '8f776090c059257acbe8e4eeaf10e5aa4f8875c0' => 
    array (
      0 => 'C:\\Program Files (x86)\\Zend\\Apache2\\htdocs\\simuleringGUI\\simuleringsGUI\\hidden\\templates\\wizard.tpl.html',
      1 => 1367755642,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '197805186440f0173b6-29430266',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_5186440f028dd2_09983715',
  'variables' => 
  array (
    'scriptURL' => 0,
    'function' => 0,
    'result' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5186440f028dd2_09983715')) {function content_5186440f028dd2_09983715($_smarty_tpl) {?><html>
	<head>
		<link href="style.css" rel="stylesheet" type="text/css" media="screen" />
		<title>Sim</title>
		
	</head>
	<body>
		<div class="sim">
			<img class="logo" src="gfx/logo.png" />
			
			<!-- Formen -->
			<form method="post" action="<?php echo $_smarty_tpl->tpl_vars['scriptURL']->value;?>
">
				<label>Antall Personer i Husstand</label><br />
				<input type="text" name="antall_i_hus"/><br />
			
				<label>Gjennomsnittsalder</label><br />
				<input type="text" name="gjen_alder"/><br />
			
				<label>Bygge&aring;r</label><br />
			
				<select name="byggaar">
					<option value="for87">F&oslash;r 1987</option>
					<option value="87-97">Mellom 1987 og 1997</option>
					<option value="etter97">Etter 1997</option>
				</select><br />
			
			    <label>Klima</label><br />
				<select name="klima">
					<option value="sn-kyst">S&oslash;r-Norge, kyst</option>
					<option value="sn-innland">S&oslash;r-Norge, innland</option>
					<option value="sn-hf">S&oslash;r-Norge, h&oslash;yfjell</option>
					<option value="mn-kyst">Midt-Norge, kyst</option>
					<option value="mn-innland">Midt-Norge, innland</option>
					<option value="nn-kyst">Nord-Norge, kyst</option>
					<option value="ft">Finnmark og innland Troms</option>
				</select><br /><br />
			
				<input type="hidden" name="function" value="<?php echo $_smarty_tpl->tpl_vars['function']->value;?>
" />
				<input type='submit' class="button" value='Beregn' />
			</form>
<?php if (isset($_smarty_tpl->tpl_vars['result']->value)&&intval($_smarty_tpl->tpl_vars['result']->value)>0){?>
		<h3>Resultat</h3><?php echo $_smarty_tpl->tpl_vars['result']->value;?>
 watt i &aring;ret.		
<?php }?>
		</div>
	<body>
</html>
<?php }} ?>