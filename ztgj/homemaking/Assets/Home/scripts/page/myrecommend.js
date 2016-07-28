/**
 * 
 * @authors Your Name (you@example.org)
 * @date    2015-05-22 14:01:55
 * @version $Id$
 */
define(function (require,exports,modules){
	var $=require('../lib/jquery');
  var popup=require("../plug/popup");
  require('../plug/jquery.validate.min');
  var reajax=require('../plug/ajax');
  var scrollRefresh=require('../plug/refresh');
  var ret=1;
  var pageNum=1;

      //后退功能
  $('.icon-return').on('click',function(){
        window.history.back(-1);
  });    

    var closearr=['#mask','.cancel_btn'];
    new popup('recomend_btn',closearr);

    $('#educate_btn').on('click',function(){
    	$('.educate_select').slideDown();
      $('#mask_inside').show();
    });
    
    $('.mes_select').on('click',function(){
    	$('.educate_select').slideUp();
        $($(this).parent('ul').attr('data-id')).val($(this).text()).attr('data-id',$(this).attr('data-id'));
        $('#mask_inside').hide();
    });





       //下拉刷新
        new scrollRefresh(ret,'Center-recommendelist',pageNum,function(data){
            readData(data)
        },function(){
           $('.friend_list').append('<p class="no_more">没有更多了</p>');
        });
        
   function readData(data){
    if(data.ret){
      var temp=""
      $(data.item).each(function(index,item){
        if(item.gender==1){
          var img_url=item.head_img_url||'Assets/Home/images/male_defult.jpg';
          var icon_class='icon-male';
        }
        else{
           var img_url=item.head_img_url||'Assets/Home/images/female_defult.jpg';
           var icon_class='icon-female';
        }
            temp+='<li><a href="javascript"><img src="'+img_url+'" alt="">'
                 +'<div class="user_mes"><p class="person_user_name"> '+item.userName+'<i class="'+icon_class+'"></i> </p>'
                 +'<p class="phone">'+item.mobile+'</p></div></a></li>';
      });
      $('#myrecommend ul').append(temp);
    }
    else{
      ret=0;
      $('#myrecommend').append('<p style="text-align: center;padding: 15px 0;color: #999;">没有更多信息了</p>');
    }
   }

  var formValidate=$("form").validate({
     submitHandler:function(form){
         var sendData={
                userName: $('#recommendname').val(),
                  mobile: $('#tel').val(),
                  gender: $('input[type="radio"]:checked').val(),
                     age: $('#age').val(),
                  degree: $('#educate_btn').attr('data-id')
            }

          
        new reajax('Center-recommended','post',sendData,'josn',function(data){
           $('.hidden_box_friend').show(); 
          if(data.type===1){
              $('#hidden_box').append('<div class="tips_box"><p class="tips_status"> <i class="icon-success"></i>推荐成功</p></div>');
                setTimeout(500,function(){
                $('.tips_box').remove();
                $('#mask').hide();
              });       
          }
          else{
             $('#hidden_box').append('<div class="tips_box"><p class="tips_status"> <i class="icon-success"></i>'+data.msg+'</p></div>');          
              setTimeout(function(){
                $('.tips_box').remove();
                $('#hidden_box').hide();
                $('#mask').hide();
              },1000);                                            
          }
        });
          setTimeout(function(){form.submit()},2000);
     },
      rules:{
         user_name:{
               required:true,
               zh_cn:true,
               maxlength:5,
               minlength:2
            },
          phoneVal:{
               required:true,
               istelephone:true,
               number:true
             },
          age:{
              required:true,
              number:true,
              max:45,
              min:16,
          }
        },
      messages:{
        user_name:{
          required:'请输入姓名',
          zh_cn:'请输入汉字',
          maxlength:'最多输入5个汉字',
          minlength:'最少输入2个汉字'
        },
       phoneVal:{
             required:' 请输入手机号',
             istelephone:'请输入正确的手机格式',
             number:'请输入数字'
           },
        age:{
            required:'请输入年龄',
            number:'请输入数字',
            max:'不能大于45岁',
            min:'不能小于16岁',
        }
      }
  });

});