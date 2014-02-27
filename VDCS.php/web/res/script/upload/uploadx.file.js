
$uploadxModel['file']={
	
	initCallback:function(){this.initCallbackFile()},
	initPage:function(){this.initPageFile()},
	
	//UploadComplete,EditorSaved,EditorError
	initCallbackAuth:function(){
		var __othis=this;
		if(!this.isCallback("UploadComplete")){
			this.setCallback("UploadComplete",function(vars){
				__othis.hideUpload();
				dbg.o(vars)
				//alert(__othis.vars["xml"]);
			});
		}
	},
	initPageFile:function(){
		var __othis=this;
		this.SWFUpload=initSWFUpload(__othis);
	},
	
'':''};
