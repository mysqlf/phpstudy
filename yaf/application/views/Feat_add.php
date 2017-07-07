<div id="page-wrapper" class="gray-bg">
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row wrapper border-bottom white-bg page-heading">
                <div class="col-lg-10">
                    <h2>模块添加</h2>
                </div>
        </div>
        <div class="col-lg-5">
            <div class="ibox float-e-margins">
                <form method="post" action="<?php echo site_url('C_Features/Feat_add') ?>" class="form-horizontal">
                    <div class="form-group"><label class="col-lg-2 control-label">模块名称</label>
                        <div class="col-lg-10"><input type="text" name="name" placeholder="模块名称" class="form-control"></div>
                    </div>
                    <div class="form-group"><label class="col-lg-2 control-label">唯一码</label>
                        <div class="col-lg-10"><input type="text" name="code" placeholder="唯一码" class="form-control"></div>
                    </div>
                    <div class="form-group"><label class="col-lg-2 control-label">应用行业</label>
                        <div class="col-lg-10"><select name="trade" class="form-control">
                            <?php foreach ($trade as  $value): ?>
                                <option value="<?php echo $value ?>"><?php echo $value ?></option>
                            <?php endforeach; ?>
                        </select></div>
                    </div>
                    <div class="form-group"><label class="col-lg-2 control-label">数据私有</label>
                         <div class="col-sm-10">
                            <label class="checkbox-inline i-checks"> <input type="radio" name="is_private" value="1">是</label>
                            <label class="checkbox-inline i-checks"> <input type="radio" name="is_private" value="0">否</label>
                            (是否为广告主建立独立数据表)
                        </div>
                    </div>
                    <div class="form-group"><label class="col-lg-2 control-label">模块说明</label>
                        <div class="col-lg-10"><input type="text" name="remark" placeholder="模块说明" class="form-control"></div>
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
