//选项卡
jQuery.Huitab =function(tabBar,tabCon,class_name,tabEvent,i){
    var $tab_menu=$(tabBar);
    // 初始化操作
    $tab_menu.removeClass(class_name);
    $(tabBar).eq(i).addClass(class_name);
    $(tabCon).hide();
    $(tabCon).eq(i).show();
    
    $tab_menu.bind(tabEvent,function(){
        $tab_menu.removeClass(class_name);
        $(this).addClass(class_name);
        var index=$tab_menu.index(this);
        $(tabCon).hide();
        $(tabCon).eq(index).show();
    });
}
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
    //用户菜单经过事件
    $(".welcome").hover(function () {
        $(this).children(".son_nav").slideDown(200);
    },function () {
        $(this).children(".son_nav").slideUp(200);
    });
    //分类选择
    $(".category").hover(function () {
        $(this).children(".cate_son_menu").slideDown(200);
    },function () {
        $(this).children(".cate_son_menu").slideUp(200);
    });
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
    // 表单选项卡执行
    $.Huitab("#tab_form .tabBar span","#tab_form .tabCon","current","click","0");
});
// 表单全选，全不选操作
function CheckAll(value,obj)  {
    var form=document.getElementsByTagName("form")
        for(var i=0;i<form.length;i++){
        for (var j=0;j<form[i].elements.length;j++){
        if(form[i].elements[j].type=="checkbox"){ 
        var e = form[i].elements[j]; 
        if (value=="selectAll"){e.checked=obj.checked}     
        else{e.checked=!e.checked;} 
           }
        }
    }
}
//单文件上传点击事件
function up_file_one(name,att){
    layer.open({
        type: 2,
        move: false,
        title: "<i class='icon-arrow-circle-o-up'></i> 选择上传",
        area: ['680px', '460px'],
        fix: true, //不固定
        content: ROOT+'admin.php/Uploads/choose/type/1/name/'+name+'/att/'+att
    });
}
//多文件上传点击事件
function up_file_more(name,att){
    layer.open({
        type: 2,
        move: false,
        title: "<i class='icon-arrow-circle-o-up'></i> 选择上传",
        area: ['680px', '460px'],
        fix: true, //不固定
        content: ROOT+'admin.php/Uploads/choose/type/2/name/'+name+'/att/'+att
    });
}
//单文件删除预览上传
function del_oneupimg(name){
    $('#upbox_'+name+' .preview').empty();
}
//多文件删除预览上传
function del_moreupimg(name,id){
    $('#upsbox_'+name+' .preview #imgid_'+id).remove();
    var ImgIds="";
    $("#upsbox_"+name+" .preview img").each(function(){
        ImgIds += $(this).attr("alt")+",";
    });
    parent.$("#upsbox_"+name+" input").val(ImgIds);
    ImgIds = null;
}