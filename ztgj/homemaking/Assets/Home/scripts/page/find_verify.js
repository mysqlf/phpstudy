/**
 * 
 * @authors Your Name (you@example.org)
 * @date    2015-05-26 09:15:40
 * @version $Id$
 */
define(function (require,exports,module){
  var $=require('../lib/jquery');
  require('../plug/jquery.validate.min')
  var reajax=require('../plug/ajax');
  var tel=$('#tel');
  var timer=$('#time').val()||60;
  var get_verify=$('.get_verify');
  var verify=$('.verify_text');
  var count=null;


var formValidate=$("form").validate({
       rules : {
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
                         return $("#tel").attr('data-tel');
                      },
                      'codeVal':function(){
                         return $(".verify_text").val();
                      }
                    }
               }
          }

       },
       messages : {
          verifytext:{
            required:'请输入验证码',
            number:'请输入数字',
            maxlength:'请输入6位数字',
            remote:'验证码不正确'
          }
       } 
    });
  get_verify.on('click',function(){  
           getcode();
   });

   if (timer!=60)
      {
        get_verify.css('background-color','#ccc').off('click');
        count=setInterval(function(){countSecond(timer)},1000);
      }


   function getcode(){
    var sendDate={
         phoneVal:$("#tel").attr('data-tel')
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


