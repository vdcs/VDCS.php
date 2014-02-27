
$uploadxModel['pic']={
	
	initParams:function(){
		this.ps=ox(this.ps,{
			"id_img_pic":"img-pic",
			"id_input_pic":"input-pic",
			"message_abdata":"服务数据异常！",
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
				that.serveShow({url:varValue("file.urls")});
				that.hideTips();
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
	serveShow:function(opt){
		opt=ox({tiopt:true},opt);
		//alert(opt.url);
		var jinput=$("#"+this.c("id_input_pic"));
		if(!opt.url){
			var _url=jinput.val();
			if(_url) this.serveShowPic(_url);
			return;
		}
		this.serveShowPic(opt.url);
		jinput.val(opt.url);
	},
	serveShowPic:function(url){
		this.showLayout();
		this.imgSrc(this.c("id_img_pic"),url);
	},
	
'':''};
