define(function(require,exports,moudle){
   var $ = jQuery = require('../../lib/jquery-2.1.4.min');

       $.fn.scrollAjax = function(options){
           
           var defaults =  {
    		ajax : {
	    			url : '',
	    			type : 'json',
	    			data : {},
	    			timeout : 30000
    		       },//ajax参数
    		html : $.noop,//标签塞入内容
    		success : $.noop,//成功回调
    		pageNum : 10 ,//每页内容的个数
    		noMoreTxt: '已经没有更多了'

      	  },
    	opts = $.extend(true,{},defaults,options);

    	return this.each(function(){
    		
    		var $listBox = $(this),
    		    $list = $('>li',$listBox),
		        flag = $listBox.data('flag'),
		        page = $listBox.data('page') || 1;

		        opts.ajax.data.page = ++page;

		        if(!flag && $list.length >= opts.pageNum){

		           var $load = $('<li class="data-loading"><img class="img-responsive" src="Assets/Home/images/loading.gif">数据加载中...</li>');
		               $load.appendTo($listBox);
		               $listBox.data('flag',true);              
	                   $.ajax({
	                      type : 'post',
	                      url : opts.ajax.url,
	                      dataType : opts.ajax.type,
	                      data:opts.ajax.data,
	                      timeout : opts.ajax.timeout,
	                      error : function(){
	                        alert('服务器繁忙');
	                        $listBox.data('flag',false); 
	                        $load.remove();
	                      },
	                      success : function(json){
	                        var data = json,
	                            html = ''
	                            len = data.length;

	                            $load.remove();
	                            $listBox.data({
	                                    'flag' : false,
	                                    'page' : opts.ajax.data.page,
	                                  })
	                             
	                             html = opts.html(data);

	                             if(len < opts.pageNum){
	                             	html += '<li class="text-center no-data">'+ opts.noMoreTxt +'</li>';
	                             	$listBox.data('flag',true); 
	                             } 
	                             
	                             $listBox.append(html);
	                             opts.success();

	                      }
	                   })
		        }
    	});
    	}

})