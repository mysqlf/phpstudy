/**
 * 
 * @authors Your Name (you@example.org)
 * @date    2015-05-17 15:57:53
 * @version $Id$
 */
define(function(require, exports, module) {

    var $ = require('jquery'),
        layer = require('layer');

    require('pwShow');
    require('validate');
    require('defaultRules');
    verifyCode = require('verifyCode');


    var Register = {
        init: function() {
            var formValidate = this.goSignup();

            $('.get_verify').on('click', function() {

                if (formValidate.element('#tel') && formValidate.element('#captcha')) {
                    verifyCode.getCode();
                }
            });

            verifyCode.init();
        },
        sendLogin: function(form) {
            var $form = $(form);
            var formData = $form.serializeArray();
            var flag = $form.data('flag');
            var $submitBtn = $('[type="submit"]', $form);
            var actLink = $form.attr('action') || 'Account-reg';

            if (flag) return;
            $form.data('flag', true);
            $submitBtn.val('注册中...');

            $.post(actLink, formData, function(data) {
                    var url = data.data.url;

                    if (data.status === 0) {
                        layer.open({
                            content: '注册成功'
                        });
                        if (url) {
                            location.href = url
                        }
                    } else {
                        $form.data('flag', false);
                        if (data.status === 200000) {
                            // 账号已经存在
                            var index = layer.open({
                                title: '<span class="error">该账号已经存在</span>',
                                className: 'login-layer',
                                content: '<div class="content text-center">继续获取会导致原先<br> 的账号无法正常使用。 </div>' +
                                    '<a class="btn btn-fill commen_btn" href="Account-login?redirect=' + $('[name="redirect"]').val() + '">登录</a>' +
                                    '<a class="btn keep-reg btn-border btn-fill" href="javascript:;">继续注册</a>'
                            })

                            $('.keep-reg').click(function() { //重新提交表单
                                layer.close(index)
                                $('#keep-reg').val(1);
                                $('[type="submit"]', $form).trigger('click');
                            });
                        } else {
                            layer.open({
                                content: '注册失败'
                            });
                        }
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
                    $submitBtn.val('登录');
                    $('#keep-reg').val(0); // 重置已有账号 继续注册
                })

        },
        goSignup: function() {
            var self = this;
            var formValidate = $("#signup-form").validate({
                submitHandler: function(form) {
                    self.sendLogin(form);
                },
                rules: {
                    phoneVal: {
                        remote: {
                            url: 'Account-telphone',
                            type: 'post',
                            dataType: 'json',
                            async: false,
                            data: {
                                'phoneVal': function() {
                                    return $("#tel").val();
                                }
                            }
                        }
                    }
                },
                messages: {
                    phoneVal: {
                        required: '请输入手机号码作为账号',
                        remote: '* 账号已存在，请<a href="Account-login?redirect=' + $('#redirect').val() + '" style="margin-left:5px;" class="forget_pw ">登录</a>'
                    },
                    verifyCode: {
                        required: '请输入验证码',
                        remote: '验证码错误'
                    }
                }
            });

            return formValidate
        }
    }


    module.exports = Register;

});
