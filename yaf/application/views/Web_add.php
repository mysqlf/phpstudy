
<div id="page-wrapper" class="gray-bg">
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row wrapper border-bottom white-bg page-heading">
                <div class="col-lg-10">
                    <h2>站点添加</h2>
                </div>
        </div>
        <div class="col-lg-5">
            <div class="ibox float-e-margins">
                <form method="post" action="<?php echo site_url('C_Website/web_add_page/').$adv_id; ?>" class="form-horizontal">
                    
                    <div class="form-group"><label class="col-lg-2 control-label">开始时间</label>
                        <div class="col-lg-10"><input type="text" name="start" placeholder="选择时间" class="form-control">
                        </div>
                    </div>
                    <div class="form-group"><label class="col-lg-2 control-label">结束时间</label>
                        <div class="col-lg-10"><input type="text" name="end" placeholder="选择时间" class="form-control"></div>
                    </div>
                    <div class="form-group"><label class="col-lg-2 control-label">站点名称</label>
                        <div class="col-lg-10"><input type="text" name="name" placeholder="站点名称" class="form-control"></div>
                        <input type="hidden" name="ad_id" value="<?php echo $adv_id; ?>">
                    </div>
                     <div class="form-group"><label class="col-lg-2 control-label">站点编码</label>
                        <div class="col-lg-10"><input type="text" name="code" placeholder="站点编码" class="form-control"></div>
                    </div>
                    <div class="form-group"><label class="col-lg-2 control-label">站点链接</label>
                        <div class="col-lg-10"><input type="text" name="url" placeholder="站点链接" class="form-control"></div>
                    </div>
                    <div class="form-group"><label class="col-lg-2 control-label">站点说明</label>
                        <div class="col-lg-10"><input type="text" name="remark" placeholder="站点说明" class="form-control"></div>
                    </div>
                    
                    <div class="form-group"><label class="col-sm-2 control-label">选择地区 </label>
                        <div class="col-sm-10">
                         <?php foreach ($area as $key => $value):?>
                            <label class="checkbox-inline i-checks">
                                <input type="checkbox" name="province[]" value="<?php echo $value['province_id'] ?>"><?php echo $value['province_name'] ?>
                                </label>
                        <?php endforeach; ?>
                        </div>
                    </div>
                    <div class="form-group"><label class="col-sm-2 control-label">选择媒体</label>
                        <div class="col-sm-10">
                         <?php foreach ($media as $key => $value):?>
                            <label class="checkbox-inline i-checks">
                                <input type="checkbox" name="media[]" value="<?php echo $value['id'] ?>"><?php echo $value['media_name'] ?>
                                </label>
                        <?php endforeach; ?> 
                        </div>
                    </div>
                    <div class="form-group"><label class="col-sm-2 control-label">选择功能 </label>
                        <div class="col-sm-10">
                        <?php foreach ($models as $key => $value):?>
                            <div class="i-checks">
                                <label>
                                    <input type="checkbox" name="feat[]" value="<?php echo $value['id'] ?>"><?php echo $value['name'] ?> 
                                </label>
                            </div>
                        <?php endforeach; ?>
                        </div>
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
