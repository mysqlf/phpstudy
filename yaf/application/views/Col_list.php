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
                                    <th>字段名</th>
                                    <th>长度</th>
                                    <th>类型</th>
                                    <th>注释</th>
                                    <th>所属表</th>
                                    <th>操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($result as  $col):?>
                                        <tr>
                                            <td><?php echo $col['colum_name'] ?></td>
                                            <td><?php echo $col['colum_len'] ?></td>
                                            <td><?php echo $col['colum_type'] ?></td>
                                            <td><?php echo $col['remark'] ?></td>
                                            <td><?php echo $col['table_name'] ?></td>
                                            <td><a href="<?php echo site_url('C_Colum/Col_edit/').$col['t_id'].'/'.$col['id']?>">修改</a></td>
                                        </tr>
                                <?php endforeach; ?>
                                </tbody>
                                <tfoot>
                                <tr>
                                    <td colspan="6">
                                        <ul class="pagination pull-right"><?php echo $this->pagination->create_links(); ?></ul>
                                    </td>
                                </tr>
                                </tfoot>
                            </table>

                        </div>
                    </div>
                </div>
            </div>
    </div>
</div>
