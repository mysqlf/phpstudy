/**
 * 
 * @authors Your Name (you@example.org)
 * @date    2015-05-19 18:02:06
 * @version $Id$
 */
define(function (require,exports,moudel){
	var $=require('../lib/jquery');
	var popup=require("../plug/popup");
	var reajax=require('../plug/ajax');
  var closeArr=['#mask','.cancel_btn','.confirm_btn'];
  var $popupbox=$('.popup_box');
  var Swiper=require('../plug/swiper.min');
	var senddata={
		id:$('.companyname').attr('data-id')
	}

	 new popup('sign',closeArr,showcallback,hidecallback);

		
	function showcallback(){
    	
		new reajax('Fast-sign','post',senddata,'json',function(data){
		    if(data.type==1||data.type==2){
                $popupbox.html('<div class="tips_box"><p class="tips_status"> <i class="icon-'+data.status+'"></i>'+data.msg+'</p></div><div class="tac"><input type="button" class="confirm_btn" value="确认"></div>');
            }
             else{
	         $popupbox.html('<h2>'+data.title+'</h2><div class="tips_box">'+data.msg+'</div><input type="button"  class="cancel_btn" value="取消"><a href="'+data.data.url+'" class="confirm_btn_a">'+data.btn+'</a>');
                
	        }
            $popupbox.show();
		});
	};
	function hidecallback(){
    	$popupbox.hide().empty();
    }
    	  //后退功能
  $('.icon-return_speed').on('click',function(){
        window.history.back(-1);
  });


   //初始化
  if($('#speed_detail').attr('data-status')==1){
        $('#mask').show();
        $('#hidden_box').show();
        $popupbox.show().html('<div class="tips_box"><p class="tips_status"> <i class="icon-success"></i>保存信息成功，请申请职位</p></div><div class="tac"><input type="button" class="confirm_btn affirm_btn" value="确认"></div>');
        $('#mask').on('click',cleanCurrent);
        $popupbox.on('click','affirm_btn',cleanCurrent);
   }
    
 function cleanCurrent(){
          $('#mask').hide();
          $popupbox.empty();
        }

 $(function(){
       var Height=$('.limit-h3').height();
       var maxHeight=parseInt($('.limit-h3').css('line-height'))*3;
       var readHtml='<div class="read_more">阅读详情<i class="icon-unfold" style="transform: rotate(0deg);"></i></div>';
       var unfold_box=$('.unfold_box');

       if(Height>maxHeight){
         unfold_box.append(readHtml);
         $('.limit-h3').css('max-height','4.5em');
         unfold_box.on('click','.read_more',function(){
         var icon=$(this).children(".icon-unfold");    
        var objtarget=$(this).closest('section').find('.limit-h3');
            if(objtarget.hasClass('active')){
               objtarget.css('max-height','4.5em').removeClass('active');
               icon.css('transform','rotate(0deg)');
            }
            else{
               objtarget.css('max-height','none').addClass('active');
               icon.css('transform','rotate(180deg)');
            }
         });
       }
       else{
         return false;
       }
    });          
    

});

