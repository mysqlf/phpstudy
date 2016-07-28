/**
 * 
 * @authors Your Name (you@example.org)
 * @date    2015-05-19 16:50:51
 * @version $Id$
 */

define(function (require,exports,module){
	var $=require('../lib/jquery');
  
	function Tab(tabclass,boxclass,callback){
       this.tabclass=$('.'+tabclass);
       this.boxclass=$('.'+boxclass);
       this.callback=callback;
       this._init();
	}

	Tab.prototype={
       _init:function(){
       	var self=this;
          this.tabclass.on('click',function(){
            self.tabclass.removeClass('active_btn');
            $(this).addClass('active_btn');
          	self.boxclass.hide();
          	self.boxclass.eq($(this).index()).show();
            self.callback&&self.callback();
          });
       }
	}
	module.exports= Tab;
});
