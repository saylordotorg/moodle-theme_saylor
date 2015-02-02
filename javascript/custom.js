jQuery(document).ready(function($) {

var $pArr = $('.coursebox.panel2');
	var pArrLen = $pArr.length;
	var pPerDiv = 2;
	for (var i = 0;i < pArrLen;i+=pPerDiv){
		$pArr.filter(':eq('+i+'),:lt('+(i+pPerDiv)+'):gt('+i+')').wrapAll('<div class="row-fluid clearfix" />');
}

//$('#block-region-side-pre .block').eq(0).addClass('firstblock');






});