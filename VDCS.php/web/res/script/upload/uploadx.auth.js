
$uploadxModel['auth']={
	
	initCallback:function(){this.initCallbackAuth()},
	initModel:function(){this.initModelAuth()},
	
	//UploadComplete,EditorSaved,EditorError
	initCallbackAuth:function(){
		var __othis=this;
		if(!this.isCallback("UploadComplete")){
			this.setCallback("UploadComplete",function(vars){
				//__othis.hideUpload();
				//alert(__othis.vars["xml"]);
			});
		}
	},
	initModelAuth:function(){
		var __othis=this;
		__othis.hideBar();
		__othis.showUpload();
		this.SWFUpload=initSWFUpload(__othis);
	},
	
'':''};
