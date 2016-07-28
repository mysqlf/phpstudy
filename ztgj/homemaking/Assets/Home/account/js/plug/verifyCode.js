/**
 * 
 * @authors Your Name (you@example.org)
 * @date    2015-05-26 10:40:44
 * @version $Id$
 */
define(function(require, exports, module) {
    var $ = require('jquery');
    var layer = require('layer');
    var VerifyCode = {
        el: $('.get_verify'),
        telEl: $('#tel'),
        captchaEl: $('#captcha'),
        count: null,
        timer: parseInt($('#time').val()) || 60,
        init: function() {
            var self = this;
            if (self.timer != 60) {
                self.beginCode();
            }
        },
        // 禁用计时器
        disabledBtn: function() {
            this.el.addClass('disabled');
            return this;
        },
        // 设置计时器
        enableBtn: function() {
            var self = this;

            self.el.removeClass('disabled');

            return this;
        },
        // 启用计时器
        beginCode: function() {
            var self = this;

            self.disabledBtn();

            self.count = setInterval(function() {
                self.countSecond(self.timer)
            }, 1000);

            return this;
        },
        // 获取验证码
        getCode: function() {
            var self = this;
            var sendDate = {
                phoneVal: self.telEl.val(),
                verifyCode: self.captchaEl.val()
            }

            self.disabledBtn()

            $.post('Account-getcode', sendDate, function(data) {
                if (data === 1) {
                    layer.open({
                        content: '您已经超过5次请求获取验证，请24小时后再尝试请求',
                        time: 3
                    });
                    self.enableBtn();
                    self.el.trigger('codeFail', data);
                } else if(data === false || data === 'false' ){
                    self.enableBtn();
                    layer.open({
                        content: '验证码失效，请重新获取验证码',
                        time: 3
                    });
                } else {
                    self.beginCode();
                    self.el.trigger('codeSuccess', data);
                }
            }, 'json')
            .fail(function(){
                self.beginCode();
            })

            return this;
        },
        countSecond: function(timers) {
            var self = this;

            if (timers != 0) {
                self.timer = timers - 1;
                self.el.text(self.timer + 's重新获取')
                self.disabledBtn();

            } else {
                clearInterval(self.count);
                self.timer = 60;
                self.enableBtn();
                self.el.text('重新获取');

            }

            return this;
        }
    }


    module.exports = VerifyCode;

});
