/**
 * 
 * @authors Your Name (you@example.org)
 * @date    2015-05-14 15:44:12
 * @version $Id$
 */
define(function (require,exports,module){
	var $=require('../lib/jquery');
	var reajax=require('../plug/ajax');
    var page=1;

    new reajax("Near-index","post",'','json',function(data){
            if(data.ret){
                var temp='';
                $(data.items).each(function(index,item){
                    temp+='<li class="job_list" data-id="'+item.posno+'">'
                                 +'<div class="list_left">'
                                   +'<p class="jobname">'+item.posname+'</p>'
                                   +'<p class="companyname">'+item.comname+'</p>'
                                   +'<p class="distance">'+item.distance+'</p>'
                                +'</div>'
                                 +'<div class="list_right">'
                                   +'<p class="payment">'+item.salaryCn+'/月</p>'
                                   +'<p class="date_list">'+item.refDate+'</p>'
                                 +'</div>'
                               +'</li>'
                   });
                  $('#nearby_list ul').append(temp);

            }
            else{
                return false;
            }
         });

     // $.fn.refresh=function(option){
     // 	var Dheight=$(document).height();
     //    var Wheight=$(window).height();
        

     //    $(window).scroll(function(){
     //      var Sheight=$('body').scrollTop();
     //      var Dheight=$(document).height();
     //       if (Dheight<(Sheight+Wheight)){
     //        new reajax("Near-index","post",'','json',function(data){
     //               console.log(data);  
     //         },function(){

     //         })
     //       }
     //    });
     // }
     // $.fn.refresh();
    
 });

// {
//     "ret":1,
//     "msg":"",
//     "items":[{
//         "posno":276231649,
//         "posname":"职位1",
//         "comname":"lwbcom",
//         "salaryCn":"3000-4499",
//         "refDate":"2015-02-27",
//         "distance":"1km",
//         "lat":23.04322,
//         "lng":113.75786}]
//     }
