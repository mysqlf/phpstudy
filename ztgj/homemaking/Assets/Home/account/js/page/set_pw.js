/**
 * 
 * @authors Your Name (you@example.org)
 * @date    2015-05-22 17:45:41
 * @version $Id$
 */
define(function(require, exports, modulde) {
    var $ = require('jquery'),
        layer = require('layer');


    var strongRegex = /^(?=.{6,})(((?=.*[A-Z])(?=.*[a-z])(?=.*[0-9]))|((?=.*[A-Z])(?=.*[a-z])(?=.*\W))|((?=.*[A-Z])(?=.*[0-9])(?=.*\W))|((?=.*[a-z])(?=.*[0-9])(?=.*\W))).*$/; //强
    var mediumRegex = /^(?=.{6,})(((?=.*[A-Z])(?=.*[a-z]))|((?=.*[A-Z])(?=.*[0-9]))|((?=.*[a-z])(?=.*[0-9]))|((?=.*[a-z])(?=.*\W))|((?=.*[A-Z])(?=.*\W))|((?=.*[0-9])(?=.*\W))).*$/; //中
    var enoughRegex = /^(?=.{6,}).*/; //弱

    require('validate');
    require('defaultRules');

    var SetPsw = {
        init: function() {
            var self = this;

            $('#password').keyup(function() {
                self.checkPswGrade(this.value);
            });
            self.validate();

        },
        reg: {
            strong: strongRegex,
            medium: mediumRegex,
            enough: enoughRegex
        },
        twigEl: {
            twig: $('.twig'),
            high: $('.high .twig'),
            middle: $('.middle .twig'),
            low: $('.low .twig')
        },
        checkPswGrade: function(val) {
            this.twigEl.twig.removeClass('level_active');

            if (this.reg.strong.test(val)) {
                this.twigEl.high.addClass('level_active');

            } else if (this.reg.medium.test(val)) {
                this.twigEl.middle.addClass('level_active');

            } else if (this.reg.enough.test(val)) {
                this.twigEl.low.addClass('level_active');

            }
        },
        validate: function() {
            var formValidate = $("#set-psw-form").validate({
                submitHandler: function(form) {
                    var $form = $(form);
                    var formData = $form.serializeArray();
                    var flag = $form.data('flag');
                    var $submitBtn = $('[type="submit"]', $form);

                    if (flag) return;
                    $form.data('flag', true);
                    $submitBtn.val('提交中...');

                    $.post('FindPassword-setpassword', formData, function(data) {
                            var url = data.data.url;
                            if (data.status === 0) {
                                layer.open({
                                    content: '修改成功'
                                });
                                if (url) {
                                    location.href = url;
                                }
                            } else {
                                layer.open({
                                    content: '修改失败'
                                })
                                $form.data('flag', false);
                            }
                        },'json')
                        .fail(function() {
                            layer.open({
                                content: '服务器繁忙',
                                time: 3
                            });
                            $form.data('flag', false);
                        })
                        .complete(function() {
                            $submitBtn.val('确定');
                        })
                }
            });

            return formValidate;
        }
    }
    SetPsw.init()
    modulde.exports = SetPsw;


});
