/**
 * 
 * @authors Your Name (you@example.org)
 * @date    2015-05-28 15:57:44
 * @version $Id$
 */
define(function(require, exports, module) {
    var $ = require('jquery'),
        layer = require('layer');
        verifyCode = require('verifyCode');

    require('validate');
    require('defaultRules');





    var bindTel = {
        init: function() {
            var formValidate = this.validate();

            $('.get_verify').on('click', function() {

                if (formValidate.element('#tel')) {
                    verifyCode.getCode();
                }
            });
            //后退功能
            $('.icon-return').on('click', function() {
                window.history.back(-1);
            });
        },
        validate: function() {
            var formValidate = $("#bindtel-form").validate({
                rules: {
                    phoneVal: {
                        required: true,
                        istelephone: true,
                        number: true,
                        remote: {
                            url: 'Account-mobile',
                            type: 'post',
                            async: false,
                            dataType: 'json',
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
                        required: '请输入手机号',
                        istelephone: '手机格式不正确',
                        number: '手机格式不正确',
                        remote: '手机号已被注册'
                    }
                }
            });

            return formValidate;
        }
    }

    return bindTel;


});
