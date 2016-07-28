/**
 * 
 * @authors Your Name (you@example.org)
 * @date    2015-05-26 10:40:44
 * @version $Id$
 */
define(function (require,exports,module){
	var $=require('../lib/jquery');
  var reajax=require('../plug/ajax');

function getCode(){
	this.tel=$('#tel').length==0 ? $('#telphone') : $('#tel');
	this.time=parseInt($('#time').val());
	this.get_verify=$('.get_verify');
	this.count=null;
  this.timer= this.time||60;
  this._init();
}

 getCode.prototype={
 	_init:function(){

    var self=this;
 		self.get_verify.on('tap',function(){
      self.getcode();
    });
 		if (self.timer!=60)
	    {
	      self.get_verify.css('background-color','#ccc').off('tap');
	      self.count=setInterval(function(){self.countSecond(self.timer)},1000);
	    }
 	},
 	getcode:function(){
    var self=this;
    if(self.tel.attr('id')==='telphone'){
          var sendDate={
                  phoneVal:self.tel.val()||self.tel.text()
            }
          new reajax('Account-getcode',"post",sendDate,'json',function(data){
            if(data===1){
              self.get_verify.css('background-color','#ccc').off('tap');
              alert('您已经超过5次请求获取验证，请24小时候再尝试请求');
            }
            else{
            self.get_verify.css('background-color','#ccc').off('tap');
            self.count=setInterval(function(){self.countSecond(self.timer)},1000);
            }
        });
      }
      else{
  			var sendDate={
  				          phoneVal:self.tel.val()||self.tel.text()
  				    }
        		new reajax('Account-getcode',"post",sendDate,'json',function(data){
            if(data===1){
              self.get_verify.css('background-color','#ccc').off('tap');
              alert('您已经超过5次请求获取验证，请24小时候再尝试请求');
            }
            else{
            self.get_verify.css('background-color','#ccc').off('tap');
            self.count=setInterval(function(){self.countSecond(self.timer)},1000);
            }
           });
      	 }
      }
 	},
 	countSecond:function(timer){
    var self=this;
    	if( timer!=0){
        self.get_verify.text(timer+'s重新获取');
         self.timer=timer-1;
       }
       else{
       	clearInterval(self.count);
         self.get_verify.css('background-color','#ff9600');
         self.get_verify.text('重新获取');
         self.timer=60;
         self.get_verify.on('tap',function(){
          self.getcode();
        });
       }
    }

   
 }

 module.exports=new getCode();

});



