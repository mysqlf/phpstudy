/**
 * 
 * @authors Your Name (you@example.org)
 * @date    2015-06-11 20:06:55
 * @version $Id$
 */
define(function (require,exports,modules){
	var $=require('../lib/jquery');

  require('../plug/jquery.validate.min');

   if(sessionStorage.account){
        $('input[name="account"]').val(sessionStorage.account);
   }
  
    $('i.error')&&$('#tel_email').focus(function(){$('i.error').hide()});
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
          account:{
            required:true
          }
       },
       messages : {
          account : {
            required:'请输入账号'
          }
       } 
    });
});

