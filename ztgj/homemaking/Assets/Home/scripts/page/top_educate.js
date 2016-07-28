/**
 * 
 * @authors Your Name (you@example.org)
 * @date    2015-05-14 10:05:19
 * @version $Id$
 */

define(function (require, exports, module){
	var $=require('../lib/jquery');
	$('#start_time').change(function(){
		$('.start_text').val($(this).val());
	});
	$('#end_time').change(function(){
		$('.end_text').val($(this).val());
	});
});