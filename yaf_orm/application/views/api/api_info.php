<link href="<?php echo DOMAIN_NAME?>/res/style.css" rel="stylesheet">
<script src="<?php echo DOMAIN_NAME?>/res/js/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo DOMAIN_NAME?>/res/layer.js"></script>

    <!--接口详细列表start-->
    <?php if(count($list)){ ?>
        <?php foreach($list as $v){ ?>
        <div class="info_api" style="border:1px solid #ddd;margin-bottom:20px;" id="info_api_<?php echo md5($v['id'])?>">
            <div style="background:#f5f5f5;padding:20px;position:relative">
                <div class="textshadow" style="position: absolute;right:0;top:4px;right:8px;">
                    最后修改者: 
                    <?php if(1){?>
                    <button class="btn btn-danger btn-xs " onclick="deleteApi(<?php echo $v['id']?>,'<?php echo md5($v['id'])?>')">delete</button>&nbsp;
                    <!-- <?php // echo U(array('act'=>'api','op'=>'edit','id'=>$v['id'],'tag'=>$_GET['tag']))?> -->
                    <button class="btn btn-info btn-xs " onclick="editApi('')">edit</button>
                    <button class="btn btn-primary btn-xs " onclick="copyApi(<?php echo $v['id']?>)">copy</button>
                    <?php } ?>
                </div>
                <h4 class="textshadow"><?php echo $v['name']?></h4>
                <p>
                    <b>编号&nbsp;&nbsp;:&nbsp;&nbsp;<span style="color:red"><?php echo $v['num']?></span></b>
                </p>
                <div>
                    <?php
                        $color = 'green';
                        if($v['type']=='POST'){
                            $color = 'red';
                        }
                    ?>
                    <kbd style="color:<?php echo $color?>"><?php echo $v['type']?></kbd> - <kbd><?php echo $v['url']?></kbd>
                </div>
            </div>
            <?php if(!empty($v['des'])){ ?>
            <div class="info">
                <?php echo $v['des']?>
            </div>
            <?php } ?>
            <div style="background:#ffffff;padding:20px;">
                <h5 class="textshadow" >请求参数</h5>
                <table class="table">
                    <thead>
                    <tr>
                        <th class="col-md-3">参数名</th>
                        <th class="col-md-2">参数类型</th>
                        <th class="col-md-2">必传</th>
                        <th class="col-md-2">缺省值</th>
                        <th class="col-md-5">描述</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                        $parameter = unserialize($v['parameter']);
                        
                        $pnum = count($parameter['name']);
                    ?>
                    <?php for( $i=0; $i<$pnum; $i++ ) {?>
                    <tr>
                        <td><?php echo $parameter['name'][$i]?></td>
                        <td><?php echo $parameter['paramType'][$i]?></td>
                        <td><?php if($parameter['type'][$i]=='Y'){echo '<span style="color:red">Y<span>';}else{echo '<span style="color:green">N<span>';}?></td>
                        <td><?php echo $parameter['default'][$i]?></td>
                        <td><?php echo $parameter['des'][$i]?></td>
                    </tr>
                    <?php } ?>

                    </tbody>
                </table>
            </div>
            <?php if(!empty($v['re'])){ ?>
            <div style="background:#ffffff;padding:20px;">
                <h5 class="textshadow" >返回值</h5>
                <pre><?php echo $v['re']?></pre>
            </div>
            <?php } ?>
            <?php if(!empty($v['memo'])){ ?>
            <div style="background:#ffffff;padding:20px;">
                <h5 class="textshadow">备注</h5>
                <pre style="background:honeydew"><?php echo $v['memo']?></pre>
            </div>
            <?php } ?>
        </div>
        <!--接口详细列表end-->
        <!--接口详情返回顶部按钮start-->
        <div id="gotop" onclick="goTop()" style="z-index:999999;font-size:18px;display:none;color:#e6e6e6;cursor:pointer;width:42px;height:42px;border:#ddd 1px solid;line-height:42px;text-align:center;background:rgba(91,192,222, 0.8);position:fixed;right:20px;bottom:200px;border-radius:50%;box-shadow: 0px 0px 0px 1px #cccccc;">
            T
        </div>
        <!--接口详情返回顶部按钮end-->
        <?php } ?>
    <?php } else{ ?>
        <div style="font-size:16px;">
            <span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span> 此分类下还没有任何接口
        </div>
    <?php }?>
    <script>
        //删除某个接口
        var $url = '<?php // echo U(array('act'=>'ajax','op'=>'apiDelete'))?>';
        function deleteApi(apiId,divId){
            if(confirm('是否确认删除此接口?')){
                $.post($url,{id:apiId},function(data){
                    if(data == '1'){
                        $('#api_'+divId).remove();//删除左侧菜单
                        $('#info_api_'+divId).remove();//删除接口详情
                    }
                })
            }
        }
        //编辑某个接口
        function editApi(gourl){
            window.location.href=gourl;
        }
        //复制某个api
        function copyApi( apiId ) {
          var askName = layer.prompt({
            title: '输入新的api名称',
            formType: 0 //prompt风格，支持0-2
          }, function(pass){
            location.href = "index.php?act=api&op=copy&id="+apiId+"&name="+pass;
          });
        }

        //返回顶部
        function goTop(){
            $('#mainwindow').animate(
                { scrollTop: '0px' }, 200
            );
        }

        //检测滚动条,显示返回顶部按钮
        document.getElementById('mainwindow').onscroll = function () {
            if(document.getElementById('mainwindow').scrollTop > 100){
                document.getElementById('gotop').style.display='block';
            }else{
                document.getElementById('gotop').style.display='none';
            }
        };
    </script>

<script src="<?php echo DOMAIN_NAME?>/res/jquery.cookie.js"></script>

