$(function(){
    //表单提示语
    var select_form = $('.form-1 input:text,textarea'); //选择需要添加提示文字的表单
    for(i=0;i<select_form.length;i++){          
        select_form.eq(i).val(select_form.eq(i).attr('fs')).css('color','#999');//设置表单的值为一个属性值为“fs”的值    
    }  
    select_form.focus(function(){   //获得焦点
        if($(this).val()==$(this).attr('fs')){
          $(this).val('');
          $(this).css('color','#333');
        }    
    })
    select_form.blur(function(){    //失去焦点  
        if($(this).val()==''){
           $(this).val($(this).attr('fs'));
           $(this).css('color','#999');
        }
    });
    //首页广告切换
    $(".banner").hover(function(){
    $(this).find(".prev,.next").fadeTo("show",0.1);
    },function(){
        $(this).find(".prev,.next").hide();
    })
    //产品分类二级菜单经过事件
    $(".category > li").hover(function () {
        $(this).children(".menu").addClass('active');
        $(this).prev().children(".menu").removeClass('menu').addClass('menu2');
        $(this).children(".son_nav").show();
    },function () {
        $(this).children(".menu").removeClass('active');
        $(this).prev().children(".menu2").removeClass('menu2').addClass('menu');
        $(this).children(".son_nav").hide();
    });
    //Validform初始化
    $.Tipmsg.r=null;
    $("#myform1,#myform2").Validform({
        tiptype:function(msg){
            layer.msg(msg, {icon: 5});
        },
        tipSweep :true,
        postonce:true,
        ajaxPost:true,
        callback:function(data){
            if(data.status=="1"){
                layer.msg(data.info, {icon:6,time:1000,});
                setTimeout(function(){
                    location.href = data.url;
                },1200);
            };
            if(data.status=="0"){
                layer.msg(data.info, {icon:5,time:2000,});
            };
        }
    });
    //数量加减1
    $("#add_num").click(function(){
        num1=$("#buynum").val();
        num1++;
        $("#buynum").val(num1);
    });
    $("#red_num").click(function(){
        num2=$("#buynum").val();
        if (num2>1) {num2--;}else{
	        layer.msg('数量不能小于1', {icon: 5});
	    };
        $("#buynum").val(num2);
    });
    //end
})

//大家都喜欢，最新，推荐，特价
function choosepush(id){
    $('.push .good a').removeClass('active');
    $('.push .good_info').hide();
    $('.push .gt_'+id+' a').addClass('active');
    $('.push .gi_'+id).show();
}