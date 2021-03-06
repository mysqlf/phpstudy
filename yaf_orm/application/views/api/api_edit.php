<link href="<?php echo DOMAIN_NAME?>/res/style.css" rel="stylesheet">
<script src="<?php echo DOMAIN_NAME?>/res/js/jquery.min.js"></script>
<!--修改接口 start-->
    <div style="border:1px solid #ddd">
        <div style="background:#f5f5f5;padding:20px;position:relative">
            <h4>修改接口<span style="font-size:12px;padding-left:20px;color:#a94442">注:"此色"边框为必填项</span></h4>
            <div style="margin-left:20px;">
                <form action="<?php echo site_url('api/api_edit') ?>" method="post">
                    <h5>基本信息</h5>
                    <div class="form-group has-error">
                        <div class="input-group">
                            <div class="input-group-addon">接口编号</div>
                            <input type="hidden" name="id" value="<?php echo $info['id']?>"/>
                            <input type="text" class="form-control" name="num" placeholder="接口编号" value="<?php echo $info['num']?>" required="required">
                        </div>
                    </div>
                    <div class="form-group has-error">
                        <div class="input-group">
                            <div class="input-group-addon">数据存表</div>
                            <input type="text" class="form-control" name="table" placeholder="数据存表" value="<?php echo $info['table']?>" required="required">
                        </div>
                    </div>
                    <div class="form-group has-error">
                        <div class="input-group">
                            <div class="input-group-addon">请求类别</div>
                            <select class="form-control" name="req_type">
                                <option <?php if ($info['req_type']=='http'): ?>
                                    selected
                                <?php endif ?> value="http">http</option>
                                <option <?php if ($info['req_type']=='https'): ?>
                                    selected
                                <?php endif ?> value="https">https</option>
                                <option <?php if ($info['req_type']=='soap'): ?>
                                    selected
                                <?php endif ?> value="soap">soap</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group has-error">
                        <div class="input-group">
                            <div class="input-group-addon">接口名称</div>
                            <input type="text" class="form-control" name="name" placeholder="接口名称" value="<?php echo $info['name']?>" required="required">
                        </div>
                    </div>
                    <div class="form-group has-error">
                        <div class="input-group">
                            <div class="input-group-addon">请求地址</div>
                            <input type="text" class="form-control" name="url" placeholder="请求地址" value="<?php echo $info['url']?>" required="required">
                        </div>
                    </div>
                    <div class="form-group">
                        <textarea name="des" class="form-control" placeholder="描述"><?php echo $info['des']?></textarea>
                    </div>
                    <div class="form-group" required="required">
                        <select class="form-control" name="type">
                            <?php
                                $selected[0] = ($info['type'] == 'GET') ? 'selected' : '';
                                $selected[1] = ($info['type'] == 'POST') ? 'selected' : '';
                            ?>
                            <option value="GET"  <?php echo $selected[0]?>>GET</option>
                            <option value="POST" <?php echo $selected[1]?>>POST</option>
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

                            <?php $count = count($info['parameter']['name']);?>
                            <?php for($i=0;$i<$count;$i++){ ?>
                            <tr>
                                <td class="form-group has-error">
                                    <input type="text" class="form-control" name="p[name][]" placeholder="参数名" value="<?php echo $info['parameter']['name'][$i]?>" required="required">
                                </td>
                                <td class="form-group has-error">
                                    <input type="text" class="form-control" name="p[paramType][]" placeholder="参数类型" value="<?php echo $info['parameter']['paramType'][$i]?>"  required="required">
                                    </td>
                                <td>
                                    <?php
                                        $selected[0] = ($info['parameter']['type'][$i] == 'Y') ? 'selected' : '';
                                        $selected[1] = ($info['parameter']['type'][$i] == 'N') ? 'selected' : '';
                                    ?>
                                    <select class="form-control" name="p[type][]">
                                        <option value="Y" <?php echo $selected[0]?>>Y</option>
                                        <option value="N" <?php echo $selected[1]?>>N</option>
                                    </select>
                                </td>
                                <td><input type="text" class="form-control" name="p[default][]" placeholder="缺省值" value="<?php echo $info['parameter']['default'][$i]?>"></td>
                                <td><textarea name="p[des][]" rows="1" class="form-control" placeholder="描述"><?php echo $info['parameter']['des'][$i]?></textarea></td>
                                <td><button type="button" class="btn btn-danger" onclick="del(this)">删除</button></td>
                            </tr>
                            <?php } ?>

                            </tbody>
                        </table>
                    </div>
                    <div class="form-group">
                        <h5>返回结果</h5>
                        <textarea name="re" rows="3" class="form-control" placeholder="返回结果"><?php echo $info['re']?></textarea>
                    </div>
                    <div class="form-group">
                        <h5>备注</h5>
                        <textarea name="memo" rows="3" class="form-control" placeholder="备注"><?php echo $info['memo']?></textarea>
                    </div>
                    <button class="btn btn-success">Submit</button>
                </form>
            </div>
        </div>
    </div>
    <script>
        function add(){
            var $html ='<tr>' +
                '<td class="form-group has-error" >' +
                    '<input type="text" class="form-control has-error" name="p[name][]" placeholder="参数名" required="required"></td>' +
                '<td class="form-group has-error">' +
                    '<input type="text" class="form-control" name="p[paramType][]" placeholder="参数类型" required="required">' +
                '</td>' +
                '<td>' +
                    '<select class="form-control" name="p[type][]">' +
                        '<option value="Y">Y</option> <option value="N">N</option>' +
                    '</select >' +
                '</td>' +
                '<td>' +
                    '<input type="text" class="form-control" name="p[default][]" placeholder="缺省值">' +
                '</td>' +
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
    <!--修改接口 end-->
<script src="<?php echo DOMAIN_NAME?>/res/jquery.cookie.js"></script>
