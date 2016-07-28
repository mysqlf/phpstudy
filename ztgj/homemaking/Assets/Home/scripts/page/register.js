/**
 * 
 * @authors Your Name (you@example.org)
 * @date    2015-05-17 15:57:53
 * @version $Id$
 */
define(function (require,exports,module){
	var $=require('../lib/jquery');
	require('../plug/pwShow');

  require('../plug/jquery.validate.min');
	var reajax=require('../plug/ajax');
  var timer=$('#time').val()||60;
  var get_verify=$('.get_verify');
  var tel=$('#tel'); 
  var verify=$('.verify_text');
  var count=null;

    var formValidate=$("form").validate({
       rules:{
         phoneVal:{
               required:true,
               istelephone:true,
               number:true,
               remote:{
                    url:'Account-telphone',
                    type:'post',
                    dataType:'json',
                    data:{
                      'phoneVal':function(){
                         return $("#tel").val();
                      }
                    }
               }
          },
         password : {
                required : true,
                maxlength : 20,
                minlength : 6,
                password : true

          },
          verifytext:{
               required:true,
               number:true,
               maxlength : 6,
               remote:{
                    url:'Account-code',
                    type:'post',
                    dataType:'json',
                    data:{
                      'phoneVal':function(){
                         return $("#tel").val();
                      },
                      'codeVal':function(){
                         return $(".verify_text").val();
                      }
                    }
               }
          }

       },
       messages:{
          phoneVal:{
            required:'请输入手机号',
            istelephone:'手机格式不正确',
            number:'手机格式不正确',
            remote:'手机号已被注册'
          },
          password:{
            required:'请输入密码',
            minlength:'至少输入六位',
            maxlength:'不能超过20位',
            password : '只能输入数字、字母或字符'
          },
          verifytext:{
            required:'请输入验证码',
            number:'请输入数字',
            maxlength:'请输入6位数字',
            remote:'验证码不正确'
          }
       } 
    });

   get_verify.on('click',function(){
        if (formValidate.element('#tel')){
           getcode();
        }
        else{
         formValidate.showErrors({
              phone  :'手机号不正确'
            })
        }
   });

   if (timer!=60)
      {
        get_verify.css('background-color','#ccc').off('click');
        count=setInterval(function(){countSecond(timer)},1000);
      }


   function getcode(){
    var sendDate={
         phoneVal:$('#tel').val()
    }
    new reajax('Account-getcode',"post",sendDate,'json',function(data){
            if(data===1){
              get_verify.css('background-color','#ccc').off('click');
              alert('您已经超过5次请求获取验证，请24小时候再尝试请求');
            }
            else{
            get_verify.css('background-color','#ccc').off('click');
            count=setInterval(function(){countSecond(timer)},1000);
            }
           }); 
  }

  function countSecond(timers){
      if( timers!=0){
        timer=timers-1;
        get_verify.text(timer+'s重新获取');
        get_verify.off('click',getcode);        
       }
       else{
        clearInterval(count);
         get_verify.css('background-color','#ff9600');
         get_verify.text('重新获取');
         timer=60;
         get_verify.on('click',function(){
          getcode();
        });
       }
    }
   

});		