$(function(){
    //Validform初始化
    $.Tipmsg.r=null;
    $("#myform1,#myform2").Validform({
        tiptype:function(msg){
            layer.open({
                content: msg,
                style: 'background-color:#09C1FF; color:#fff; border:none;',
                time: 2
            });
        },
        tipSweep :true,
        postonce:true,
        ajaxPost:true,
        callback:function(data){
            if(data.status=="1"){
                layer.open({
                    content: data.info,
                    style: 'background-color:#09C1FF; color:#fff; border:none;',
                    time: 2
                });
                setTimeout(function(){
                    location.href = data.url;
                },1200);
            };
            if(data.status=="0"){
                layer.open({
                    content: data.info,
                    style: 'background-color:#09C1FF; color:#fff; border:none;',
                    time: 1
                });
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
            layer.open({
                content: '数量不能小于1',
                style: 'background-color:#09C1FF; color:#fff; border:none;',
                time: 2
            });
        };
        $("#buynum").val(num2);
    });
    //end
})