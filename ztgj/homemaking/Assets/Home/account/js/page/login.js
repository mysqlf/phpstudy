/**
 * 
 * @authors Your Name (you@example.org)
 * @date    2015-05-17 15:53:39
 * @version $Id$
 */
define(function(require, exports, module) {
    var $ = require('jquery'),
        layer = require('layer');

    require('pwShow');
    require('validate');
    require('defaultRules');

    var Login = {
        init: function() {

            if (sessionStorage.account) {
                $('input[name="account"]').val(sessionStorage.account);
            }

            $('#login_box form input').focus(function() {
                $('p.error').hide();

            });
            $("#login-form input[name='account']").blur(function() {

                var val = $(this).val();
                sessionStorage.account = val;

            });

            this.goLogin();
        },
        goLogin: function() {
            var formValidate = $("#login-form").validate({
                submitHandler: function(form) {
                    var $form = $(form);
                    var formData = $form.serializeArray();
                    var flag = $form.data('flag');
                    var $submitBtn = $('[type="submit"]', $form);
                    var actLink = $form.attr('action') || '/Account-login';
                    
                    if (flag) return;
                    $form.data('flag', true);
                    $submitBtn.val('登录中...');

                    $.post(actLink, formData, function(data) {
                            var url = data.data.url;
                            if (data.status === 0) {
                                layer.open({
                                    content: '登录成功'
                                });
                                if (url) {
                                    location.href = url;
                                }
                            } else {
                                if(data.msg){
                                    $('p.error').html(data.msg).show();
                                } else{
                                    $('p.error').html('账号和密码不一致').show();
                                }
                                
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
                            $submitBtn.val('登录');
                        })
                },
                success: function() {
                    $('p.error').hide();
                }
            });

            return formValidate;
        }
    }

    module.exports = Login;

});
