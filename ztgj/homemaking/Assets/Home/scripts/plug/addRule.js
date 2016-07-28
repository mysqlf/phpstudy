define(function(){

jQuery.validator.addMethod("letter", function(value, element) {   
			var reg = /^[a-zA-Z]+$/;
			return this.optional(element) || (reg.test(value));
		}, "只支持字母格式，如duhaitao");
jQuery.validator.addMethod("letterNum", function(value, element) {   
			var reg = /^[a-zA-Z0-9]+$/;
			return this.optional(element) || (reg.test(value));
		}, "只支持字母、数字格式，如HT0120");	
jQuery.validator.addMethod("letterNum_g", function(value, element) {   
			var reg = /^[A-Za-z0-9_-]+$/;
			return this.optional(element) || (reg.test(value));
		}, "只支持字母、数字格式，如HT0120");
jQuery.validator.addMethod("isZipCode", function(value, element) {   
			var reg = /^[0-9]{6}$/;
			return this.optional(element) || (reg.test(value));
		}, "邮编格式不正确");		
jQuery.validator.addMethod("istelephone", function(value, element) {   
			var reg = /^(13[0-9]|14[5|7]|15[0|1|2|3|5|6|7|8|9]|17[0-9]|18[0|1|2|3|4|5|6|7|8|9])\d{8}$/;//^0?(13[0-9]|15[012356789]|17[678]|18[0-9]|14[57])[0-9]{8}$
			return this.optional(element) || (reg.test(value));
		}, "手机格式不正确");	
jQuery.validator.addMethod("ispositivenum", function(value, element) {   
			var reg = /^\d+$/;
			return this.optional(element) || (reg.test(value));
		}, "只能为正整数");					
jQuery.validator.addMethod("isdate", function(value, element) {   
			var reg = /(((^((1[8-9]\d{2})|([2-9]\d{3}))([-\/\._])(10|12|0?[13578])([-\/\._])(3[01]|[12][0-9]|0?[1-9]))|(^((1[8-9]\d{2})|([2-9]\d{3}))([-\/\._])(11|0?[469])([-\/\._])(30|[12][0-9]|0?[1-9]))|(^((1[8-9]\d{2})|([2-9]\d{3}))([-\/\._])(0?2)([-\/\._])(2[0-8]|1[0-9]|0?[1-9]))|(^([2468][048]00)([-\/\._])(0?2)([-\/\._])(29))|(^([3579][26]00)([-\/\._])(0?2)([-\/\._])(29))|(^([1][89][0][48])([-\/\._])(0?2)([-\/\._])(29))|(^([2-9][0-9][0][48])([-\/\._])(0?2)([-\/\._])(29))|(^([1][89][2468][048])([-\/\._])(0?2)([-\/\._])(29))|(^([2-9][0-9][2468][048])([-\/\._])(0?2)([-\/\._])(29))|(^([1][89][13579][26])([-\/\._])(0?2)([-\/\._])(29))|(^([2-9][0-9][13579][26])([-\/\._])(0?2)([-\/\._])(29)))((\s+(0?[1-9]|1[012])(:[0-5]\d){0,2}(\s[AP]M))?$|(\s+([01]\d|2[0-3])(:[0-5]\d){0,2})?$))/;
			return this.optional(element) || (reg.test(value));
		}, "日期格式不正确");

jQuery.validator.addMethod("noSpace", function(value, element) {   
			var reg = /(^\s+)|(\s+$)/g;
			return this.optional(element) || (reg.test(value));
		}, "不能含有空格");	
jQuery.validator.addMethod("zh_cn", function(value, element) {   
			var reg = /^[\u4e00-\u9fa5]+$/i;
			return this.optional(element) || (reg.test(value));
		}, "只支持汉字");	
jQuery.validator.addMethod("password", function(value, element) {   
			var reg = /^[\w\!\@\#\$\%\^\&\*\(\)\{\}\[\]\:\;\"\'\<\>\,\.\?\/\\\|\-\+\=]+$/i;
			return this.optional(element) || (reg.test(value));
		}, "只支持字母、数字或字符");						
		
	
})