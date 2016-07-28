/**
 * 
 * @authors Your Name (you@example.org)
 * @date    2015-05-26 14:05:40
 * @version $Id$
 */
 define(function (require,exports,module){
  var reajax=require('../plug/ajax');
  var $=require('../lib/jquery');

function scrollRefresh(ret,pageNum,url,sendDate,readData,callbakenoMore){
  this.ret=ret;
  this.pageNum=pageNum||0;
  this.readData=readData;
  this.sendDate=sendDate;
  this.url=url;
  this.$body=$('body');
  this._init();
  this.callbakenoMore=callbakenoMore;
}

scrollRefresh.prototype={
   _init:function(){
    var self=this;
    $(document).on('scroll',function(){
      self.scrollData();
    });     
   },
   scrollData:function(){
     var self=this;
        if(this.ret==0){
                      //显示没有更多了
                      $(document).off('scroll');
                    return false;
              }
                else if(self.$body.attr('data-lock')==0){
                    dHeight=$(document).height();
                    wHeight=$(window).height();
                    sHeight=self.$body.scrollTop();
                    if(dHeight-20<wHeight+sHeight)
                    {
                        
                        this.pageNum++;
                        this.sendDate.ajax='ajax';
                        this.sendDate.pageNum=this.pageNum;
                        self.$body.attr('data-lock',1);
                        new reajax(self.url,"post",this.sendDate,'json',function(data){
                           if(data.ret){
                            self.readData(data);
                            self.$body.attr('data-lock',0);
                            self.ret=data.ret;
                          }
                          else{
                            self.$body.attr('data-lock',0);
                            self.ret=data.ret;
                            self.callbakenoMore();
                          }
                        });
                       
                    }
                }
   }
}
module.exports=scrollRefresh;


 });
