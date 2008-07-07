<?
require_once("./../class/Autoload.class.php");
Autoload::add("../class");
Autoload::add("../class/html");
?>

function checkForm(form) {
	var impColumns = getImportantFormColumns(form.name);
	for (var i in impColumns) {
		if (form.elements.namedItem(impColumns[i]).value == '') {	
			var columnLabel = document.getElementById(<? echo "'".Form::LABEL_ID_PREFIX."'"; ?> + form.elements.namedItem(impColumns[i]).name).innerHTML;
			alert(<? echo "'".Language::WITHOUT_IMPORTANT_FORM_COLUMN."'"; ?> + "'"+columnLabel+"'");
			return false;
		}
	}
	return true;
	
}
