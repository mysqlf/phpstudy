<div id="page-wrapper" class="gray-bg">
    <div class="wrapper wrapper-content animated fadeInRight ecommerce">
        <div class="row wrapper border-bottom white-bg page-heading">
                <div class="col-lg-10">
                    <h2>车系列表</h2>
                </div>
        </div>
       <div class="row">
				<div class="col-xs-12">
					<div class="box">
						<div class="box-header">
							<h3 class="box-title">车系管理</h3>&nbsp;&nbsp;&nbsp;&nbsp;
							<!-- ?ad_id=<?php echo $_GET['ad_id']; ?> -->
							<a href="<?php echo site_url('c_car/add/').$ad_id;?>" type="button" class="btn btn-primary" rel='0'>
								<i class="fa fa-plus"></i>&nbsp;添加车系
							</a>					

						</div>

						<div class="box-body">
							<form id="form" method="post" action="">
								<table class="table table-striped table-bordered table-hover">
									<thead>
										<tr>
											<th><input class="check-all" type="checkbox" value=""></th>
											<th>ID</th>
				                            <th>车系名称</th>
				                            <th>车系代码</th>
				                            <th>图片</th>
				                            <th>所属广告主</th>
				                            <th>状态</th>
				                            <th>操作</th>
										</tr>
									</thead>

									<tbody>
										<?php foreach ($list as $key => $val): ?>
											<tr>
												<td class="center"><input class="ids" type="checkbox" name="ids[]"
                                                          value="<{$val['id']}>"></td>
												<td><?php echo $val['id'] ?></td>
                            					<td><?php echo $val['name'] ?></td>
                            					<td><?php echo $val['car_code'] ?></td>
                            					<td><img src="<?php echo base_url() . 'uploads/car_img/' . $val['car_img_thumb'] ?>" alt=""></td>
                            					<td><!-- <?php $val['adver_name'] ?> --></td>
                            					<td>
													<a href="javascript:;" class="disable" title="点击禁用" rel="<?= $val['id'] ?>" ><?php if($val['is_use'] == 0): ?>启用<?php endif; ?></a>
                            						
                            						<a href="javascript:;" class="enable" title="点击启用" rel="<?= $val['id'] ?>" ><?php if($val['is_use'] == 1): ?>禁用<?php endif; ?></a>
                            					</td>

                            					<td class="center">
                            					<!-- ?ad_id=<?php echo $_GET['ad_id']; ?>&c_id=<?= $val['id'] ?> -->
                            						<a href="<?php echo site_url('c_car/edit/').$ad_id.'/'.$val['id'];?>" type="button" class="btn btn-info btn-sm">
														<i class="fa fa-pencil-square-o"></i>&nbsp;修改
													</a>
                            					</td>
											</tr>
										<?php endforeach; ?>
									</tbody>
								</table>
								<div style="text-align:center;"><?= $links ?></div>
							</form>
						</div>
					</div>
				</div>
			</div>
    </div>
</div>
