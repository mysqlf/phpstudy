	seajs.use(['/Assets/Home/js/lib/jquery-2.1.4.min', '/Assets/Home/js/page/imagelightboxInit.js', '/Assets/Home/js/plug/layer.m/layer.m', '/Assets/Home/js/plug/scrollListener/jquery.scrollListener', '/Assets/Home/js/plug/lazyload/lazyload', '/Assets/Home/js/plug/scrollAjax/scrollAjax', '/Assets/Home/js/plug/hammer/hammer.min'], function($, imagelightbox) {

	    $(function() {

	        $('img.lazy').lazyload();
	        imagelightbox.init();
	        var panup = new Hammer($('body')[0], {
	            threshold: 0
	        });
	        panup.on('panup', function() {
	            $(window).scrollListener({

	                scrollEnd: function(top, maxH, el) {
	                    $('.fuwu-item').scrollAjax({
	                        ajax: {
	                            url: 'Homemaking-requirementDetail',
	                            data: {
	                                type: $('#postData').data('type'),
	                                id: $('#postData').data('id')
	                            }
	                        },
	                        html: function(data) {
	                            var html = imgHtml = '';

	                            for (var i = 0, len = data.length; i < len; i++) {

	                                //图片
	                                var index = $('.imagelightbox').length || 0; //imagelightbox插件
	                                if (data[i].UploadFile) {
	                                    for (var j = 0, len2 = data[i].UploadFile.length; j < len2; j++) {

	                                        imgHtml += '<li class="col-4"><a href="' + data[i].UploadFile[j][0] + '" data-imagelightbox="' + (index + j) + '" target="_blank">' + '<img class="lazy img-responsive" src="Assets/Home/images/lazy.gif" data-original="' + data[i].UploadFile[j][1] + '" alt=""></a></li>'
	                                    }
	                                }

	                                html += '<li class="panel panel-border"><div class="panel-body clearfix">' + '<div class="col-4 text-overflow c-dark">雇主：' + data[i].fldName + '</div>' + '<div class="col-8 text-overflow text-right c-dark">服务时间：<span class="c-gray-light">' + data[i].fldBeginDate + '~' + data[i].fldEndDate + '</span></div>' + '<h4 class="sub-title clear">评语：</h4>' + '<p>' + data[i].fldAssessment + '</p>' + '<ul class="clearfix imagelightbox">' + imgHtml + '</ul></div></li>';
	                                imgHtml = '';

	                            }
	                            return html;
	                        },
	                        success: function() {

	                            $('img.lazy').lazyload();
	                            imagelightbox.init();

	                        }
	                    });
	                }
	            });
	        });

	    });
	})
