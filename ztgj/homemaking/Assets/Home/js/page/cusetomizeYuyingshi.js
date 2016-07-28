seajs.use([
	'/Assets/Home/js/lib/jquery-2.1.4.min', 
	'/Assets/Home/js/lib/tools.min', 
	'/Assets/Home/js/plug/popup/popup', 
	'/Assets/Home/js/plug/layer.m/layer.m',
	'/Assets/Home/js/plug/mobiscroll/mobiscroll',
	'/Assets/Home/js/plug/validate/jquery.validate.min'
],
	function($, tool, popup,layer){
	$(function(){
		var customNurseryTeacher={
			init:function(){
				var self=this;
				self.goCustom();
				self.imgStopRote();
			},
			//马上定制
			goCustom:function(){
				$("#go_custom").on('click',function(){
					//type=1是定制月嫂   type=2是定制育婴师 type=3是定制粤管家
					var $this=$(this);
					var type=$this.attr('type');
					if(type==1){
						var html='<div class="con_tit">马上定制</div>'+
							'<form id="form_cusetom">'+
							'<input type="hidden" value="1" name="type">'+
							'<div class="con_list"><i>*</i><input type="text" placeholder="您的手机" name="tel" value="" class="phone_inp" required></div>'+
							'<div class="error_text">请输入正确的手机号码！</div>'+
							'<div class="con_list"><i>*</i><input type="text" placeholder="您的预产期" name="promisetime" value="" class="due_date " required></div>'+
							'<div class="con_list con_text"><i>*</i><textarea placeholder="您的定制要求(例如属相属鸡,身材中等...)" name="claim" value="" required></textarea></div>'+
							'</form>';
					}
					if(type==2){
						var html='<div class="con_tit">马上定制</div>'+
							'<form id="form_cusetom">'+
							'<input type="hidden" value="2" name="type">'+
							'<div class="con_list"><i>*</i><input type="text" placeholder="您的手机" name="tel" value="" class="phone_inp" required></div>'+
							'<div class="error_text">请输入正确的手机号码！</div>'+
							'<div class="con_list"><i>*</i><input type="text" placeholder="宝宝出生日期" name="promisetime" value="" class="due_date " required></div>'+
							'<div class="con_list con_text"><i>*</i><textarea placeholder="您的定制要求(例如30-35岁,广东,广西人...)" name="claim" value="" required></textarea></div>'+
							'</form>';
					}
					if(type==3){
						var html='<div class="con_tit">马上定制</div>'+
							'<form id="form_cusetom">'+
							'<input type="hidden" value="3" name="type">'+
							'<div class="con_list"><i>*</i><input type="text" placeholder="您的手机" name="tel" value="" class="phone_inp" required></div>'+
							'<div class="error_text">请输入正确的手机号码！</div>'+
							'<div class="con_list"><i>*</i><span class="check_box"><label class="radio_box"><input type="radio" checked="checked" name="housekeeper" class="custom" value="0" required><i></i></label>单品定制</span><span class="check_box"><label class="radio_box"><input type="radio" name="housekeeper" class="custom" value="1" required><i></i></label>套餐定制</span></div>'+
							'<div class="con_list con_text"><i>*</i><textarea name="claim" value="" required id="textArea" placeholder="您的定制要求(例如营养配餐师会面食等等,ps：单品定制可单独挑选粤管家内任何一产品)"></textarea></div>'+
							'</form>';
						}
					layer.open({
						content:html,
						btn:['提交'],
						success:function(){
							$("#form_cusetom").validate({
								rules:{
									tel:{
										istelephone:true
									}
								},
								errorPlacement:function(error,element){	
								},
								submitHandler:function(from){
									var html='<div class="con_tit">定制成功</div>'+
									'<p>请保持电话通畅,网站工作人员会</p>'+
									'<p>尽快为您提供服务。</p>';
									var htmlFalse='<div class="con_tit" style="border:none" id="unCunsetom"></div>'
									var dataJson=$("#form_cusetom").serializeArray();
									var url="/Homemaking-confirmcustommade";
									$.post(url,dataJson,function(data){
										console.log(data);
										if(data=="true"){
											layer.open({
												content:html
											});
										}else if(data==1){
											layer.open({
												content:htmlFalse,
												success:function(){
													$("#unCunsetom").html("您已经定制过了！")
												}
											});
										}else{
											layer.open({
												content:htmlFalse,
												success:function(){
													$("#unCunsetom").html("手机号或时间格式错误！")
												}
											});
										}
									});
								}
							});
							$(".custom").click(function(){
								var $this=$(this);
								var val=$this.val();
								if($this.is(':checked')){
									if(val==0){
										$('#textArea').attr('placeholder','您的定制要求(例如营养配餐师会面食等等，ps：单品定制可单独挑选粤管家内任何一产品)');
									}else{
										$('#textArea').attr('placeholder','您的定制要求(例如二人团，三人团...)');
									}
								}
							})
							$(".layermbox").css('zIndex','100');
							$(".layermbtn").addClass("pink_btn");
							$(".layermcont").addClass("paddingBottom0");
							var currYear = (new Date()).getFullYear();
							var opt = {};
							opt.date = {
								preset: 'date'
							};
							opt.default = {
								theme: 'android-ics light', //皮肤样式
								startYear: currYear - 18, //开始年份
								endYear: currYear + 1 //结束年份
							};
							$('.due_date').mobiscroll($.extend(opt['date'], opt['default']));
						},
						yes:function(){
							$("#form_cusetom").submit();

						}
					})
					
					
				})
			},	
			//控制图片旋转
			imgStopRote:function(){
				$(".img_box").on('touchstart',function(){
					$(this).find('img').removeClass('img_rorate');
				});
				$(".img_box").on('touchend',function(){
					$(this).find('img').addClass('img_rorate');
				});
			}
			
		}
		
//		go_custom
		customNurseryTeacher.init();
	})
})