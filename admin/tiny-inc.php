<!-- TinyMCE -->
<?php 
$tinyLang = ($_SESSION['lang']==1)? "en" : "en";
if(isset($site_config) AND $site_config==1) {
	$tinyMode = '
	mode : "exact", 
	elements : "storeClosedMessage1,textList,textList_2,textList_3,textCategories,textCategories_2,textCategories_3,textHome,textHome_2,textHome_3",
	';
}
else {
		$tinyMode = 'mode : "textareas",';
}
?>
<script type="text/javascript" src="../js/tinymce/jscripts/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript">
	tinyMCE.init({

		<?php print $tinyMode;?>
		
		theme : "advanced",
		plugins : "pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,inlinepopups,autosave",
		relative_urls : true,
		language : "<?php print $tinyLang;?>",
		document_base_url : "<?php print 'http://'.$www2.$domaineFull.'/';?>",
		
 
		theme_advanced_buttons1 : "preview,code,fullscreen,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,fontselect,fontsizeselect",
		theme_advanced_buttons2 : "undo,redo,|,search,replace,|,bullist,numlist,|,outdent,indent,|,link,unlink,anchor,image,cleanup,|,forecolor,backcolor,|,attribs",
		theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,|,charmap,iespell,media,advhr",
		theme_advanced_buttons4 : "",
		theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "left",
		theme_advanced_statusbar_location : "bottom",
		theme_advanced_resizing : true,
		
 
template_replace_values : {
username : "Some User",
staffid : "991234"
}
	});
</script>
<!-- /TinyMCE -->