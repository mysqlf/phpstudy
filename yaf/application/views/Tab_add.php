<div id="page-wrapper" class="gray-bg">
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row wrapper border-bottom white-bg page-heading">
                <div class="col-lg-10">
                    <h2>表添加</h2>
                </div>
        </div>
        <div class="col-lg-5">
            <div class="ibox float-e-margins">
                <form method="post" action="<?php echo site_url('C_Table/Tab_add') ?>" class="form-horizontal">
                    <div class="form-group"><label class="col-lg-2 control-label">所属模块</label>
                        <div class="col-lg-10">
                            <select name='mid' class="form-control">
                                <?php foreach ($model as $key => $value):?>
                                    <option value="<?php echo $value['id'] ?>"><?php echo $value['name'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group"><label class="col-lg-2 control-label">表名</label>
                        <div class="col-lg-10"><input type="text" name="name" placeholder="表名" class="form-control"></div>
                    </div>
                    <div class="form-group"><label class="col-lg-2 control-label">表属性</label>
                        <div class="col-lg-10">
                        <select name="type" class="form-control">
                            <option value="innodb">innodb</option>
                            <option value="myisam">myisam</option>
                        </select>
                        </div>
                    </div>
                     <div class="form-group"><label class="col-lg-2 control-label">说明</label>
                        <div class="col-lg-10"><input type="text" name="remark" placeholder="表说明" class="form-control"></div>
                    </div>
                    
                    
                    
                    <div class="form-group">
                        <div class="col-lg-offset-2 col-lg-10">
                            <button class="btn btn-sm btn-white" type="submit">提交</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>



