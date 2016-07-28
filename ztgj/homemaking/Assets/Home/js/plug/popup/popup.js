define(function(require,exports,module){
  var $ = require('../../lib/jquery-2.1.4.min');

     $.fn.popup = function(options){
         var defaults = {
            position : 'bottom',
            content : '',
            events : 'click',
            className : '',
            opacity : .3,
            zIndex : 1000,
            lockWin : false,
            effectClass:'bottom',
            beforeShow : $.noop,
            afterShow : $.noop,
            beforeHide : $.noop,
            afterHide : $.noop
         };

         var opts = $.extend({},defaults,options);
         var $body = $('body,html');
         var winH = $(window).height();
         var effectClass = 'popup-animate-' + opts.effectClass ;
         var methons = {
            close : function($popup,$mask){
                    var opts = $popup.data('options');
                    var effectClass = 'popup-animate-' + opts.effectClass ;
                    opts.beforeHide();
                    $mask.fadeOut(300);
                    $popup.addClass(effectClass);
                    if(opts.lockWin){
                      methons.unlockWin();
                    }
                    opts.afterHide();
                    
                    },
            show : function($popup,$mask){
                    var opts = $popup.data('options');
                    var effectClass = 'popup-animate-' + opts.effectClass ;
                    opts.beforeShow();
                    $mask.fadeIn(300);
                    $popup.removeClass(effectClass);
                    if(opts.lockWin){
                      methons.lockWin();
                    }
                     opts.afterShow();
                },
            remove : function($popup,$mask){
                    
                    $mask.remove();
                    $popup.remove();
                    $this.data({
                        id : null,
                        ispopup : null
                    })
                    if(opts.lockWin){
                      methons.unlockWin();
                    }
                },
            lockWin : function(){
                $body.css({
                overflow : 'hidden',
                height : winH + 'px'
              });
             } ,
             unlockWin : function(){
                $body.css({
                overflow : 'auto',
                height : 'auto'
              });
             }    
         };
         return this.each(function(){
            var $this = $(this);
            if(typeof options === 'string'){
                     var now = $this.data('popupid') ;
                     var popupId = 'popup'+now;
                     var maskId = 'mask'+now;
                     var $popup = $('#'+popupId);
                     var $mask = $('#'+maskId) ;
                        methons[options]($popup,$mask,$this);
                        return;
                    }

            $this.on(opts.events,function(){
                var isPopup = $this.data('ispopup');
                var now =  isPopup ? $this.data('popupid') : ($.now() + Math.random()*300 + '').replace('.','');
                var popupId = 'popup'+now;
                var maskId = 'mask'+now;
                var $popup = isPopup ? $('#'+popupId) : $('<div id="'+popupId+'" class="fixed popup-animate fixed-'+ opts.position + ' ' + effectClass +' '+ opts.className +' popup"><div class="popup-content">'+ opts.content +'</div></div>');
                var $mask = isPopup ? $('#'+maskId) : $('<div id="'+maskId+'" class="mask"></div>');

                    $popup.data('popup-toggle',$this);
                    $popup.data('options',opts);
                    $this.data('popupid',now);

                     if(!isPopup){

                        opts.beforeShow();
                        $mask.on('click',function(){                         
                             methons.close($popup,$mask);
                        }).css({
                            zIndex: opts.zIndex-1,
                            opacity : opts.opacity
                        }).appendTo('body');

                        $popup.css({
                            zIndex: opts.zIndex,
                        }).appendTo('body');

                     }
                     methons.show($popup,$mask);
                     opts.afterShow();
                    $this.data('ispopup',true); 

                 
            })
         })
     }

})