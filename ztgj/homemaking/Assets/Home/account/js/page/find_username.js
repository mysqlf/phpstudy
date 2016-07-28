/**
 * 
 * @authors Your Name (you@example.org)
 * @date    2015-06-11 20:06:55
 * @version $Id$
 */
define(function(require, exports, modules) {
    var $ = require('jquery'),
        layer = require('layer');

    require('validate');
    require('defaultRules');


    var findUser = {
        init: function() {
            var self = this;
            self.setDefaultVal();
            self.validate();

            $('#tel_email').focus(function() {
                $('p.error').hide();
            });

            self.validateAccount();

            $('#tel_email').on('blur keyup', function() {
                self.validateAccount();
            })
        },
        validateAccount: function(obj) {
            var val = $('#tel_email').val();
            var isTel = /^(13[0-9]|14[5|7]|15[0|1|2|3|5|6|7|8|9]|17[0-9]|18[0|1|2|3|4|5|6|7|8|9])\d{8}$/.test(val);
            var isEmail = /^[a-zA-Z0-9.!#$%&'*+\/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$/.test(val);
            if (isTel || isEmail) {
                $('#resume-Name').attr('required', true).closest('.relative_box').show();
            } else {
                $('#resume-Name').attr('required', false).closest('.relative_box').hide();
            }
        },
        setDefaultVal: function() {
            if (sessionStorage.account) {
                $('input[name="account"]').val(sessionStorage.account);
            }
        },
        validate: function() {
            var formValidate = $("#username-form").validate({
                submitHandler: function(form) {
                    var $form = $(form);
                    var formData = $form.serializeArray();
                    var flag = $form.data('flag');
                    var $submitBtn = $('[type="submit"]', $form);
                    var val = $form.find('input[name="account"]').val();
                    var resumeNameHidden = $form.find('input[name="resumeName"]').parent().is(':hidden');
                    if (flag) return;
                    $form.data('flag', true);
                    $submitBtn.val('提交中...');
                    sessionStorage.account = val;
                    $.post('/FindPassword-getpassword', formData, function(data) {
                            var url = data.data.url;

                            if (data.status === 0) {
                                if (url) {
                                    location.href = url;
                                }
                            } else {
                                if (data.status === 900000) { // 账号不存在
                                    if (resumeNameHidden)
                                    {
                                        layer.open({
                                            title: '<span class="color_blue_deep">' + $('#tel_email').val() + '</span>',
                                            className: 'login-layer',
                                            content: '<div class="content text-center">这个账号不存在，<br>欢迎注册一个新账号。</div>' +
                                                '<a class="btn btn-fill commen_btn" href="Account-reg?redirect=' + $('[name="redirect"]').val() + '">注册</a>'
                                        })
                                    }
                                    else
                                    {
                                       layer.open({
                                            title: '<span class="color_blue_deep">' + $('#tel_email').val() + '</span>',
                                            className: 'login-layer',
                                            content: '<div class="content text-center">账号或姓名错误，<br>请重新输入账号和姓名</div>'
                                        })
                                    }
                                } else if (data.status === 999998) { //没有填写过手机号码和邮箱
                                    layer.open({
                                        title: '<span></span>',
                                        className: 'login-layer',
                                        content: '<div class="content text-center">您的账号没有填写过<br>手机号码和邮箱，<br>请拨打 <span class="color_blue_deep f-lg">95105333</span>  <br>通过客服找回密码！<br><br></div>'
                                    })
                                } else{
                                    layer.open({
                                        content: data.msg
                                    });
                                }

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
                success: function() {
                    $('p.error').hide();
                },
                rules: {
                    account: {
                        required: true
                    }
                },
                messages: {
                    account: {
                        required: '请输入找回密码的账号'
                    }
                }
            });
            return formValidate;
        }

    }


    findUser.init();


});
