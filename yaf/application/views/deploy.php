<div id="page-wrapper" class="gray-bg">
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row wrapper border-bottom white-bg page-heading">
                <div class="col-lg-10">
                    <h2>接口配置</h2>
                </div>
        </div>
        <div class="col-lg-5">
            <div class="ibox float-e-margins">
                <form method="post" action="<?php echo site_url('C_Website/drive_write/').$web_id.'/'.$m_id; ?>" class="form-horizontal">
                    <div class="form-group"><label class="col-lg-2 control-label">广告主</label>
                        <div class="col-lg-10"><input type="text"  value="<?php echo $adv_info['name']; ?>" readonly  class="form-control"></div>
                        <input type="hidden" name="web_id" value="<?php echo $web_id; ?>">
                        <input type="hidden" name="m_id" value="<?php echo $m_id; ?>">
                    </div>
                    <div class="form-group"><label class="col-lg-2 control-label">活动站</label>
                        <div class="col-lg-10"><input type="text" value="<?php echo $adv_info['web_name']; ?>" readonly class="form-control"></div>
                    </div>
                    <div class="form-group"><label class="col-lg-2 control-label">模块名称</label>
                        <div class="col-lg-10"><input type="text"  value="<?php echo $model_name['name']; ?>" readonly class="form-control"></div>
                    </div>
                    <div class="form-group"><label class="col-lg-2 control-label">开始时间</label>
                        <div class="col-lg-10"><input type="text" name="start_date" value="<?php echo $model_status['start_date'] ?>" class="form-control"></div>
                    </div>
                    <div class="form-group"><label class="col-lg-2 control-label">结束时间</label>
                        <div class="col-lg-10"><input type="text" name="end_date" value="<?php echo $model_status['end_date'] ?>" class="form-control"></div>
                    </div>
                    <div class="form-group"><label class="col-lg-2 control-label">是否启用</label>
                        <input type="radio" name="is_use" value="1"<?php if ($model_status['is_use']==1) {
                                echo "checked";
                            } ?>>启用
                            <input type="radio" value="0" name="is_use" <?php if ($model_status['is_use']==0) {
                                echo "checked";
                            } ?>>禁用
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

