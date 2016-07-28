/**
 * 
 * @authors Your Name (you@example.org)
 * @date    2015-05-15 09:38:01
 * @version $Id$
 */

define(function (require,exports,modules){
	var $=require('../lib/jquery');
	function Loading(){
		this.html='<div id="loading"><img src="/Assets/Home/images/loading2.gif"></div>';
	}

	Loading.prototype={
		showLoading:function(){
            $('body').append(this.html);
		},
		removeLoading:function(){
            $('#loading') && $('#loading').remove();
		}
	}
	modules.exports = new Loading();
})
