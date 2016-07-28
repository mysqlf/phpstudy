define(function(require, exports, module) {
    var $ = require('jquery');
    require('validate');

    $.validator.setDefaults({
        rules: {
            password: {
                required: true,
                maxlength: 20,
                minlength: 6,
                password: true
            },
            phoneVal: {
                required: true,
                istelephone: true,
                number: true
            },
            verifytext: {
                required: true,
                number: true,
                maxlength: 6,
                remote: {
                    url: 'Account-code',
                    type: 'post',
                    data: {
                        'phoneVal': function() {
                            return $("#tel").val();
                        },
                        'codeVal': function() {
                            return $(".verify_text").val();
                        }
                    }
                }
            },
            verifyCode: {
                remote: {
                    url: 'Account-verifyCode',
                    type: 'post',
                    dataType: 'json',
                    async: false
                }
            },
            resumeName: {
                maxlength: 10
            },
            repassword: {
                equalTo: '#password'
            }
        },
        messages: {
            account: {
                required: '请输入账号'
            },
            password: {
                required: '请输入密码',
                minlength: '密码不能少于6位数',
                maxlength: '密码不能超过20位数',
                password: '只能输入数字、字母或字符'
            },
            verifytext: {
                required: '请输入短信验证码',
                number: '请输入数字',
                maxlength: '请输入6位数字',
                remote: '验证码不一致'
            },
            resumeName: {
                required: '请输入简历中的姓名',
                maxlength: '输入字符超过10个字'
            },
            phoneVal: {
                required: '请输入手机号',
                istelephone: '请输入正确的手机号码',
                number: '请输入正确的手机号码',
                remote: '手机号已被注册'
            },
            repassword: {
                required: '请再次输入密码',
                equalTo: '重复的密码和新密码不一致'
            },
            verifyCode: {
                required: '请输入验证码',
                remote: '验证码错误'
            }
        }
    });
})
