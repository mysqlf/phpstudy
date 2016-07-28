/**
 * 
 * @authors Your Name (you@example.org)
 * @date    2015-05-14 10:05:19
 * @version $Id$
 */

define(function (require, exports, module){
  var $=require('../lib/jquery');
  var popup=require("../plug/popup");
  require('../plug/jquery.validate.min');
  require('../plug/mobiscroll/mobiscroll');
	

  //后退功能
  $('.icon-return').on('click',function(){
        window.history.back(-1);
  });

    
    var hideBoxs = $('.educate_select,.jobage_select,.job_status,.address_select');

    var closeArr = ['#mask','.mes_select_btn'];

    var        e = new popup('educate_text',closeArr,function(){
                        $('.educate_select').slideDown();
    					},function(){
                        $('.educate_select').slideUp();
                     
                     });

    var     jAge = new popup('jobage_text',closeArr,function(){

                        $('.jobage_select').slideDown();
    					},function(){
                        $('.jobage_select').slideUp();
                      
                     });
    var jStatus  = new popup('jobstatus_text',closeArr,function(){

                        $('.job_status').slideDown();
    					},function(){
                        $('.job_status').slideUp();
                       
                     });
  
    
    new popup('address_text',closeArr,function(){
                        $('.address_select').slideDown();
              },function(){
                        $('.address_select').slideUp();
                        $('.address_province,.address_city,.address_area').empty();  
                     });



//地理位置获取
    $('#hidden_box').on('click','.mes_select_btn',function(){
      var parentInput=$($(this).parent('ul').attr('data-id'));
    	parentInput.val($(this).text());
      var message="";
        if($(this).parent().prev('ul'))
        { 
          $('.active_adress').each(function(index,item){
            message=message+$(item).text();
          });
        }

        message=message+$(this).text();
        parentInput.val(message);
        parentInput.next().val($(this).attr('data-id'));
    });

    $('.address_province').on('click','li',function(){
      if(!$(this).hasClass('mes_select_btn')){
      $('.address_province').children('.active_adress').removeClass('active_adress');
      $(this).addClass('active_adress');
      }
      else{
        $('.address_province').children('.active_adress').removeClass('active_adress');
      }
    });
    
    $('.address_city').on('click','li',function(){
      if(!$(this).hasClass('mes_select_btn')){
      $('.address_city').children('.active_adress').removeClass('active_adress');
      $(this).addClass('active_adress');
     }
    });


function ajaxjsonp(key,code,self,range){
    if(localStorage[key]){
            var datas=localStorage[key];
            $('.address_'+range+'').append(datas);
          }
          else{
            if(self.attr('data-child'))
            {   
               if(code){
                  var senddata={
                     code : code
                  }
                }
                $.ajax({
                  type:'post',
                  data:senddata||'',
                  timeout:300,
                  dataType:'json',
                  url: 'http://weixin.hr5156.com/Ptime-areaSelect',
                  success: function(data){
                    if(data.ret){
                      var datatemp="";
                      $(data.items).each(function(index,item){
                         var temp='<li data-id="'+item.code+'" data-child="'+item.hasChild+'" class="'+range+'_select">'+item.name+'</li>'
                          if(!item.hasChild)
                          {
                          temp='<li data-id="'+item.code+'" data-child="'+item.hasChild+'" class="'+range+'_select mes_select_btn">'+item.name+'</li>';
                          }
                        datatemp=datatemp+temp;
                      });
                       $('.address_'+range+'').append(datatemp);
            
                        localStorage[key]=datatemp;
                    }
                  },
                  error:function(){
                  }
                });
             }
          } 
}



    $('.address_select').on('click','.province_select',function(){
        $('.address_city,.address_area').empty();
          var code=$(this).attr('data-id');
          var range="city";
          var key=range+code;
          var self=$(this);
          ajaxjsonp(key,code,self,range);  
      });
     

      $('.address_select').on('click','.city_select',function(){
        $('.address_area').empty();
          var code=$(this).attr('data-id');
          var range="area";
          var key=range+code;
          var self=$(this);
          ajaxjsonp(key,code,self,range);
      });


      $('.address_text').on('click',function(){
          var range="province";
          var key=range;
          var code="";
          var self=$(this);
          ajaxjsonp(key,code,self,range);
        });



var currYear = (new Date()).getFullYear(); 
        var opt={};
        opt.date = {preset : 'date'};
        opt.default = {
          theme: 'android-ics light', //皮肤样式
          startYear: currYear - 100, //开始年份
          endYear: currYear //结束年份
        };

        $('.input-date').mobiscroll($.extend(opt['date'], opt['default']));

	// //时间获取
	// $('#birth_time').change(function(){
 //    alert($(this).val());
	// 	$('.birth_time').val($(this).val());
	// });

  var formValidate=$("form").validate({
      rules:{
         user_name:{
               required:true,
               zh_cn:true,
               maxlength:6,
               minlength:2
          },
      },
      messages:{
        user_name:{
          required:'请输入姓名',
          zh_cn:'请输入汉字',
          maxlength:'最多输入6个汉字',
          minlength:'最少输入2个汉字'
        },
    }
  });

});