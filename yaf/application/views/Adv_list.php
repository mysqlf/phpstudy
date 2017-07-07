<div id="page-wrapper" class="gray-bg">
    <div class="wrapper wrapper-content animated fadeInRight ecommerce">
        <div class="row wrapper border-bottom white-bg page-heading">
                <div class="col-lg-10">
                    <h2>广告主列表</h2>
                </div>
        </div>
       <div class="row">
                <div class="col-lg-12">
                    <div class="ibox">
                        <div class="ibox-content">

                            <table class="footable table table-stripped toggle-arrow-tiny" data-page-size="15">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>名称</th>
                                    <th>编码</th>
                                    <th>所有者</th>
                                    <th>行业</th>
                                    <th>说明</th>
                                    <th>操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach($result as  $col):?>
                                    <tr>
                                        <td><?php echo $col['id'] ?></td>
                                        <td><?php echo $col['name'] ?></td>
                                        <td><?php echo $col['code'] ?></td>
                                        <td><?php echo $col['ower'] ?></td>
                                        <td><?php echo $col['trade'] ?></td>
                                        <td><?php echo $col['remark'] ?></td>
                                        <td>
                                            <a class="btn-white btn btn-xs" href="<?php echo site_url('C_Advertisers/Adv_edit/').$col['id']?>">修改信息</a>
                                            <a class="btn-white btn btn-xs" href="<?php echo site_url('C_Website/Web_advlist/').$col['id'];?>">推广站点管理</a>
                                            <a class="btn-white btn btn-xs" href="<?php echo site_url('C_car/index/').$col['id']?>">车系管理</a>
                                            <a class="btn-white btn btn-xs" href="<?php echo site_url('C_dealer/index/').$col['id']?>">经销商管理</a>
                                        </td>
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
