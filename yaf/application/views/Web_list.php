<div id="page-wrapper" class="gray-bg">
    <div class="wrapper wrapper-content animated fadeInRight ecommerce">
        <div class="row wrapper border-bottom white-bg page-heading">
                <div class="col-lg-10">
                    <h2>站点列表 <?php if ($adv_id){ echo "<a href=".site_url('C_Website/Web_add_page/').$adv_id.">添加站点</a>";}?> </h2>
                </div>
        </div>
       <div class="row">
                <div class="col-lg-12">
                    <div class="ibox">
                        <div class="ibox-content">
                            <table class="footable table table-stripped toggle-arrow-tiny" data-page-size="15">
                                <thead>
                                <tr>
                                    <th>站点名称</th>
                                    <th>站点编码</th>
                                    <th>所属广告主</th>
                                    <th>开始时间</th>
                                    <th>结束时间</th>
                                    <th>状态</th>
                                    <th>功能模块</th>
                                    <th>操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php if ($result): ?>
                                    <?php foreach($result as $web): ?>
                                    <tr>
                                        <td><?php echo $web['web_name'] ?></td>
                                        <td><?php echo $web['web_code'] ?></td>
                                        <td><?php echo $web['name'] ?></td>
                                        <td><?php echo $web['start_date'] ?></td>
                                        <td><?php echo $web['end_date'] ?></td>
                                        <td><?php echo $web['is_use']?'启用':'停用'; ?></td>
                                        <td>
                                            <?php foreach ($web['wm'] as $value):?>
                                                <a class="btn-white btn btn-xs" href="<?php echo site_url('C_Website/Webmodel_write').'/'.$web['id'].'/'.$value['id'] ?>"><?php echo $value['name'] ?></a>
                                            <?php endforeach; ?>
                                        </td>
                                        <td>
                                            <a class="btn-white btn btn-xs" href="<?php echo site_url('C_Website/Web_edit_page/').$web['id'].'/'.$web['ad_id'] ?>">查看</a>
                                        </td>
                                    </tr>  
                                <?php endforeach; ?>
                                <?php endif ?>
                                
                                </tbody>
                                <tfoot>
                                <tr>
                                  <?php echo $this->pagination->create_links(); ?>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
    </div>
</div>


