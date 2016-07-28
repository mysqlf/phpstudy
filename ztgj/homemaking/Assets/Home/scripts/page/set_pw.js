/**
 * 
 * @authors Your Name (you@example.org)
 * @date    2015-05-22 17:45:41
 * @version $Id$
 */
define(function (require,exports,modulde){
	var $=require('../lib/jquery');
    require('../plug/jquery.validate.min');
	var strongRegex =/^(?=.{6,})(((?=.*[A-Z])(?=.*[a-z])(?=.*[0-9]))|((?=.*[A-Z])(?=.*[a-z])(?=.*\W))|((?=.*[A-Z])(?=.*[0-9])(?=.*\W))|((?=.*[a-z])(?=.*[0-9])(?=.*\W))).*$/; //强
    var mediumRegex =/^(?=.{6,})(((?=.*[A-Z])(?=.*[a-z]))|((?=.*[A-Z])(?=.*[0-9]))|((?=.*[a-z])(?=.*[0-9]))|((?=.*[a-z])(?=.*\W))|((?=.*[A-Z])(?=.*\W))|((?=.*[0-9])(?=.*\W))).*$/; //中
    var enoughRegex =/^(?=.{6,}).*/;  //弱
    var twig=$('.twig');

    $('#password').keyup(function(){
    	var pw=$(this).val();
      init();
    	if(strongRegex.test(pw)){
           $('.high .twig').addClass('level_active');
    	}
    	else if(mediumRegex.test(pw)){
           $('.middle .twig').addClass('level_active');
    	}
    	else if(enoughRegex.test(pw)){
           $('.low .twig').addClass('level_active');
    	}
    });


	  function init(){
	  twig.removeClass('level_active');
	  }
    var formValidate=$("form").validate({
       rules : {
         password : {
                    required:true,
                    maxlength:20,
                    minlength:6,
                    password:true
                    
                  },
          repassword : {
            equalTo:'#password'
          }

       },
       messages : {
          password : {
            required:'请输入密码',
            minlength:'至少输入6位',
            maxlength:'不能超过20位',
            password : '只能输入数字、字母或字符'
          },
          repassword :{
            required:'请再次输入密码',
            equalTo:'两次密码不一致'
          }

       },
      submitHandler:function(form){
         form.submit();
      }
    });

   
});
