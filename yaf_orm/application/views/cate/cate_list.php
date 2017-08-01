<link href="<?php echo DOMAIN_NAME?>/res/style.css" rel="stylesheet">
<script src="<?php echo DOMAIN_NAME?>/res/js/jquery.min.js"></script>
 <div class="list">
    <ul class="list-unstyled" style="padding:10px">
        
        <?php foreach($list as $v){ ?>
        <li class="menu" id="api_<?php echo md5($v['aid']);?>" >
            <a href="<?php echo site_url('api/api_list?cate=').$v['aid']; ?>" id="<?php echo 'menu_'.md5($v['aid'])?>">
                <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                <?php echo $v['cname'] ?>
            </a>
        </li>
        <?php } ?> 
    </ul>
</div>

<script src="<?php echo DOMAIN_NAME?>/res/jquery.cookie.js"></script>
<script src="<?php echo DOMAIN_NAME?>/res/bootstrap-3.3.4-dist/js/bootstrap.min.js"></script>
