
if(typeof $uploadx === 'undefined') $uploadx={};
if(typeof $uploadxBase === 'undefined') $uploadxBase={};
if(typeof $uploadxModel === 'undefined') $uploadxModel={};

extendo($uploadxBase,{
	opt:{
		/*
		id				: 'Uploadx',
		idLoading			: 'UploadxLoading',
		idBar				: 'UploadxBar',
		idBtnPic			: 'UploadxBtnPic',
		idBtnCamera			: 'UploadxBtnCamera',
		idBtnSWFU			: 'UploadxBtnSWFU',
		idBtnSWFUpload			: 'UploadxBtnSWFUpload',
		idGuide				: 'UploadxGuide',
		idUpload			: 'UploadxUpload',
		idEditor			: 'UploadxEditor',
		idLayout			: 'UploadxLayout',
		idTips				: 'UploadxTips',
		
		idFPContainer			: 'UploadxFPContainer',
		*/
		message_abdata			: '服务数据异常！',
	'':''},
	callback:{},vars:{},
	
	
	c:function(k){
		var re=this.opt[k];
		if(!re && this.configure) re=this.configure[k]||'';
		return re
	},
	getMessage:function(k){return this.c('message_'+k)},
	
	e:function(k){return this.c('es_'+k)},
	je:function(k){return $(this.e(k))},
	eid:function(k,v){
		v=v||this.opt.KEY+'_'+k;
		var jo=this.je(k);
		if(!jo.attr('id')) jo.attr('id',v);
		return jo.attr('id')
	},
	showImg:function(k,src){
		if(k && src) this.je(k).attr('src',$url.link(src,'_t='+Math.random()))
	},
	
	init:function(opt){
		this.opt=ox(this.opt,opt);
		if(!this.opt.SESSIONID) this.opt.SESSIONID='';
		//dbg.t('SESSIONID: '+this.opt.SESSIONID);
		this.opt=ox({
			PATH_BASE:this.PATH_BASE,PATH_SWFUPLOAD:this.PATH_SWFUPLOAD,
			upload_m			: '',
			upload_x			: 'x',
			upload_channel			: 'common',
			upload_sorts			: '',
			upload_types			: '',
			upload_params			: {PHPSESSID:this.opt.SESSIONID},	// php
			upload_filetype			: 'all',	//all,pic
			SWFUpload_maxsize		: this.opt.maxsize||'10 MB',
			SWFUpload_upload_limit		: this.opt.upload_limit||0,
			SWFUpload_queue_limit		: this.opt.queue_limit||0,
		isinite:false},this.opt);
		this.opt.ids=this.opt.ide?(this.opt.KEY+'_'+this.opt.ide):this.opt.KEY;
	},
	
	initParams:function(){},
	initElement:function(){
		this.hideLoading();
		this.showBar();
		this.showGuide();
		this.hideUpload();
		this.hideEditor();
		this.hideLayout();
		this.hideTips();
	},
	initPage:function(){
		var that=this;
		
	},
	initPager:function(){
		var that=this;
		
	},
	initCallback:function(){
		var that=this;
		
	},
	initModel:function(){
		var that=this;
		
	},
	
	
	/*
	########################################
	########################################
	*/
	URLUpload:function(opt){
		opt=ox({
			m:this.opt.upload_m,x:this.opt.upload_x,
			channel:this.opt.upload_channel,
			sorts:this.opt.upload_sorts,
			types:this.opt.upload_types,
		test:''},opt);
		var re=$url.link(app.serve('c/upload/'+opt.m+'.'+opt.x),'channel='+opt.channel+'&sorts='+opt.sorts+'&types='+opt.types+'');
		return re
	},
	
	varValue:function(k){return this.vars['treeVar'].v(k)},
	
	setCallback:function(action,callback){this.callback[action]=callback},
	doCallback:function(action,vars){if(this.callback[action]) this.callback[action](vars)},
	isCallback:function(action){return this.callback[action]?true:false},
	
	hideLoading:function(){this.je('loading').hide()},
	hideBar:function(){this.je('bar').hide()},
	hideUpload:function(){this.je('upload').hide()},
	hideEditor:function(){this.je('editor').hide()},
	hideLayout:function(){this.je('layout').hide()},
	hideGuide:function(){this.je('guide').hide()},
	hideTips:function(){this.je('tips').hide()},
	
	showLoading:function(html){this.je('loading').show();if(html)this.je('loading').html(html)},
	showBar:function(html){this.je('bar').show();if(html)this.je('bar').html(html)},
	showUpload:function(html){this.je('upload').show();if(html)this.je('upload').html(html)},
	showEditor:function(html){this.je('editor').show();if(html)this.je('editor').html(html)},
	showLayout:function(html){this.je('layout').show();if(html)this.je('layout').html(html)},
	showGuide:function(html){this.je('guide').show();if(html)this.je('guide').html(html)},
	showTips:function(html,timeout){
		var that=this;
		if(html) this.je('tips').show().find('p:first').html(html);
		if(timeout&&timeout>0) $w.timeout(function(){that.hideTips()},timeout)
	},
	
	
	btnPic:function(){return this.je('btn_pic')},
	btnCamera:function(){return this.je('btn_camera')},
	
	btnPicClick:function(callback){this.btnPic().click(function(){callback();return false})},
	btnCameraClick:function(callback){this.btnCamera().click(function(){callback();return false})},
	
	btnPicShape:function(shape){
		var jo=this.btnPic(),_pop=jo.attrd('class-pop')||'pop';
		if(shape=='pop') jo.addClass(_pop);
		else jo.removeClass(_pop);
	},
	btnCameraShape:function(shape){
		var jo=this.btnCamera(),_pop=jo.attrd('class-pop')||'pop';
		if(shape=='pop') jo.addClass(_pop);
		else jo.removeClass(_pop);
	},
	
'':''});


/*
########################################
########################################
*/
initSWFUpload=function(oup,ps){
	ps=ps||oup.opt;
	if(oup.je('swfuploadi').length<1) return;
	var _file_types='',_file_types_description='',_btn_rx='';
	switch(ps.upload_filetype){
		case 'pic':case 'img':case 'image':case 'images':
			_file_types='*.png;*.jpg;*.jpeg;*.gif;*.bmp';
			_file_types_description='All Images';
			_btn_rx='_pic';
			break;
		case 'all':
		default:
			_file_types='*.*';
			_file_types_description='All Files';
			break;
	}
	
	//dbg.t('initSWFUpload: '+oup.URLUpload());
	var oSWFUpload=new SWFUpload({
		upload_url			: oup.URLUpload(),
		post_params			: ps.upload_params		|| {},
		
		file_post_name			: ps.post_field_name		|| 'file1',
		file_size_limit			: ps.SWFUpload_maxsize		|| '10 MB',
		file_types			: _file_types			|| '*.*',
		file_types_description		: _file_types_description	|| 'All File',
		file_upload_limit		: ps.SWFUpload_upload_limit	|| 0,
		file_queue_limit		: ps.SWFUpload_queue_limit	|| 1,
		
		flash_url			: ps.PATH_SWFUPLOAD+'swfupload.swf',
		button_image_url		: ps.button_image_url		|| $c.url('images')+'common/space.gif',		//ps.PATH_UPLOADX+'btns_swfupload'+_btn_rx+'.png'
		button_placeholder_id		: ps.button_placeholder_id	|| oup.eid('swfuploadi'),
		button_width			: ps.button_width		|| 120,
		button_height			: ps.button_height		|| 27,
		button_window_mode		: SWFUpload.WINDOW_MODE.TRANSPARENT,
		button_cursor			: SWFUpload.CURSOR.HAND,
		
		//file_queued_handler		: SWFUpload_FileQueue,
		file_queue_error_handler	: SWFUpload_FileQueueError,
		file_dialog_complete_handler	: SWFUpload_FileDialogComplete,
		//upload_start_handler		: SWFUpload_Start,
		upload_progress_handler		: SWFUpload_Progress,
		upload_error_handler		: SWFUpload_Error,
		upload_success_handler		: SWFUpload_Success,
		upload_complete_handler		: SWFUpload_Complete,
		
		custom_settings : {
			upload_target		: oup.eid('upload_process')
		},
		
		debug: false, uploadx: oup
	});
	return oSWFUpload
};
// un error: popup


function SWFUpload_FileQueue(file){
	try{
		var progress = new FileProgress(file, this.customSettings.progressTarget);
		progress.setStatus('准备上传..');
		progress.toggleCancel(true, this);
	}catch(ex){
		this.debug(ex)
	}
}

function SWFUpload_FileQueueError(file, errorCode, message){
	try{
		var _message=null;
		switch(errorCode){
			case SWFUpload.QUEUE_ERROR.ZERO_BYTE_FILE:			_message="无效或异常的文件！";break;
			case SWFUpload.QUEUE_ERROR.FILE_EXCEEDS_SIZE_LIMIT:		_message="文件大小超出范围！\n(文件不能超过"+this.settings.file_size_limit+")";break;
			case SWFUpload.QUEUE_ERROR.QUEUE_LIMIT_EXCEEDED:		_message="超出了上传的数量！\n(单次只能选择"+this.settings.file_queue_limit+"个文件)";break;
			case SWFUpload.QUEUE_ERROR.INVALID_FILETYPE:
			default:							_message="未知错误("+message+")";break;
		}
		var uploadx=this.settings.uploadx;
		if(uploadx.isCallback('FileQueueError')){
			uploadx.doCallback('FileQueueError',{uploadx:uploadx,file:file,errorCode:errorCode,message:message});
		}
		else{
			if(_message) alert(_message);
		}
	}catch(ex){this.debug(ex)}
}

function SWFUpload_FileDialogComplete(numFilesSelected, numFilesQueued){
	try{
		if(numFilesQueued>0) this.startUpload()
	}catch(ex){this.debug(ex)}
}

function SWFUpload_Start(file){
	try {
		var uploadx=this.settings.uploadx;
		uploadx.hideGuide();
		var progress = new FileProgress(file, this.customSettings.progressTarget);
		progress.setStatus('上传中..');
		progress.toggleCancel(true, this);
	}
	catch(ex){}
	return true
}

function SWFUpload_Progress(file, bytesLoaded){
	try {
		var uploadx=this.settings.uploadx;
		uploadx.hideGuide();
		var percent = Math.ceil((bytesLoaded / file.size) * 100);
		var progress = new FileProgress(file,  this.customSettings.upload_target);
		progress.setProgress(percent);
		if (percent === 100) {
			progress.setStatus('上传结束，处理中..');
			progress.toggleCancel(false, this);
		} else {
			progress.setStatus('上传中，请不要关闭窗口..');
			progress.toggleCancel(true, this);
		}
	}catch(ex){this.debug(ex)}
}

function SWFUpload_hideProgress(timeout){		//disable
	timeout=timeout||0;
	var hideContainer=function(){
		$('#'+swfu.customSettings.upload_target).hide();
	};
	if(timeout>0) $w.timeout(function(){hideContainer()},timeout);
	else hideContainer();
}

function SWFUpload_Success(file, serverData) {		//responseReceived
	this.is_success=false;
	try{
		var uploadx=this.settings.uploadx;
		uploadx.vars['xml']=serverData;
		uploadx.vars['maps']=$util.toMapByXML(serverData);
		uploadx.vars['treeVar']=uploadx.vars['maps'].getItemTree('var');
		//uploadx.vars['tableItem']=uploadx.vars['maps'].getItemTable('item');
		var _status=uploadx.vars['treeVar'].v('status');	//uploadx.varValue('status')
		//dbg.t(_status);
		var progress = new FileProgress(file,this.customSettings.upload_target);
		if(_status=='succeed'){
			progress.setStatus('上传成功！');
			progress.toggleCancel(false);
			this.is_success=true;
		}else{
			alert('上传异常: '+uploadx.vars['treeVar'].v('message'));
			progress.setStatus('上传异常: '+_status+'');
			progress.toggleCancel(false);
		}
	}catch(ex){this.debug(ex)}
}

function SWFUpload_Complete(file) {
	if(!this.is_success) return;
	try{
		if(this.getStats().files_queued > 0){
			this.startUpload();
		}
		var progress = new FileProgress(file,this.customSettings.upload_target);
		progress.setComplete();
		progress.setStatus('上传完成！');
		progress.toggleCancel(false);
		var uploadx=this.settings.uploadx;
		uploadx.doCallback('UploadComplete',{uploadx:uploadx,file:file,treeVar:uploadx.vars['treeVar'],xml:uploadx.vars['xml']});
	}catch(ex){this.debug(ex)}
}

function SWFUpload_Error(file, errorCode, message) {
	var progress;
	try{
		var _message=null;
		switch(errorCode){
			case SWFUpload.UPLOAD_ERROR.FILE_CANCELLED:
				try{
					progress = new FileProgress(file,this.customSettings.upload_target);
					progress.setCancelled();
					progress.setStatus('Cancelled');
					progress.toggleCancel(false);
				}catch(ex1){this.debug(ex1)}
				break;
			case SWFUpload.UPLOAD_ERROR.UPLOAD_STOPPED:
				try {
					progress = new FileProgress(file,this.customSettings.upload_target);
					progress.setCancelled();
					progress.setStatus('Stopped');
					progress.toggleCancel(true);
				}catch(ex1){this.debug(ex1)}
			case SWFUpload.UPLOAD_ERROR.UPLOAD_LIMIT_EXCEEDED:		_message='上传文件过多';break;
			default:
				_message='上传错误: '+message;
				break;
		}
		var uploadx=this.settings.uploadx;
		if(uploadx.isCallback('UploadError')){
			uploadx.doCallback('UploadError',{uploadx:uploadx,file:file,errorCode:errorCode,message:message});
		}
		else{
			if(_message) alert(_message);
		}
	}catch(ex){this.debug(ex)}
}




/*
########################################
	  FileProgress Object
Control object for displaying file info.
########################################
*/
function FileProgress(file, targetID,ProgressID) {
	this.fileProgressID = ProgressID || "SWFUpload_FileProgress";
	
	this.fileProgressWrapper = document.getElementById(this.fileProgressID);
	if(!this.fileProgressWrapper) {
		this.fileProgressWrapper = document.createElement("div");
		this.fileProgressWrapper.className = "SWFUpload_progressWrapper";
		this.fileProgressWrapper.id = this.fileProgressID;
		
		this.fileProgressElement = document.createElement("div");
		this.fileProgressElement.className = "SWFUpload_progressContainer";
		
		var progressCancel = document.createElement("a");
		progressCancel.className = "SWFUpload_progressCancel";
		progressCancel.href = "#";
		progressCancel.style.visibility = "hidden";
		progressCancel.appendChild(document.createTextNode(" "));
		
		var progressText = document.createElement("div");
		progressText.className = "SWFUpload_progressName";
		progressText.appendChild(document.createTextNode(file.name));
		
		var progressBar = document.createElement("div");
		progressBar.className = "SWFUpload_progressBarInProgress";
		
		var progressStatus = document.createElement("div");
		progressStatus.className = "SWFUpload_progressBarStatus";
		progressStatus.innerHTML = "&nbsp;";
		
		this.fileProgressElement.appendChild(progressCancel);
		this.fileProgressElement.appendChild(progressText);
		this.fileProgressElement.appendChild(progressStatus);
		this.fileProgressElement.appendChild(progressBar);
		
		this.fileProgressWrapper.appendChild(this.fileProgressElement);
		
		if(targetID && document.getElementById(targetID)){
			document.getElementById(targetID).appendChild(this.fileProgressWrapper);
			$x.fadeIn(this.fileProgressWrapper, 0);
		}
	} else {
		this.fileProgressElement = this.fileProgressWrapper.firstChild;
		this.fileProgressElement.childNodes[1].firstChild.nodeValue = file.name;
	}
	
	this.height = this.fileProgressWrapper.offsetHeight;

}
FileProgress.prototype.setProgress = function (percentage) {
	this.fileProgressElement.className = "SWFUpload_progressContainer SWFUpload_progress";
	this.fileProgressElement.childNodes[3].className = "SWFUpload_progressBarInProgress";
	this.fileProgressElement.childNodes[3].style.width = percentage + "%";
};
FileProgress.prototype.setComplete = function () {
	this.fileProgressElement.className = "SWFUpload_progressContainer SWFUpload_complete";
	this.fileProgressElement.childNodes[3].className = "SWFUpload_progressBarComplete";
	//this.fileProgressElement.childNodes[3].style.width = '';
};
FileProgress.prototype.setError = function () {
	this.fileProgressElement.className = "SWFUpload_progressContainer SWFUpload_error";
	this.fileProgressElement.childNodes[3].className = "SWFUpload_progressBarError";
	this.fileProgressElement.childNodes[3].style.width = '';
};
FileProgress.prototype.setCancelled = function () {
	this.fileProgressElement.className = "SWFUpload_progressContainer";
	this.fileProgressElement.childNodes[3].className = "SWFUpload_progressBarError";
	this.fileProgressElement.childNodes[3].style.width = '';

};
FileProgress.prototype.setStatus = function (status) {
	this.fileProgressElement.childNodes[2].innerHTML = status;
};

FileProgress.prototype.toggleCancel = function (show, swfuploadInstance) {
	if(show) this.show();
	else this.hide();
	this.fileProgressElement.childNodes[0].style.visibility = show ? "visible" : "hidden";
	if (swfuploadInstance) {
		var fileID = this.fileProgressID;
		this.fileProgressElement.childNodes[0].onclick = function () {
			swfuploadInstance.cancelUpload(fileID);
			return false;
		};
	}
};

FileProgress.prototype.show = function () {
	this.fileProgressWrapper.style.visibility = "visible";
};
FileProgress.prototype.hide = function () {
	this.fileProgressWrapper.style.visibility = "hidden";
};
