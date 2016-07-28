/**
 * 
 * @authors Your Name (you@example.org)
 * @date    2015-05-31 11:34:21
 * @version $Id$
 */
define(function (require,exports,modules){
	  var $=require('../lib/jquery');
	  var reajax=require('../plug/ajax');
    var scrollRefresh=require('../plug/refresh');
    var pageNum;
    var searchBox=$('.search_box');
    var placeholder=$('#placeholder');
    var searchContainer=$('#search_container');
    var ret=1;

      //后退功能
  $('.icon-return').on('click',function(){
        window.history.back(-1);
  });
     
    
    //存储热门搜索
     var html=searchContainer.html();


    searchBox.on('focus',function(){
       $(this).attr('placeholder','').css('text-align','left');
    });

    searchBox.on('blur',function(){
       if(!($(this).val()))
       {
        $(this).attr('placeholder','地区/岗位名').css('text-align','center');
        searchContainer.empty().html(html);
       }
    });
    
   searchContainer.on('click','.hot_search',function(){
        searchBox.css('text-align','left');
        var page=1;
         var sendData={
                pageNum:page,
                keyword:$(this).text()
            }
        searchBox.val($(this).text());
        placeholder.css('display','none');
         searchContainer.empty().html('<section id="nearby_list" class="nearby_list"><ul></ul></section>');
           new reajax("Near-search","post",sendData,'json',function(data){
               readData(data);
           });
            $(document).off('scroll');
           new scrollRefresh(ret,1,'Near-search',sendData,readData,function(){
            $('#search_container').append('<li class="no_more">没有更多了</li>');
          }); 
    });
    
    $('.search_btn').on('click',function(){
      if(searchBox.val()){
        var page=1;
             var sendData={
                pageNum:page,
                keyword:searchBox.val()
            }
         searchContainer.empty().html('<section id="nearby_list" class="nearby_list"><ul></ul></section>');
           new reajax("Near-search","post",sendData,'json',function(data){
               readData(data);
           });
            $(document).off('scroll');
          new scrollRefresh(ret,1,'Near-search',sendData,readData,function(){
            $('#search_container ul').append('<li class="no_more">没有更多了</li>');
          });    
      }
    });

  

    function readData(data){
        if(data.ret){
                var temp='';
                $(data.items).each(function(index,item){
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
                        $('#nearby_list').append('<div id="error" style="width:100%; background-color:#fff; height:100%; text-align:center;"><img src="/Assets/Home/images/error.png"></div>');
                        $(document).off('scroll');
                    }   
          }

});
