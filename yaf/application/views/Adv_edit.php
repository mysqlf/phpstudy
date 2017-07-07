<div id="page-wrapper" class="gray-bg">
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row wrapper border-bottom white-bg page-heading">
                <div class="col-lg-10">
                    <h2>广告主添加</h2>
                </div>
        </div>
        <div class="col-lg-5">
            <div class="ibox float-e-margins">
                <form method="post" action="<?php echo site_url('C_Advertisers/Adv_edit/').$adv['id']; ?>" class="form-horizontal">
                    <div class="form-group"><label class="col-lg-2 control-label">广告主名称</label>
                        <div class="col-lg-10"><input type="text" name="name" value="<?php echo $adv['name']; ?>"  placeholder="广告主名称" class="form-control"></div>
                        <input type="hidden" name="adv_id" value="<?php echo $adv['id']; ?>">

                    </div>
                    <div class="form-group"><label class="col-lg-2 control-label">唯一码</label>
                        <div class="col-lg-10"><input type="text" name="code" value="<?php echo $adv['code']; ?>" readonly placeholder="唯一码" class="form-control"></div>
                    </div>
                    <div class="form-group"><label class="col-lg-2 control-label">所属行业</label>
                        <div class="col-lg-10">
                            <select name="trade" class="form-control">
                            <option value="<?php echo $adv['trade'] ?>"><?php echo $adv['trade'] ?></option>
                        </select></div>
                    </div>
                    <div class="form-group"><label class="col-lg-2 control-label">所有者</label>
                        <div class="col-lg-10"><input type="text" value="<?php echo $adv['ower']; ?>" name="ower" placeholder="所有者" class="form-control"></div>
                    </div>
                     <div class="form-group"><label class="col-lg-2 control-label">是否定制</label>
                        <div class="col-lg-10">
                        <select name="is_custom" class="form-control">
                            <option value="1" <?php if ($adv['is_custom']==1): ?>
                                selected
                            <?php endif ?> >是</option>
                            <option value="0" <?php if ($adv['is_custom']==1): ?>
                                selected
                            <?php endif ?> >否</option>
                        </select></div>
                    </div>
                    <div class="form-group"><label class="col-lg-2 control-label">备注</label>
                        <div class="col-lg-10"><input type="text" name="remark" placeholder="备注" class="form-control"></div>
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

