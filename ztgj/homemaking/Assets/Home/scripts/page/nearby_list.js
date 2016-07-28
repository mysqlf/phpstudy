/**
 * 
 * @authors Your Name (you@example.org)
 * @date    2015-05-26 11:00:44
 * @version $Id$
 */
define(function (require,exports,module){
    var $=require('../lib/jquery');
    var popup=require('../plug/popup');
    var Tab=require('../plug/tab');
    var reajax=require('../plug/ajax');
    var scrollRefresh=require('../plug/refresh');
    var jobclass_box=$('.near_jobclass_box');
    var jobclassBtn=$('#jobclass_btn a');
    var oldType=jobclassBtn.attr('data-id');
    var closeArr=['#mask','.location_list','.tab_click'];
    var mile=4;
    var pageNum=0;
    var ret=1;
    var type=0;

    new Tab('tab_click','tab_box');

   
    new popup('classes_btn',closeArr,function(){jobclass_box.show()},function(){jobclass_box.hide()});

$('.location_list').on('click',function(){
        var  oldtype=jobclassBtn.attr('data-id');
        $($(this).closest('ul').attr('data-target')).children('a').text($(this).text()).attr('data-id',$(this).attr('data-id')).append('<i class="icon-angle"></i>');
        //防止重复选择后的锁
        if(oldtype==$(this).attr('data-id')){
           $('#mask,#hidden_box').hide();
        }
        else{
         $(document).off('scroll');
         scroll=null;
         oldType=$(this).attr('data-id');
         jobclassBtn.attr('data-id',oldType);
         mile=$('.hidden_box_check').attr('data-mile');
         var sendData={
             type:oldType,
            mile:mile
        }

        var scroll=new scrollRefresh(ret,1,'Near-index',sendData,readData,function(){
            $('#container').append('<p class="no_more">没有更多了</p>');
        });    

        $('.tab_box').empty();
        new reajax('Near-index','post',sendData,'json',function(data){
            if(!data.ret){
                  $("#container").html('<div id="error" style="width:100%; background-color:#fff; height:100%; text-align:center;"><img src="/Assets/Home/images/error.png"></div>');
                  $(document).off('scroll');
            }
          else{
            readData(data);        
          }
        });
    }
});


var sendData={
     mile:4,
     type:0
}

 var scroll=new scrollRefresh(ret,1,'Near-index',sendData,readData,function(){
        $('#container').append('<p class="no_more">没有更多了</p>');
 });    


  function readData(data){
    if(data.ret){
        var temp='';
                $(data.items).each(function(index,item){
                  //var payment
                  //item.salaryCn=="面议" ? payment = "面议" : payment = item.salaryCn+'/月';
                    temp+='<li class="job_list" data-id="'+item.posNo+'">'
                             +'<a href="Near-details-id-'+item.posNo+'">'
                                 +'<div class="list_left">'
                                   +'<p class="jobname">'+item.posName+'</p>'
                                   +'<p class="companyname">'+item.comName+'</p>'
                                   +'<p class="distance"><i class="icon-distance"></i>'+item.distance+'</p>'
                                +'</div>'
                                 +'<div class="list_right">'
                                   +'<p class="payment">'+item.salaryCn+'</p>'
                                   +'<p class="date_list">'+item.refDate+'</p>'
                                 +'</div>'
                               +'</li>'
                   });
                  $('#nearby_list ul').append(temp);
          }
        else{
              ret=0;
              $('#container').append('<p style="text-align: center;padding: 15px 0;color: #999;">没有更多信息了</p>');
            }   
  }

  


    

});
