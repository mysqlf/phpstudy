
define(function(require,exports,module){
   var $ = require('../../lib/jquery-2.1.4.min');
   
   $.fn.scrollListener = function(options){

   	var defaults = {
   		scrollTop : $.noop,//滚动到头部时候
   		scroll : $.noop,//滚动中头部时候
   		scrollEnd : $.noop,//滚动到底部时候
      threshold : 0// 提前n像素触发 scrollEnd
   	}
   	var opts = $.extend({},defaults,options);

   	return this.each(function(e){

       var isWin = this === window,
           $this = $(this),
           maxH = isWin ?  $(document).height() : $this.children(0).height(),
           boxH = $this.height();

           _scrollFn = function(e){
            
             var top = $this.scrollTop();  
             e.stopPropagation();
             maxH = isWin ?  $(document).height() : $this.children(0).height()
             if(maxH <= top + boxH - opts.threshold){
                  opts.scrollEnd(top,maxH,$this);
               
             }
             else if(top === 0){
                   opts.scrollTop(top,maxH,$this);
             }
             else{
                   opts.scroll(top,maxH,$this);
             }
             }

           //防止底部刷新问题
           setTimeout(function(){
            if($this.scrollTop() + boxH >= maxH){
               $this.scrollTop($this.scrollTop()-1);
             }
           },0)
           
           setTimeout(function(){

           //防止多次绑定事件
           $this.off('scroll.scrollListener');
           //为scroll事件添加命名空间
           $this.on('scroll.scrollListener',_scrollFn);

         },1)
      	 })
             
   }

})