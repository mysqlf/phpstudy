<div id="page-wrapper" class="gray-bg">
    <div class="wrapper wrapper-content animated fadeInRight ecommerce">
        <div class="row wrapper border-bottom white-bg page-heading">
                <div class="col-lg-10">
                    <h2>字段列表</h2>
                </div>
        </div>
       <div class="row">
                <div class="col-lg-12">
                    <div class="ibox">
                        <div class="ibox-content">

                            <table class="footable table table-stripped toggle-arrow-tiny" data-page-size="15">
                                <thead>
                                <tr>
                                    <th>表名</th>
                                    <th>记录数</th>
                                    <th>数据大小</th>
                                    <th>注释</th>
                                    <th>所属模块</th>
                                    <th>操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach($result as  $item):?>
                                    <tr>
                                        <td><?php echo $item['table_name'] ?></td>
                                        <td></td>
                                        <td></td>
                                        <td><?php echo $item['remark'] ?></td>
                                        <td><?php echo $item['name'] ?></td>
                                        <td><a class="btn-white btn btn-xs" href="<?php echo site_url("C_Colum/Colum_add/").$item['id'] ?>">字段管理</a></td>
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

