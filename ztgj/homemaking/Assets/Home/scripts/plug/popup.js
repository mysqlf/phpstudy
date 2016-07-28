/**
 * 
 * @authors Your Name (you@example.org)
 * @date    2015-05-13 09:40:10
 * @version $Id$
 */
define(function (require, exports, module){
	var $=require('../lib/jquery');

	function PopUp (Tclass,closeArr,showcallback,hidecallback) {
		this.mask=$('#mask');
		this.Tclass=$("."+Tclass);
		this.hideBox=$('#hidden_box');
		this.closeArr=closeArr;
		this._init();
		this.showcallback=showcallback;
		this.hidecallback=hidecallback;
	}
	
     PopUp.prototype={
     	_init:function(){
     		var self=this;
     		this.show();

     		$(this.closeArr).each(function(index,item){
                 $('body').on('click',item,function(){
                 	self.hide();
                 });
     		}); 
	         
           },
         show:function(){
          var self=this;
          this.Tclass.on('click',function(){
          	self.mask.show();
          	self.hideBox.show();
          	self.showcallback&&self.showcallback();
          });
         },
         hide:function(){
            this.mask.hide();
            this.hideBox.hide();
            this.hidecallback&&this.hidecallback(); 
         }
     	}
module.exports=PopUp; 
   	
});


