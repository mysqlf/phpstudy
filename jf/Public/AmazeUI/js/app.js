//ajax跳转
function ajaxjump (url) {
    $.get(url,function(data){
        if(data.status=="1"){
            layer.msg(data.info, {icon: 1,time:1000},function(){location.href = data.url;});
        }
        if(data.status=="0"){
            layer.msg(data.info, {icon: 2,time:1000},function(){location.href = data.url;});
        }
    });
}
//弹窗确定执行，取消执行
function confirm(url,str){
    layer.confirm(str, {icon: 3}, function(index){
        layer.close(index);
        ajaxjump(url);
    });
}
//页面加载执行
$(function() {
    //Validform初始化
    $.Tipmsg.r=null;
    $("#form1").Validform({
        tiptype:function(msg){
            layer.msg(msg, {icon:0});
        },
        tipSweep :true,
        postonce:true,
        ajaxPost:true,
        callback:function(data){
            if(data.status=="1"){
                layer.msg(data.info, {icon:1,time:1000,});
                setTimeout(function(){
                    location.href = data.url;
                },1200);
            };
            if(data.status=="0"){
                layer.msg(data.info, {icon:2,time:2000,});
            };
        }
    });
});