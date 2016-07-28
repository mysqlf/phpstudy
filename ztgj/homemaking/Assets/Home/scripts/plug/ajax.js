/**
 * 
 * @authors Your Name (you@example.org)
 * @date    2015-05-14 16:00:44
 * @version $Id$
 */
define(function (require,exports,modules){
	var $=require('../lib/jquery');
    var loading=require('./loading'); 
	// 封装ajax请求
function reajax(url, type, data, dataType, success, error ){
    loading.showLoading();

    $.ajax({
        url : url,
        type: type || 'get',
        data: data || {},
        timeout : 300000,
        dataType: 'json',
        success: function(data){
            loading.removeLoading();
            $.isFunction(success) && success(data);
        },
        error: function(data){
            loading.removeLoading();
            $.isFunction(error) && error(data);
        }
    });
}
modules.exports = reajax;
});
