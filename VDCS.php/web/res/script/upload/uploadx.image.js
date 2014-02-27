
$uploadxModel['image']={
	
	initParams:function(){
		this.ps=ox(this.ps,{
			"id_images":"img-certif",
			"message_abdata":"服务数据异常！",
			"message_apply_nodata":"请填写名称和证件号码！",
			"message_apply_ing":"正在提交您的认证请求 ..",
			"message_apply_complete":"您的认证请求已提交！请耐心等候审核。",
			"message_auth_not":"您的证件和真实信息不符！请重新上传再提交认证。",
			"message_auth_succeed":"您的认证已经通过！",
		'':''});
	},
	
	initCallback:function(){this.initCallbackAuth()},
	initModel:function(){this.initModelAuth()},
	
	//UploadComplete,EditorSaved,EditorError
	initCallbackAuth:function(){
		var __othis=this;
		if(!this.isCallback("UploadComplete")){
			this.setCallback("UploadComplete",function(vars){
				//__othis.hideUpload();
				//alert(__othis.vars["xml"]);
				__othis.servePlus(ox(vars,{tips:false}));
				//__othis.hideTips();
			});
		}
	},
	initModelAuth:function(){
		this.hideBar();
		this.showUpload();
		var __othis=this;
		this.SWFUpload=initSWFUpload(__othis);
	},
	
	
	/*
	########################################
	########################################
	*/
	servePlus:function(ps){
		ps=ox({tips:true},ps);
		var __othis=this;
		//alert(ps.xml);
		var treeVar=ps.treeVar;
		alert(treeVar.v("status"));
	},
	serveShow:function(ps){
		ps=ox({tips:true},ps);
		var __othis=this;
		dbg.t("serveShow: "+$url.link(__othis.c("serveURL"),"mode="));
		$ajax({url:$url.link(__othis.c("serveURL"),"mode="),value:"map",ready:function(maps){
			var isdata=false;
			if(maps){
				var treeVar=maps.getItemTree("var");
				if(treeVar.v("status")=="succeed"){
					isdata=true;
					__othis.showLayout();
					__othis.imgSrc( __othis.c("id_img"),treeVar.v("res.origin.url"));
					
					var auth_status=treeVar.v("auth.status");
					if(auth_status=="yes"){
						__othis.hideBar();
						__othis.hideUpload();
						__othis.showTips(__othis.serveMessage("auth_succeed"));
					}
					else{
						var isapply=false;
						if(treeVar.v("apply.is")){
							if(ps.tips){
								if(auth_status=="not") __othis.showTips(__othis.serveMessage("auth_not"));
								else __othis.showTips(__othis.serveMessage("apply_complete"));
							}
						}
						else{
							isapply=true;
						}
					}
				}
			}
		},error:true});
	},
	
'':''};
