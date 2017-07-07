<div id="page-wrapper" class="gray-bg">
    <div class="wrapper wrapper-content animated fadeInRight ecommerce">
        <div class="row wrapper border-bottom white-bg page-heading">
                <div class="col-lg-10">
                    <h2>模块功能列表</h2>
                </div>
        </div>
       <div class="row">
                <div class="col-lg-12">
                    <div class="ibox">
                        <div class="ibox-content">

                            <table class="footable table table-stripped toggle-arrow-tiny" data-page-size="15">
                                <thead>
                                <tr>
       
                                    <th>功能模块名称</th>
                                    <th>所属活动站</th>
                                    <th>所属广告主</th>
                                    <th>开始日期</th>
                                    <th>结束日期</th>
                                    <th>状态</th>
                                    <th>操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($result as  $value):?>
                                <tr>

                                    <td><?php echo $value['mname']; ?></td>
                                    <td><?php echo $value['web_name']; ?></td>
                                    <td><?php echo $value['adv_name']; ?></td>
                                    <td><?php echo $value['m_start_date']; ?></td>
                                    <td><?php echo $value['m_end_date']; ?></td>
                                    <td><?php echo $value['is_use']?'启用':'禁用'; ?></td>
                                    <td><a class="btn-white btn btn-xs" href=" <?php echo site_url('C_Website/Webmodel_write/').$value['id'].'/'.$value['m_id'] ?>">接口设置</a><a href="<?php echo site_url('C_Interface/word/').$value['id'].'/'.$value['m_id'] ?>">接口文档</a></td>
                                </tr>
                                <?php endforeach; ?>
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
