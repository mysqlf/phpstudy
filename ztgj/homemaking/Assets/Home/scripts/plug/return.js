/**
 * 
 * @authors Your Name (you@example.org)
 * @date    2015-06-01 16:39:20
 * @version $Id$
 */
define(function (require,exports,module){
var $=require('../lib/jquery');
	  //后退功能
  $('.icon-return').on('click',function(){
        window.history.back(-1);
  });
});
