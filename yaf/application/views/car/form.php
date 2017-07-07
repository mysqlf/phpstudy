
<!-- 图片上传 -->
<link rel="stylesheet" href="<?php echo base_url()?>public/webuploader/webuploader.css">
<link rel="stylesheet" href="<?php echo base_url()?>public/webuploader/style.css">

 <div id="page-wrapper" class="gray-bg">
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row wrapper border-bottom white-bg page-heading">
                <div class="col-lg-10">
                    <h2>车系添加</h2>
                </div>
        </div>
        <div class="col-lg-5">
            <div class="ibox float-e-margins">
                <form id="car_form" class="form-horizontal form-data" action="<?php echo site_url('admin/c_car/add_or_update') ?>" method="post">
                        <div class="modal-body">

                            <input type="hidden" id="ad_id" name="ad_id" value="<?php if(isset($_GET['ad_id'])) echo $_GET['ad_id']; ?>">
                            <input type="hidden" id="c_id" name="c_id" value="<?php if(isset($_GET['c_id'])) echo $_GET['c_id']; ?>">

                            <div class="form-group">
                                <label class="col-sm-2 control-label"> 所属广告主 </label>
                                <div class="col-sm-6">
                                    <input type="hidden" id="ad_id" name="ad_id" value="<?=$advertiser['id']?>">
                                    <input type="text" class="form-control" placeholder="<?=$advertiser['name']?>"  readonly />
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label"> 车系名称 </label>
                                <div class="col-sm-6">
                                    <input type="text" id="name" name="name" class="form-control" placeholder="请输入车系名称" value="<?php if(isset($car['name'])) echo $car['name']; ?>" />
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label"> 车系代码 </label>
                                <div class="col-sm-6">
                                    <input type="text" id="car_code" name="car_code" class="form-control" placeholder="请输入车系代码" value="<?php if(isset($car['car_code'])) echo $car['car_code']; ?>" />
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label"> 状态 </label>
                                <div class="col-sm-6">
                                    <select id="is_use" name="is_use" class="form-control">
                                        <option value="0" <?php if(isset($car['is_use']) && $car['is_use'] == 0) echo "selected" ?>> 启用 </option>
                                        <option value="1" <?php if(isset($car['is_use']) && $car['is_use'] == 1) echo "selected" ?>> 禁用 </option>
                                    </select>
                                </div>
                            </div>

                            <?php if(isset($car['car_img_thumb'])): ?>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label"> 原车系图片 </label>
                                    <div class="col-sm-6">
                                        <img src="<?php echo base_url() . 'uploads/car_img/' . $car['car_img_thumb'] ?>" alt="">  
                                    </div>
                                </div>
                            <?php endif; ?>

                            <div class="form-group">
                                <label class="col-sm-2 control-label"> 
                                    <?php 
                                        if(isset($car['car_img_thumb'])) 
                                            echo '修改车系图片'; 
                                        else 
                                            echo '车系图片';
                                    ?> 
                                </label>
                                <div class="col-sm-6">  
                                    <div id="container">
                                        <!--头部，相册选择和格式选择-->
                                        <input type="hidden" id="car_img" name="car_img" value="<?php if(isset($car['car_img'])) echo $car['car_img']; ?>">
                                        <input type="hidden" id="car_img_thumb" name="car_img_thumb" value="<?php if(isset($car['car_img_thumb'])) echo $car['car_img_thumb']; ?>">
                                        <div id="uploader">
                                            <div class="queueList">
                                                <div id="dndArea" class="placeholder">
                                                    <div id="filePicker"></div>
                                                    <p>或将照片拖到这里(大小不能超过200k)</p>
                                                </div>
                                            </div>
                                            <div class="statusBar" style="display:none;">
                                                <div class="progress">
                                                    <span class="text">0%</span>
                                                    <span class="percentage"></span>
                                                </div><div class="info"></div>
                                                <div class="btns">
                                                    <div id="filePicker2"></div><div class="uploadBtn">开始上传</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>                                 
                                </div>
                            </div>

                            <div class="col-md-offset-5 col-md-9">
                                <button type="button" class="btn btn-success btn-longer submit-btn"><i class="fa fa-cloud-upload"></i>&nbsp;提&nbsp;交</button>&nbsp;&nbsp;&nbsp;
                                <button type="button" class="btn btn-default back-btn" data-dismiss="modal">返&nbsp;回</button>
                            </div>
                        </div>
                    </form>
                    
            </div>
        </div>
    </div>
</div> 




<script src="<?php echo base_url()?>public/js/jquery-2.1.1.js"></script>

<!-- 图片上传 -->
<script src="<?php echo base_url()?>public/webuploader/webuploader.js"></script>
<script src="<?php echo base_url()?>public/webuploader/upload.js"></script>


<script>
    var upload_url = '<?php echo site_url("c_car/upload_car_img") ?>'; 
</script>

<script>
	$(document).ready(function(){

        $('.submit-btn').click(function(){
            $('#car_form').submit();
        })

        $('.back-btn').click(function(){
            window.history.back();
        })
       
	})
</script>

</body>
</html>
