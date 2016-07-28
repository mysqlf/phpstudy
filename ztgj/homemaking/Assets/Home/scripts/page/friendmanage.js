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



    function readData(data){
        var temp="";

        $(data.result).each(function(index,item){
            if(item.gender==1){
                var img_url=item.head_img_url||'Assets/Home/images/male_defult.jpg';
                var icon_class='icon-male';
            }
            else{
                var img_url=item.head_img_url||'Assets/Home/images/female_defult.jpg';
                var icon_class='icon-female';
            }
            temp+='<li data-id="'+item.id+'"><img src="'+img_url+'" alt=""><a href="http://socket.hr5156.com/Chat-mess_detail-fuid-'+item.myself_id+'-tuid-'+item.goodf_id+'" class="fd_list">'
            +'<p class="person_user_name">'+item.user_name+'<i class="'+item.icon_class+'"></i><span class="fd_distance fr">'+item.distance+'m</span></p>'
            +'<p class="fd_adress">'+item.location+'</p></a></li>';
        });
        $('.manage_ul').append(temp);
    }



    //下拉刷新
    new scrollRefresh(ret,header_url,pageNum,readData,function(){
        $('#friendnearby ul').append('<p class="no_more">没有更多了</p>');
    });

    //后退功能
    $('.icon-return').on('click',function(){
        window.history.back(-1);
    });


});
