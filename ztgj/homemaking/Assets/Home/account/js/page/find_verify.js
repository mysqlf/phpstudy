/**
 * 
 * @authors Your Name (you@example.org)
 * @date    2015-05-26 09:15:40
 * @version $Id$
 */
define(function(require, exports, module) {
    var $ = require('jquery'),
        verifyCode = require('verifyCode'),
        layer = require('layer');

    require('validate')
    require('defaultRules');

    var FindVerify = {
        init: function() {
            var self = this;
            var formValidate = self.validate();

            $('.get_verify').on('click', function() {

                if (formValidate.element('#tel') && formValidate.element('#captcha')) {
                    verifyCode.getCode();
                }
            });
            $('input').focus(function(event) {
                $('p.error').hide();
            });

            verifyCode.init();

            $('#sendEmail').click(function() {
                self.sendEmail(this);
            });
        },
        sendEmail: function(obj) {
            var $this = $(obj);

            var flag = $this.data('flag');

            if (flag) return;
            $this.data('flag', true);
            $this.val('提交中...');

            $.post('/FindPassword-emailFindPwd', {}, function(data) {
                    if(data.status === 0){
                        $('#sendEmailSucess').show();
                        $('#sendEmailBox').hide();

                        var $emailsign = $('#emailsign');
                        $emailsign.attr('href', data.data.emailurl);

                        if(data.data.emailsign != 1){
                            $emailsign.html('请查收');
                        } else{
                            $emailsign.html('去点链接');
                        }

                        $('#content').html(data.data.content);
                    } else{
                        layer.open({
                            content:'发送失败'
                        })
                    }
                    $this.data('flag', false);
                }, 'json')
                .fail(function() {
                    layer.open({
                        content: '服务器繁忙',
                        time: 3
                    });
                    $this.data('flag', false);
                })
        },
        validate: function() {
            var formValidate = $("#find-verify").validate({
                submitHandler: function(form) {
                    var $form = $(form);
                    var formData = $form.serializeArray();
                    var flag = $form.data('flag');
                    var $submitBtn = $('[type="submit"]', $form);

                    if (flag) return;
                    $form.data('flag', true);
                    $submitBtn.val('提交中...');

                    $.post('/FindPassword-sureaccount', formData, function(data) {
                            var url = data.data.url;

                            if (data.status === 0) {
                                if (url) {
                                    location.href = url;
                                }
                            } else {
                                $('p.error').show();
                                $form.data('flag', false);
                            }
                        }, 'json')
                        .fail(function() {
                            layer.open({
                                content: '服务器繁忙',
                                time: 3
                            });
                            $form.data('flag', false);
                        })
                        .complete(function() {
                            $submitBtn.val('下一步');
                        })
                },
            });

            return formValidate;
        }
    }

    FindVerify.init();
    module.exports = FindVerify;

});
