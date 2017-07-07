<div id="page-wrapper" class="gray-bg">
    <div class="wrapper wrapper-content animated fadeInRight ecommerce">
        <div class="row wrapper border-bottom white-bg page-heading">
                <div class="col-lg-10">
                    <h2>模块列表</h2>
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
                                    <th>表</th>
                                    <th>行业</th>
                                    <th>说明</th>
                                    <th>操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($result as $value): ?>
                                    <tr>
                                        <td><?php echo $value['id']?></td>
                                        <td><?php echo $value['name']?></td>
                                        <td><?php foreach ($value['table'] as $item):?>
                                            <a class="btn-white btn btn-xs" href="<?php echo site_url('C_Colum/Colum_add/').$item['id']?>"><?php echo $item['table_name']?></a>
                                            <?php endforeach; ?>
                                        </td>
                                        <td><?php echo $value['trade']?></td>
                                        <td><?php echo $value['remark']?></td>
                                        <td>
                                        <a class="btn-white btn btn-xs" href="<?php echo site_url('C_Features/Feat_edit/').$value['id'] ?>">修改</a>
                                        <a class="btn-white btn btn-xs" href="<?php echo site_url('C_Table/tab_add'); ?>">添加表</a></td>
                                    </tr>
                                <?php endforeach;?> 
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

