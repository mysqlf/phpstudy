/**
 *
 * @authors Your Name (you@example.org)
 * @date    2015-05-26 13:58:00
 * @version $Id$
 */
define(function (require,exports,module){
    var $=require('../lib/jquery');
    var scrollRefresh=require('../plug/refresh');
    var reajax=require('../plug/ajax');
    var ret=1;
    var pageNum=0;
    var sendData={
        pageNum:pageNum,
        ajax:'ajax'
    }
    var header_url=$('header').attr('data-url')||'';

    new reajax(header_url,"post",sendData,'json',function(data){
        readData(data);
    });

    new scrollRefresh(ret,header_url,pageNum,readData,function(){
        $('#friendnearby ul').append('<p class="no_more">没有更多了</p>');
    });
    var $=require('../lib/jquery');
      //后退功能
  $('.icon-return').on('click',function(){
        window.history.back(-1);
  });

    function readData(data){
        var temp="";
        $(data.result).each(function(index,item){
            var cnttemp;
            item.cnt ? cnttemp='<i class="remind">'+item.cnt+'</i>': cnttemp='';
            if(item.gender==1){
                var img_url=item.head_img_url||'Assets/Home/images/male_defult.jpg';
                var icon_class='icon-male';
            }
            else{
                var img_url=item.head_img_url||'Assets/Home/images/female_defult.jpg';
                var icon_class='icon-female';
            }
            temp+='<li ><div class="relative_box"><img src="'+img_url+'" alt="">'+cnttemp+''
            +'</div><a href="http://socket.hr5156.com/Chat-mess_detail-fuid-'+item.tuser_id+'-tuid-'+item.fuser_id+'.html" class="fd_list">'
            +'<p class="person_user_name">'+item.user_name+'<i class="'+icon_class+'"></i><span class="fd_distance fr">'+item.send_date+'</span></p>'
            +'<p class="fd_adress">'+item.content+'</p></a></li>';
        });
        $('#friendrecord ul').append(temp);
    }

});
