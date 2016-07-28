/**
 * 
 * @authors Your Name (you@example.org)
 * @date    2015-06-12 14:58:22
 * @version $Id$
 */
define(function (require,exports,module){
var $=require('../lib/jquery');
  require('../plug/jquery.validate.min');
	  //后退功能
  $('.icon-return').on('click',function(){
        window.history.back(-1);
  });
  var formValidate=$("form").validate({
       rules : {
         signature : {
                   required : true,
                    maxlength:50
                  }
       },
       messages : {
          signature : {
                   required : '请填写个性签名',
                   maxlength: '不能超过50个字哦'
                  }
       } 
    });
});
