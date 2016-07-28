webpackJsonp([3],{0:/*!***************************!*\
  !*** ./js/entry/login.js ***!
  \***************************/
function(n,o,t){var a=t(10);a.init()},6:/*!***************************!*\
  !*** ./js/plug/pwShow.js ***!
  \***************************/
function(n,o,t){var a;a=function(n,o,a){var i=t(1);i(".password_hidden").on("click",function(){var n=i(this);n.children().hasClass("icon-hidden")?(n.children().removeClass("icon-hidden").addClass("icon-see"),i("#password").attr("type","text")):(n.children().removeClass("icon-see").addClass("icon-hidden"),i("#password").attr("type","password"))})}.call(o,t,o,n),!(void 0!==a&&(n.exports=a))},10:/*!**************************!*\
  !*** ./js/page/login.js ***!
  \**************************/
function(n,o,t){var a;a=function(n,o,a){var i=t(1),r=t(2);t(6),t(3),t(4);var s={init:function(){sessionStorage.account&&i('input[name="account"]').val(sessionStorage.account),i("#login_box form input").focus(function(){i("p.error").hide()}),i("#login-form input[name='account']").blur(function(){var n=i(this).val();sessionStorage.account=n}),this.goLogin()},goLogin:function(){var n=i("#login-form").validate({submitHandler:function(n){var o=i(n),t=o.serializeArray(),a=o.data("flag"),s=i('[type="submit"]',o),e=o.attr("action")||"/Account-login";a||(o.data("flag",!0),s.val("登录中..."),i.post(e,t,function(n){var t=n.data.url;0===n.status?(r.open({content:"登录成功"}),t&&(location.href=t)):(n.msg?i("p.error").html(n.msg).show():i("p.error").html("账号和密码不一致").show(),o.data("flag",!1))},"json").fail(function(){r.open({content:"服务器繁忙",time:3}),o.data("flag",!1)}).complete(function(){s.val("登录")}))},success:function(){i("p.error").hide()}});return n}};a.exports=s}.call(o,t,o,n),!(void 0!==a&&(n.exports=a))}});
//# sourceMappingURL=login.bundle.js.map