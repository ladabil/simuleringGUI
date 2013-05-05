<?php /* Smarty version Smarty-3.1.13, created on 2013-05-05 15:15:10
         compiled from "C:\Program Files (x86)\Zend\Apache2\htdocs\simuleringGUI\hidden\templates\wizard.tpl.html" */ ?>
<?php /*%%SmartyHeaderCode:3026851865b5e2fc825-78358369%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '0ac43dc2e01cd3f27e2b1166d217f655e9e6a06b' => 
    array (
      0 => 'C:\\Program Files (x86)\\Zend\\Apache2\\htdocs\\simuleringGUI\\hidden\\templates\\wizard.tpl.html',
      1 => 1367759493,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '3026851865b5e2fc825-78358369',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'scriptURL' => 0,
    'function' => 0,
    'result' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_51865b5e330635_86901250',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_51865b5e330635_86901250')) {function content_51865b5e330635_86901250($_smarty_tpl) {?><div>
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
		
		<!-- Husets størrelse -->
		<label>Boenhet: Brutto Areal</label><br />
		<input type="text" name="houseTotalArea"/><br />
	
		<label>Boenhet: P-Rom</label><br />
		<input type="text" name="housePrimaryArea"/><br />
	
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
</div><?php }} ?>