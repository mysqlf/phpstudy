<link href="<?php echo DOMAIN_NAME?>/res/style.css" rel="stylesheet">
<script src="<?php echo DOMAIN_NAME?>/res/js/jquery.min.js"></script>
 <div style="border:1px solid #ddd">
        <div style="background:#f5f5f5;padding:20px;position:relative">
            <h4>添加分类</h4>
            <div>
                <form action="<?php echo site_url('cate/cate_add') ?>" method="post">
                    <div class="form-group">
                        <input type="text" class="form-control" name="cname" placeholder="分类名">
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" name="cdesc" placeholder="描述">
                    </div>
                    <button class="btn btn-success" name="op" value="add">Submit</button>
                </form>
            </div>
        </div>
    </div>
<script src="<?php echo DOMAIN_NAME?>/res/jquery.min.js"></script>
<script src="<?php echo DOMAIN_NAME?>/res/jquery.cookie.js"></script>
<script src="<?php echo DOMAIN_NAME?>/res/bootstrap-3.3.4-dist/js/bootstrap.min.js"></script>
