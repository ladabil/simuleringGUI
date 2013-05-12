<?php /* Smarty version Smarty-3.1.13, created on 2013-05-12 22:01:38
         compiled from "C:\Program Files (x86)\Zend\Apache2\htdocs\simuleringGUI\hidden\templates\wizard.tpl.html" */ ?>
<?php /*%%SmartyHeaderCode:3026851865b5e2fc825-78358369%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '0ac43dc2e01cd3f27e2b1166d217f655e9e6a06b' => 
    array (
      0 => 'C:\\Program Files (x86)\\Zend\\Apache2\\htdocs\\simuleringGUI\\hidden\\templates\\wizard.tpl.html',
      1 => 1368381654,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '3026851865b5e2fc825-78358369',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_51865b5e330635_86901250',
  'variables' => 
  array (
    'scriptURL' => 0,
    'i' => 0,
    'inhabitantWorkTypesArr' => 0,
    'enegrySimulator' => 0,
    'function' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_51865b5e330635_86901250')) {function content_51865b5e330635_86901250($_smarty_tpl) {?><?php if (!is_callable('smarty_function_html_options')) include 'C:\\Program Files (x86)\\Zend\\Apache2\\htdocs\\simuleringGUI\\hidden\\Smarty-3.1.13\\plugins\\function.html_options.php';
?><!-- kommentar -->
<SCRIPT type="text/javascript">
 
function changeNumPersons() {
	var numPersons = document.getElementById('numPersons');
	
	alert(numPersons.selectedIndex);
}

// Legg til beboer (inhabitant)
function addInhabitant()
{
	var inhabitantsAgeOpt = document.getElementById('tmpInhabAge');
	var inhabitantsWorkOpt = document.getElementById('tmpInhabWork');

	addInhabitantHelper(inhabitantsAgeOpt.options[inhabitantsAgeOpt.selectedIndex].value
						,inhabitantsWorkOpt.options[inhabitantsWorkOpt.selectedIndex].value
						);
	
	return false;
}

function addInhabitantHelper(ageId, workId)
{
	if ( ageId < 0 )
	{
		alert('Du må velge alder');
		return false;
	}
	
	if ( workId < 0 )
	{
		alert('Du må velge yrke');
		return false;
	}
	
	var inhabitantsAgeOpt = document.getElementById('tmpInhabAge');
	var inhabitantsWorkOpt = document.getElementById('tmpInhabWork');
	var inhabitantsAgeArr = document.getElementsByName('inhabitantsAge[]');
	
	if ( inhabitantsAgeArr == null )
	{
		return false;
	}
	
	var i = inhabitantsAgeArr.length;

	var newDiv = document.createElement("div");
	newDiv.id = 'inhabitant' + i;	
	
	var work = '';
	
	for ( j=0; j<inhabitantsWorkOpt.length; j++ )
	{
		if ( inhabitantsWorkOpt.options[j].value == workId )
		{
			work = inhabitantsWorkOpt.options[j].innerHTML;
		}
	}
	
	newDiv.innerHTML = 'Alder: ' + ageId
						+ '<input type="hidden" name="inhabitantsAge[]"'
						+ ' value="' + ageId + '" />' 
						+ '<input type="hidden" name="inhabitantsWork[]"'
						+ ' value="' + workId + '" />' 
						+ ' Yrke: ' + work
						+ '&nbsp;<input class="javaButton" type="image" src="gfx/16x16_redcross.png" value="fjern" onclick="javascript:removeTag(\'' + newDiv.id + '\');" />'
						;
						
	document.getElementById("myInhabitants").appendChild(newDiv);	
}

// Fjern beboer (inhabitant)
function removeTag(divid)
{
	var removeTag = document.getElementById(divid);
	removeTag.parentNode.removeChild(removeTag);
}

</SCRIPT>
<div>
	<img class="logo" src="gfx/logo.png" />
	
	<!-- Formen -->
	<form method="post" action="<?php echo $_smarty_tpl->tpl_vars['scriptURL']->value;?>
">
		<label>Type byggning</label><br />
		<select name="byggType">
			<option value="enebolig">Enebolig</option>
			<option value="leilighet">to be specified</option>
			<option value="rekkehus">to be specified</option>
		</select><br />
	
		<label>Alder:</label>
		<select id="tmpInhabAge" name="tmpInhabAge">
		<option value="-1">Velg alder</option>
		<?php $_smarty_tpl->tpl_vars['i'] = new Smarty_Variable;$_smarty_tpl->tpl_vars['i']->step = 1;$_smarty_tpl->tpl_vars['i']->total = (int)ceil(($_smarty_tpl->tpl_vars['i']->step > 0 ? 120+1 - (0) : 0-(120)+1)/abs($_smarty_tpl->tpl_vars['i']->step));
if ($_smarty_tpl->tpl_vars['i']->total > 0){
for ($_smarty_tpl->tpl_vars['i']->value = 0, $_smarty_tpl->tpl_vars['i']->iteration = 1;$_smarty_tpl->tpl_vars['i']->iteration <= $_smarty_tpl->tpl_vars['i']->total;$_smarty_tpl->tpl_vars['i']->value += $_smarty_tpl->tpl_vars['i']->step, $_smarty_tpl->tpl_vars['i']->iteration++){
$_smarty_tpl->tpl_vars['i']->first = $_smarty_tpl->tpl_vars['i']->iteration == 1;$_smarty_tpl->tpl_vars['i']->last = $_smarty_tpl->tpl_vars['i']->iteration == $_smarty_tpl->tpl_vars['i']->total;?>
			<option value="<?php echo $_smarty_tpl->tpl_vars['i']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['i']->value;?>
</option>
		<?php }} ?>
		</select>

		<label>Yrke:</label>
		
		<select name="tmpInhabWork" id="tmpInhabWork">
   			<?php echo smarty_function_html_options(array('options'=>$_smarty_tpl->tpl_vars['inhabitantWorkTypesArr']->value),$_smarty_tpl);?>

		</select>
		
		<input type="image" onClick="addInhabitant(); return false;" src="gfx/16x16_addplus.png" class="javaButton" />
		<!-- <input type="image" onClick="return false;" src="gfx/16x16_redcross.png" class="javaButton" /> -->
		
		<br />
		<div id="myInhabitants">
		
		</div>
		
		<!-- Beboere ferdig -->
		<label>Bygge&aring;r</label><br />
	
		<select name="byggaar">
			<option value="for87">F&oslash;r 1987</option>
			<option value="87-97">Mellom 1987 og 1997</option>
			<option value="etter97">Etter 1997</option>
		</select><br />
		
		<!-- Husets størrelse -->
		<label>Boenhet: Brutto Areal</label><br />
		<input type="text" name="houseTotalArea" value="<?php echo $_smarty_tpl->tpl_vars['enegrySimulator']->value->_houseTotalArea;?>
" /><br />
	
		<label>Boenhet: P-Rom</label><br />
		<input type="text" name="housePrimaryArea" value="<?php echo $_smarty_tpl->tpl_vars['enegrySimulator']->value->_housePrimaryArea;?>
" /><br />
		
		<!-- Valg av oppvarming -->
		<label>Prim&aelig;r oppvarming</label><br />
		<select name="priVarme">
			<option value="olv">Oljekjel - vannb&aring;ren varme</option>
			<option value="old">Oljekjel - direkte varme</option>
			<option value="gas">Gasspeis</option>
			<option value="luv">Varmepumpe luft til vann</option>
			<option value="lul">Varmepumpe luft til luft</option>
			<option value="elv">Elektrokjel - vannb&aring; varme</option>
			<option value="el">Helelektrisk</option>
			<option value="fjv">Fjernvarme - vannb&aring;ren varme</option>
			<option value="ved">Vedovn</option>
		</select><br />
	
	    <label>Klima</label><br />
		<select name="klima">
			<option value="5,37">S&oslash;r-Norge, kyst</option>
			<option value="7,08">S&oslash;r-Norge, innland</option>
			<option value="8,79">S&oslash;r-Norge, h&oslash;yfjell</option>
			<option value="6,05">Midt-Norge, kyst</option>
			<option value="9,02">Midt-Norge, innland</option>
			<option value="8,11">Nord-Norge, kyst</option>
			<option value="10,82">Finnmark og innland Troms</option>
		</select><br />
		
		<!--  Spesifisering av lyskilder -->
		<label>Belysningstype</label><br />
	
		<select name="belysningstype">
			<option value="60">Gl&oslash;de p&aelig;rer</option>
			<option value="14">Sparep&aelig;rer</option>
			<option value="10">LED p&aelig;rer</option>
			<option value="30">Lysr&oslash;r</option>
		</select><br />
		
		<label>Antall lyskilder</label><br />
		<input type="text" name="antall_lyskilder" value="<?php echo $_smarty_tpl->tpl_vars['enegrySimulator']->value->_numLys;?>
" /><br />
		
		<!--  Hvitevarer / Brunevarer -->
		<label>Antall hvitevarer</label><br />
		<input type="text" name="antall_hvitevarer" value="<?php echo $_smarty_tpl->tpl_vars['enegrySimulator']->value->_numHvit;?>
" /><br />
		
		<label>Antall brunevarer</label><br />
		<input type="text" name="antall_brunevarer" value="<?php echo $_smarty_tpl->tpl_vars['enegrySimulator']->value->_numBrun;?>
" /><br /><br />
	
		<input type="hidden" name="function" value="<?php echo $_smarty_tpl->tpl_vars['function']->value;?>
" />
		<input type='submit' class="button" value='Beregn' />
	</form>
<?php if (isset($_smarty_tpl->tpl_vars['enegrySimulator']->value)&&intval($_smarty_tpl->tpl_vars['enegrySimulator']->value->getEnergyUsage())>0){?>
	<h3>Resultat</h3><?php echo $_smarty_tpl->tpl_vars['enegrySimulator']->value->getEnergyUsage();?>
 kWh i &aring;ret.	
<?php }?>
</div>

<SCRIPT type="text/javascript">

<?php if (count($_smarty_tpl->tpl_vars['enegrySimulator']->value->_inhabitantsWork)>0){?>
	<?php $_smarty_tpl->tpl_vars['i'] = new Smarty_Variable;$_smarty_tpl->tpl_vars['i']->step = 1;$_smarty_tpl->tpl_vars['i']->total = (int)ceil(($_smarty_tpl->tpl_vars['i']->step > 0 ? (count($_smarty_tpl->tpl_vars['enegrySimulator']->value->_inhabitantsWork)-1)+1 - (0) : 0-((count($_smarty_tpl->tpl_vars['enegrySimulator']->value->_inhabitantsWork)-1))+1)/abs($_smarty_tpl->tpl_vars['i']->step));
if ($_smarty_tpl->tpl_vars['i']->total > 0){
for ($_smarty_tpl->tpl_vars['i']->value = 0, $_smarty_tpl->tpl_vars['i']->iteration = 1;$_smarty_tpl->tpl_vars['i']->iteration <= $_smarty_tpl->tpl_vars['i']->total;$_smarty_tpl->tpl_vars['i']->value += $_smarty_tpl->tpl_vars['i']->step, $_smarty_tpl->tpl_vars['i']->iteration++){
$_smarty_tpl->tpl_vars['i']->first = $_smarty_tpl->tpl_vars['i']->iteration == 1;$_smarty_tpl->tpl_vars['i']->last = $_smarty_tpl->tpl_vars['i']->iteration == $_smarty_tpl->tpl_vars['i']->total;?>
		addInhabitantHelper(<?php echo $_smarty_tpl->tpl_vars['enegrySimulator']->value->_inhabitantsAge[$_smarty_tpl->tpl_vars['i']->value];?>
, <?php echo $_smarty_tpl->tpl_vars['enegrySimulator']->value->_inhabitantsWork[$_smarty_tpl->tpl_vars['i']->value];?>
);
	<?php }} ?>
<?php }?>

</SCRIPT>
<?php }} ?>