
if(typeof $uploadx === 'undefined') $uploadx={};

extendo($uploadx,{
	PATH_BASE				: $c.url('script')+'upload/',
	PATH_SWFUPLOAD				: $c.url('script')+'upload/swfupload/',
	PATH_UPLOADX				: $c.url('script')+'upload/',
	model_path				: $c.url('script')+'upload/uploadx.{model}.js',
	opt:{
		KEY				: 'Uploadx',
		es_wrap				: '#Uploadx',
		es_loading			: '#Uploadx .loading',
		es_bar				: '#Uploadx .bar',
		es_btn_pic			: '#Uploadx .btn_pic',
		es_btn_camera			: '#Uploadx .btn_camera',
		es_guide			: '#Uploadx .guide',
		es_tips				: '#Uploadx .tips',
		es_editor			: '#Uploadx .editor',
		es_layout			: '#Uploadx .layout',
		es_upload			: '#Uploadx .upload',
		es_upload_process		: '#Uploadx .upload .process',
		es_swfuploadi			: '.swfuploadi',
	'':''},
	o:{},model:{},
	/*
	ids:function(ide){return this.opt.id+(ide?('_'+ide):'')},
	j:function(ide){return $('#'+this.ids(ide))},
	*/
	
	core:function(cnext){
		if(this.iscore){cnext();return}
		var that=this;
		var urlRes=[this.PATH_SWFUPLOAD+'swfupload.js?'+$c.vrt(1)];
		urlRes.push(this.PATH_BASE+'uploadx.base.js?'+$c.vrt(1));
		//urlRes.push(this.PATH_BASE+'uploadx.css?'+$c.vrt(1));
		//dbg.o(urlRes)
		$.include(urlRes,function(){
			that.iscore=true;
			cnext()
		})
	},
	
	init:function(opt,callback){
		var that=this;
		var model=opt.model,name=opt.name||opt.ide||model;
		this.o[name]={name:name,model:model,PATH_BASE:this.PATH_BASE,PATH_SWFUPLOAD:this.PATH_SWFUPLOAD};
		
		this.core(function(){
			var urlRes=[];
			if(model) urlRes.push($url.link(rv(opt.model_path||that.model_path,'model',model),$c.vrt(1)));
			//dbg.o(urlRes)
			$.include(urlRes,function(){
				that.isiniter=true;
				that._initModel(that.o[name],opt,callback);
			});
		});
		return this.o[model]
	},
	_initModel:function(omodel,opt,callback){	//that.o[model]
		omodel=extend(omodel,$uploadxBase);
		if(omodel.model && $uploadxModel[omodel.model]) omodel=extend(omodel,$uploadxModel[omodel.model]);
		opt=ox(opt,this.opt);
		//dbg.o(opt);
		omodel.init(opt);
		omodel.initParams();
		omodel.initElement();
		omodel.initPage();
		omodel.initPager();
		omodel.initCallback();
		omodel.initModel();
		if(callback) callback(omodel);
	},
	
	
	btn_count:0,
	elementID:function(ide){return this.opt.KEY+'__'+ide},
	btnElement:function(ide,jswap,opt){
		this.btn_count++;
		opt=ox({jwrap:null,top:0,left:0,width:60,height:20,force_place:false,force_size:false,inbody:false,zindex:(10000+this.btn_count),display:'show',sessionid:''},opt);
		if(!ide) ide='n'+this.btn_count;
		var _ids=this.elementID(ide);
		var juploadx=opt.jwrap||$j('#'+_ids);
		if(juploadx.length<1){
			var _html='<div id="'+_ids+'" data-ide="'+ide+'"><span class="swfuploadi" sessionid="{sessionid}"></span></div>';
			_html=r(_html,'{sessionid}',opt.sessionid);
			//$(document.body).append(_html);
			juploadx=$(_html).appendTo((!opt.inbody&&jswap)?jswap:'body');	//jswap||
		}
		if(jswap){
			jswap.attrd('ide',ide);
			if(!opt.inbody) jswap.css({'position':'relative'});
			if(opt.inbody && !opt.force_place){opt.top=jswap.offset().top;opt.left=jswap.offset().left;}
			if(!opt.force_size){opt.width=jswap.outerWidth();opt.height=jswap.outerHeight();}
		}
		juploadx.css({'position':'absolute','top':opt.top,'left':opt.left,'width':opt.width,'height':opt.height,'z-index':opt.zindex,'overflow':'hidden'});
		if(opt.inbody && !opt.force_place){
			jswap.on('mouseenter',function(){
				var ocss={};
				if(opt.inbody && !opt.force_place) ocss=ox(ocss,{'top':jswap.offset().top,'left':jswap.offset().left});
				if(!opt.force_size) ocss=ox(ocss,{'width':jswap.outerWidth(),'height':jswap.outerHeight()});
				juploadx.css(ocss);
			});
		}
		//dbg.o(opt);
		//juploadx.css({'width':60,'height':20});
		//juploadx.css({'background-color':'#DDDDDD'});
		juploadx.find('.swfuploadi').css({display:'inline-block',width:'100%',height:'100%'});
		if(opt.display=='show') juploadx.show();
		if(opt.display=='hide') juploadx.hide();
		return juploadx
	},
	btnInit:function(ide,opt,callback){
		var juploadx=iso(ide)?ide:$('#'+this.elementID(ide));
		if(iso(ide)) ide=juploadx.attrd('ide');
		if(!ide){
			alert('Uploadx: no ide');
			return false;
		}
		if(juploadx.length<1){
			alert('Uploadx: Uninitialized Element');
			return false;
		}
		//alert(juploadx);
		if(juploadx.attr('_init')) return false;
		opt=ox({ide:ide,model:'image',model_path:'',serveURL:'',
			upload_channel:'',upload_sorts:'',upload_filetype:'pic',upload_limit:0,queue_limit:0},opt);
		opt.sessionid=opt.sessionid||juploadx.find('.swfuploadi').attr('sessionid');
		callback=ox({},callback);
		callback.opt=callback.opt||{};
		callback.opt=ox({ide:opt.ide,
			model:opt.model,model_path:opt.model_path,
			upload_channel:opt.upload_channel,upload_sorts:opt.upload_sorts,upload_filetype:opt.upload_filetype,upload_limit:opt.upload_limit,queue_limit:opt.queue_limit,
			serveURL:opt.serveURL,
			SESSIONID:opt.sessionid},callback.opt);
		//dbg.o(callback.opt);
		this.init(callback.opt,function(omodel){
				if(callback.complete) omodel.setCallback('UploadComplete',callback.complete);
				if(callback.load) callback.load(omodel);
				juploadx.attr('_init','true');
			});
		return juploadx
	},
	
'':''});


/*
########################################
########################################
*/
/*
extendo($uploadx,{
	_opt:function(opt){
		opt=ox({upload_channel:'t',upload_sorts:'image'},opt);
		if(opt.sorts=='user'){
			opt.upload_channel='account';
			opt.upload_sorts='home';
		}
		return opt;
	},
	imgInit:function(ide,opt,callback){
		opt=this._opt(opt);
		opt=ox({model:"image",model_path:$c.url("theme")+"app.t.uploadx.{model}.js",
			"serveURL":app.serve('t/upload/image'),
			upload_filetype:"pic",upload_limit:0},opt);
		return this.btnInit(ide,opt,callback);
	},
	
'':''});
*/
