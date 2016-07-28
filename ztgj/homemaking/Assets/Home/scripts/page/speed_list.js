/**
 * 
 * @authors Your Name (you@example.org)
 * @date    2015-05-13 09:23:50
 * @version $Id$
 */
define(function (require, exports, module){
    var $=require('../lib/jquery');
    var popup=require("../plug/popup");
    var scrollRefresh=require('../plug/refresh');
    var reajax=require('../plug/ajax');
    var Tab=require('../plug/tab');
    var closearr=['#mask','.location_list'];

    var p=new popup("popup_btn",closearr,function(){
      $('#container a').addClass('stopa');
      $('.speed_line').show();
    },function(){
      $('#container a').removeClass('stopa');  
      $('.speed_line').hide();
    });
    
    var tab=new Tab('popup_btn','hidden_list');
    p._init();
    var ret=1;               //是否存在数_list据
    var pageNum=1;           //页码
    var ulbox=$('.speed_ul');
    var location_btn=$('#location_btn');
    var jobclass_btn=$('#jobclass_btn')
    $('.location_list').on('click',function(){
    !($('body').hasClass('bg_eee'))&&$('body').addClass('bg_eee');
    var oldlocation=location_btn.children('a').attr('data-id');
    var oldjobclass=jobclass_btn.children('a').attr('data-id');

    $($(this).closest('ul').attr('data-target')).children('a').text($(this).text()).attr('data-id',$(this).attr('data-id')).append('<i class="icon-angle"></i>');
    $(this).parent().children('li').removeClass('active');
    $(this).addClass('active');

    var location=location_btn.children('a').attr('data-id');
    var jobclass=jobclass_btn.children('a').attr('data-id');
    if((oldlocation==location)&&(oldjobclass==jobclass))
    {     
         $('.speed_line').hide();
         $('#mask,#hidden_box').hide();
           return false;       
    }
    else{
        $(document).off('scroll');
         scroll=null;
         var sendData={
             index:1,
             type:jobclass,
             site:location
        }

        var scroll=new scrollRefresh(ret,1,'Fast-index',sendData,readData,function(){
            $('#container').append('<p class="no_more">没有更多了</p>');
        });    

        $('#container').empty();
        new reajax('Fast-index','post',sendData,'json',function(data){
            if(!data.ret){
                  $("#container").html('<div id="error" style="width:100%; background-color:#fff; height:100%; text-align:center;"><img src="/Assets/Home/images/error.jpg"></div>')
                  $(document).off('scroll');
                  $('body').removeClass('bg_eee');
            }
          else{
            readData(data);        
          }
        });
    }
});

 var sendData={
             index:1,
             type:0,
             site:0
        }       
 $('.class_btn').on('click',function(){
  $(this).index()==0?$('.speed_line').css('left','0'):$('.speed_line').css('left','50%');
  

});



var scroll=new scrollRefresh(ret,1,'Fast-index',sendData,readData,function(){
            $('#container').append('<li class="no_more">没有更多了</li>');
        });    

    //写入数据
    function readData(data){
          if(data.ret){
           var temp="";
		    		$(data.items).each(function(index,item){
		    			var taotemp="";
		    			
                        $(item.taoLable).each(function(index,item){
                             taotemp+='<li class="weals">'+item+'</li>';
                        });
                        temp+='<li class="company_list" data-id='+item.id+'><a class="company_top" href="Fast-details-id-'+item.id+'">'
				               +'<img class="company_logo" src='+item.photoInfoPosUrl+'>'
				               +'<div class="company_right">'
				                  +' <p class="companyname">'+item.comName+'</p>'
				                  +' <p class="address_list">'+item.jobLocation+'<span class="num_people">'+item.appCount+'人报名</span></p>'
				                   +'<p class="payment">'+item.salary+'元/月</p></div></a>'
				               +'<ul>'+taotemp+'</ul><p class="compay_mindetail">'+item.posDesc+'</p></li>';
		    		});
		    		ulbox.append(temp);
            }
          else{
              ret=0;
              $('#container').append('<p style="text-align: center;padding: 15px 0;color: #999;">没有更多信息了</p>');

            }   
    }

});
