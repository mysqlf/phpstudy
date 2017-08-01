<!-- 
<div class="col-lg-5">
    <div class="ibox float-e-margins">
        <div class="ibox-content">
        <h2>API添加</h2>
            <form action="<?php echo site_url('api/api_add') ?>" method="post" id="signupForm" class="form-horizontal">
                <p></p>
                <div class="form-group">
                <label class="col-lg-2 control-label">接口名</label>
                    <div class="col-lg-10">
                    <input type="text" id="api_name" name="api_name" placeholder="接口名称" class="form-control "> 
                     <span class="help-block m-b-none">例如块级文本在这里。</span> 
                    </div>
                </div>
                <div class="form-group">
                <label class="col-lg-2 control-label">接口地址</label>
                    <div class="col-lg-10">
                    <input type="text" id="api_url" name="api_url" placeholder="接口地址" class="form-control ">
                    </div>
                </div>
                <div class="form-group">
                <label class="col-lg-2 control-label">数据表</label>
                    <div class="col-lg-10">
                    <input type="text" id="api_table" name="api_table" placeholder="数据表" class="form-control ">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-lg-offset-2 col-lg-10">
                        <input class="btn btn-sm btn-white" type="submit">
                    </div>
                </div>
            </form>
        </div>
    </div>
</div> -->
 <!--添加接口 start-->
    
<!--js自动保存到cookie  star-->
<!--     <link href="<?php echo DOMAIN_NAME?>/res/bootstrap-3.3.4-dist/css/bootstrap.min.css" rel="stylesheet"> -->
    <link href="<?php echo DOMAIN_NAME?>/res/style.css" rel="stylesheet">
    <script src="<?php echo DOMAIN_NAME?>/res/js/jquery.min.js"></script>
    <script>
        
        $(function(){
            
                    $("textarea[name='des'],textarea[name='re'],textarea[name='memo']").keydown(function () {
                        AutoSave();
                    });
                    
                    $(".btn-success").click(function(){
                        DeleteCookie('apimanage');
                    });
            
        });
        </script>
<script>    
/**
*
*自动保存文字到cookie中
*http://www.xuebuyuan.com/1323493.html
*
*/
function AutoSave() {
    var des = $("textarea[name='des']").val();
    var re  = $("textarea[name='re']").val();
    var memo= $("textarea[name='memo']").val();
    var _value = des + ";"+ re+";"+memo;
    if (_value==";;"){
        var LastContent = GetCookie('apimanage');
        
        if (LastContent == ";;") return;
        var text = LastContent.split(";");
        if (des != text[0] || re!=text[1] || memo!=text[2] ){
            if (confirm("加载保存的记录")) {
                $("textarea[name='des']").html(text[0]);
                $("textarea[name='re']").html(text[1]);
                $("textarea[name='memo']").html(text[2]);
                return true;
            }
        }

    } else {
        var expDays = 30;
        var exp = new Date();
        exp.setTime(exp.getTime() + (expDays * 86400000)); // 24*60*60*1000 = 86400000
        var expires = '; expires=' + exp.toGMTString();

        // SetCookie
        document.cookie = "apimanage=" + escape(_value) + expires;
    }
}

function getCookieVal(offset) {
    var endstr = document.cookie.indexOf(";", offset);
    if (endstr == -1) endstr = document.cookie.length;
    return unescape(document.cookie.substring(offset, endstr));
}

function GetCookie(name) {
    var arg = name + "=";
    var alen = arg.length;
    var clen = document.cookie.length;
    var i = 0;
    while (i < clen) {
        var j = i + alen;
        if (document.cookie.substring(i, j) == arg) return getCookieVal(j);
        i = document.cookie.indexOf(" ", i) + 1;
        if (i == 0) break;
    }
    return null;
}

function DeleteCookie(name) {
    var exp = new Date();
    exp.setTime(exp.getTime() - 1);
    var cval = GetCookie(name);
    document.cookie = name + "=" + cval + ";expires=" + exp.toGMTString();
}
</script>
<!--js自动保存到cookie  end-->

    <div style="border:1px solid #ddd">
        <div style="background:#f5f5f5;padding:20px;">
            <h4>添加接口<span style="font-size:12px;padding-left:20px;color:#a94442">注:"此色"边框为必填项</span></h4>
            <div style="margin-left:20px;">
                <form action="<?php echo site_url('api/api_add') ?>" method="post">
                <input type="hidden" name="aid" value="<?php echo $cate; ?>">
                    <h5>基本信息</h5>
                    <div class="form-group has-error">
                        <div class="input-group">
                            <div class="input-group-addon">接口编号</div>
                            <input type="text" class="form-control" name="num" placeholder="接口编号" required="required">
                        </div>
                    </div>
                    <div class="form-group has-error">
                        <div class="input-group">
                            <div class="input-group-addon">数据存表</div>
                            <input type="text" class="form-control" name="table" placeholder="数据存表" required="required">
                        </div>
                    </div>
                    <div class="form-group has-error">
                        <div class="input-group">
                            <div class=" input-group-addon">请求类别</div>
                            <select class="form-control" name="req_type" >
                                <option value="http">http</option>
                                <option value="https">https</option>
                                <option value="soap">soap</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group has-error">
                        <div class="input-group">
                            <div class="input-group-addon">接口名称</div>
                            <input type="text" class="form-control" name="name" placeholder="接口名称" required="required">
                        </div>
                    </div>
                    <div class="form-group has-error">
                        <div class="input-group">
                            <div class="input-group-addon">请求地址</div>
                            <input type="text" class="form-control" name="url" placeholder="请求地址" required="required">
                        </div>
                    </div>
                    <div class="form-group">
                        <textarea name="des" class="form-control" placeholder="描述"></textarea>
                    </div>
                    <div class="form-group" required="required">
                        <select class="form-control" name="type">
                            <option value="GET">GET</option>
                            <option value="POST">POST</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <h5>请求参数</h5>
                        <table class="table">
                            <thead>
                            <tr>
                                <th class="col-md-3">参数名</th>
                                <th class="col-md-2">参数类型</th>
                                <th class="col-md-2">必传</th>
                                <th class="col-md-2">缺省值</th>
                                <th class="col-md-4">描述</th>
                                <th class="col-md-1">
                                    <button type="button" class="btn btn-success" onclick="add()">新增</button>
                                </th>
                            </tr>
                            </thead>
                            <tbody id="parameter">
                            <tr>
                                <td class="form-group has-error">
                                    <input type="text" class="form-control" name="p[name][]" placeholder="参数名" required="required">
                                </td>
                                <td class="form-group has-error"><input type="text" class="form-control" name="p[paramType][]" placeholder="参数类型" required="required"></td>
                                <td>
                                    <select class="form-control" name="p[type][]">
                                        <option value="Y">Y</option>
                                        <option value="N">N</option>
                                    </select>
                                </td>
                                <td><input type="text" class="form-control" name="p[default][]" placeholder="缺省值"></td>
                                <td><textarea name="p[des][]" rows="1" class="form-control" placeholder="描述"></textarea></td>
                                <td><button type="button" class="btn btn-danger" onclick="del(this)">删除</button></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="form-group">
                        <h5>返回结果</h5>
                        <textarea name="re" rows="3" class="form-control" placeholder="返回结果"></textarea>
                    </div>
                    <div class="form-group">
                        <h5>备注</h5>
                        <textarea name="memo" rows="3" class="form-control" placeholder="备注"></textarea>
                    </div>
                    <button class="btn btn-success">Submit</button>
                </form>
            </div>
        </div>
    </div>
    <script>
        function add(){
            var $html ='<tr>' +
                '<td class="form-group has-error" ><input type="text" class="form-control has-error" name="p[name][]" placeholder="参数名" required="required"></td>' +
                '<td class="form-group has-error">' +
                '<input type="text" class="form-control" name="p[paramType][]" placeholder="参数类型" required="required"></td>' +
                '<td>' +
                '<select class="form-control" name="p[type][]">' +
                '<option value="Y">Y</option> <option value="N">N</option>' +
                '</select >' +
                '</td>' +
                '<td>' +
                '<input type="text" class="form-control" name="p[default][]" placeholder="缺省值"></td>' +
                '<td>' +
                '<textarea name="p[des][]" rows="1" class="form-control" placeholder="描述"></textarea>' +
                '</td>' +
                '<td>' +
                '<button type="button" class="btn btn-danger" onclick="del(this)">删除</button>' +
                '</td>' +
                '</tr >';
            $('#parameter').append($html);
        }
        function del(obj){
            $(obj).parents('tr').remove();
        }
    </script>
    <!--添加接口 end-->
<script src="<?php echo DOMAIN_NAME?>/res/jquery.min.js"></script>
<script src="<?php echo DOMAIN_NAME?>/res/jquery.cookie.js"></script>
<script src="<?php echo DOMAIN_NAME?>/res/bootstrap-3.3.4-dist/js/bootstrap.min.js"></script>
