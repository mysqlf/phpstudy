/**
 * 
 * @authors Your Name (you@example.org)
 * @date    2015-05-17 15:53:39
 * @version $Id$
 */
define(function (require,exports,modules){
	var $=require('../lib/jquery');
	require('../plug/pwShow');

  require('../plug/jquery.validate.min');

   if(sessionStorage.account){
        $('input[name="account"]').val(sessionStorage.account);
   }
  

  $('#login_box form input').focus(function(){
      $('p.error').hide();
 
  });
	var formValidate=$("form").validate({
       submitHandler : function(form){
             var val = $(form).find('input[name="account"]').val();
             sessionStorage.account = val;
             
             form.submit();
       },
       success : function(){
        $('p.error').hide();
       },
       rules : {
         password : {
                   required : true,
                    maxlength:20,
                    minlength:6,
                    password : true
                  }
       },
       messages : {
          account : {
            required:'请输入手机号/邮箱/用户名'
          },
          password : {
            required:'请输入密码',
            minlength:'至少输入6位',
            maxlength:'不能超过20位',
            password : '只能输入数字、字母或字符'
          }
       } 
    });
});
