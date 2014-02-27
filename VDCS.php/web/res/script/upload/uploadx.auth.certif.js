
$uploadxModel['auth.certif']={
	
	initParams:function(){
		this.ps=ox(this.ps,{
			"id_img_certif":"img-certif",
			"id_apply_form":"apply-form",
			"id_apply_submit":"apply-submit",
			"form_cert_name":"cert-name",
			"form_cert_no":"cert-no",
			"message_abdata":"服务数据异常！",
			"message_apply_nodata":"请填写名称和证件号码！",
			"message_apply_ing":"正在提交您的认证请求 ..",
			"message_apply_complete":"您的认证请求已提交！请耐心等候审核。",
			"message_auth_not":"您的证件和真实信息不符！请重新上传再提交认证。",
			"message_auth_succeed":"您的认证已经通过！",
		'':''});
		$uploadx_avatar=this;
	},
	
	initCallback:function(){this.initCallbackAuth()},
	initModel:function(){this.initModelAuth()},
	
	//UploadComplete,EditorSaved,EditorError
	initCallbackAuth:function(){
		var that=this;
		if(!this.isCallback("UploadComplete")){
			this.setCallback("UploadComplete",function(vars){
				that.hideUpload();
				//alert(that.vars["xml"]);
				that.serveShow({tips:false});
				that.hideTips();
				that.serveApplyForm(true);
			});
		}
	},
	initModelAuth:function(){
		this.hideBar();
		this.showUpload();
		this.SWFUpload=initSWFUpload(this);
		this.serveShow();
	},
	
	
	/*
	########################################
	########################################
	*/
	serveApplyForm:function(status){
		var that=this;
		if(status){
			$("#"+that.c("id_apply_form")).show();
			if(!that._apply_submit){
				$("#"+that.c("id_apply_submit")).click(function(){
					that.serveApply()
				});
				that._apply_submit=true;
			}
		}
		else{
			$("#"+that.c("id_apply_form")).hide();
		}
	},
	
	serveShow:function(ps){
		ps=ox({tips:true},ps);
		var that=this;
		//dbg.t("serveShow: "+$url.link(that.c("serveURL"),"mode="));
		$ajax({url:$url.link(that.c("serveURL"),"mode="),value:"map",ready:function(maps){
			var isdata=false;
			if(maps){
				var treeVar=maps.getItemTree("var");
				if(treeVar.v("status")=="succeed"){
					isdata=true;
					that.showLayout();
					that.imgSrc( that.c("id_img_certif"),treeVar.v("res.origin.url"));
					
					var auth_status=treeVar.v("auth.status");
					if(auth_status=="yes"){
						that.hideBar();
						that.hideUpload();
						that.showTips(that.serveMessage("auth_succeed"));
					}
					else{
						var isapplyform=false;
						if(treeVar.v("apply.is")){
							if(ps.tips){
								if(auth_status=="not") that.showTips(that.serveMessage("auth_not"));
								else that.showTips(that.serveMessage("apply_complete"));
							}
						}
						else{
							isapplyform=true;
						}
						if(isapplyform) that.serveApplyForm(true);
					}
				}
			}
		},error:true});
	},
	
	serveApply:function(){
		var that=this;
		var sendData={
			cert_name:$f.v(this.c("form_cert_name")),
			cert_no:$f.v(this.c("form_cert_no"))
		};
		if(!sendData.cert_name || !sendData.cert_no){
			$xtip.popups('error',that.serveMessage("apply_nodata"),true);
			return;
		}
		that.hideUpload();
		that.serveApplyForm(false);
		that.showTips(that.serveMessage("apply_ing"));
		$ajax({url:$url.link(that.c("serveURL"),"mode=apply"),send:sendData,value:"map",ready:function(maps){
			that.hideTips();
			var isdata=false,isapply=false;
			if(maps){
				var treeVar=maps.getItemTree("var");
				if(treeVar.v("status")=="succeed"){
					isdata=true;
					if(treeVar.v("apply.status")=="succeed"){
						isapply=true;
						$xtip.popups('succeed',that.serveMessage("apply_complete"),true);
						that.showTips(that.serveMessage("apply_complete"));
					}
				}
			}
			if(!isdata || !isapply){
				$xtip.popups('error',that.serveMessage("abdata"),true);
				that.showTips(that.serveMessage("abdata"));
			}
		},error:true});
	},
	
'':''};
