UE.registerUI('选择上传',function(editor,uiName){
    //创建dialog
    var dialog = new UE.ui.Dialog({
        //指定弹出层中页面的路径，这里只能支持页面,因为跟addCustomizeDialog.js相同目录，所以无需加路径
        iframeUrl:ROOT+"admin.php/Uploads/choose/name/content/type/3",
        //需要指定当前的编辑器实例
        editor:editor,
        //指定dialog的名字
        name:"选择上传",
        //dialog的标题
        title:"选择上传",
        //指定dialog的外围样式
        cssRules:"width:680px;height:425px;",
    });
    //参考addCustomizeButton.js
    var btn = new UE.ui.Button({
        name:'dialogbutton' + uiName,
        title: uiName,
        //需要添加的额外样式，指定icon图标，这里默认使用一个重复的icon
        cssRules :'background-position: -380px 0;',
        onclick:function () {
            //渲染dialog
            dialog.render();
            dialog.open();
        }
    });

    return btn;
}, 9);