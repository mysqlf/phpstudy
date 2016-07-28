/**
 * 
 * @authors Your Name (you@example.org)
 * @date    2015-05-17 14:55:23
 * @version $Id$
 */
define(function(require, exports, modules) {
    var $ = require("jquery");
    $('.password_hidden').on('click', function() {
        var that = $(this);
        if (that.children().hasClass('icon-hidden')) {
            that.children().removeClass('icon-hidden').addClass('icon-see')
            $('#password').attr('type', 'text');
        } else {
            that.children().removeClass('icon-see').addClass('icon-hidden');
            $('#password').attr('type', 'password');
        }
    });
});
