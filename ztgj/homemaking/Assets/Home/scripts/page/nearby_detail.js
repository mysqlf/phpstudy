/**
 * 
 * @authors Your Name (you@example.org)
 * @date    2015-05-25 11:23:09
 * @version $Id$
 */
define(function (require,exports,moudel){
  var $=require('../lib/jquery');
	var popup=require("../plug/popup");
	var reajax=require('../plug/ajax');
  var closeArr=['#mask','.cancel_btn','.confirm_btn'];
  var $menu=$('#menu');
  var $popupbox=$('.popup_box');
  var Swiper=require('../plug/swiper.min');

      //后退功能
  $('.icon-return').on('click',function(){
        window.history.back(-1);
  });
    
    new popup('icon-moremenu',closeArr,function(){
    	$menu.show();
    },hidecallback);

    new popup('send_resume',closeArr,showcallback,hidecallback);

	var jobId = $('.jobname').attr('data-id');
	var comId = $('.compayname').attr('data-id');

	var sendData={
		            id:jobId,
		            comId:comId
		        }


   var html1='<h2>举报企业</h2><ul><li>虚假信息<input class="check_box" name="prosecute" value="虚假信息" type="checkbox"></li><li>不良信息<input class="check_box" name="prosecute" value="不良信息" type="checkbox"></li></ul><input type="button"  class="cancel_btn" value="取消"><input type="button" class="confirm_btn" value="确认">'

    $('.report_btn').on('click',function(){
    	$menu.hide();
    	$popupbox.show().html(html1);
    });


    $popupbox.on('click','.confirm_btn',function(){
        var prosecuteArr=[];
        $('input[name="prosecute"]:checked').each(function(index,item){
               prosecuteArr.push($(item).val());
        });
        var sendData={
           comId:comId, 
           subject:prosecuteArr
        }

    	new reajax('Near-prosecute','post',sendData,'json',function(data){
        var status;
         data.type ? status='success':status='fail';
         var temp='<div class="tips_box"><p class="tips_status"> <i class="icon-'+status+'"></i>'+data.msg+'</p></div>';
            $('#hidden_box,#mask').show();
            $('#hidden_box').append(temp).addClass('dateil_box');
              setTimeout(function(){
                $('.tips_box').remove();
                $('#hidden_box,#mask').hide();
                $('#hidden_box').removeClass('dateil_box');
              },2000);

        });
    });

    $('#hidden_box').on('click','.confirm_btn_a',function(){
        hidecallback();
        $('#mask').hide();
    });
    
    function showcallback(){
    	
		new reajax('Near-sign','post',sendData,'json',function(data){
		    if(data.type==1||data.type==2){
                $popupbox.html('<div class="tips_box"><p class="tips_status"> <i class="icon-'+data.status+'"></i>'+data.msg+'</p></div><div class="tac"><input type="button" class="confirm_btn_a" value="确认"></div>');
            }
             else{
	         $popupbox.html('<h2>'+data.title+'</h2><div class="tips_box">'+data.msg+'</div><input type="button"  class="cancel_btn" value="取消"><a href="'+data.data.url+'" class="confirm_btn_a">'+data.btn+'</a>');
                
	        }
            $popupbox.show();
		});

	};

	function hidecallback(){
    	$menu.hide();
    	$popupbox.hide().empty();
    }

   //初始化显示

     if($('#nearby_detail').attr('data-status')==1){

          $('#mask').show();
          $('#hidden_box').show();
          $popupbox.show().html('<div class="tips_box"><p class="tips_status"> <i class="icon-success"></i>保存信息成功，请选择报名</p></div><div class="tac"><input type="button" class="affirm_btn" value="确认"></div>');
          $('#mask').on('click',cleanCurrent);
          $popupbox.on('click','.affirm_btn',cleanCurrent);
     }

    
 function cleanCurrent(){
          $('#mask').hide();
          $popupbox.empty();
          $popupbox.hide();
        }

  $('.phone_btn').on('click',function(){
      var url=$(this).attr('data-url');
      $menu.hide();
      $popupbox.show().append('<div class="tips_box">请登录后再进行咨询</div><input type="button"  class="cancel_btn" value="取消"><a href="'+url+'" class="confirm_btn_a">登录</a>');

  });

   
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
