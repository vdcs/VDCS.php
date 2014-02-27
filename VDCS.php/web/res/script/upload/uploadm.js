
$uploadm={ps:{upload:{}},
	
	set:function(k,v){this.ps[k]=v},
	
	// rc5t pop
	init:function(){
		this.initer();
	},
	initer:function(){
		var __othis=this;
		this.jbody=$(".uploadm");
		this.jbar=this.jbody.find(".bars");
		this.jitems=this.jbody.find(".items");
		this.jitemsul=this.jitems.find("ul");
		this.mbar=new ui.MultiBar();
		this.mbar.set("classpop","pop");
		var ela=('upload,web').split(',');
		for(var n in ela){
			var k=ela[n];
			this.jbar.find("."+k).addClass('rc5t');
			var event=function(el){__othis._event(el)};
			this.mbar.add(k,{bar:this.jbar.find("."+k),con:this.jbody.find(".con_"+k),event:event});
		}
		this.mbar.init();
	},
	_event:function(el){
		if(this.juploadx){
			if(el.key!='upload') this.juploadx.hide();
			else this.juploadx.show();
		}
		switch(el.key){
			case 'upload':		this.juploadx=this.juploadx||this._initUpload();break;
			case 'web':		this._initWeb();break;
		}
	},
	_initUpload:function(){
		if(this.juploadx) return this.juploadx;
		var __othis=this;
		var juploadbtn=this.jbody.find(".con_upload .upload");
		var ps=ox({model:"pic",
			//"serveURL":$c.url("root")+"t/index."+$c.EXT+"?p=upload&m=image&x=x",
			sessionid:juploadbtn.attr('sessionid'),
			upload_filetype:"pic",upload_limit:0,queue_limit:10},this.ps.upload);
		callback={complete:function(vars){
			var treeVar=vars.treeVar;
			//var url=treeVar.v("file.urls");
			__othis.plus(null,vars);
		}};
		//alert(this.jbody.find(".con_upload .upload").html());
		this.juploadx=$uploadx.btnElement("pic",juploadbtn,{display:'show',sessionid:ps.sessionid});
		return $uploadx.btnInit("pic",ps,callback);
	},
	_initWeb:function(){
		if(this.jweb) return;
		var __othis=this;
		this.jweb=this.jbody.find(".con_web");
		//this.jweb.find('.url').colorpicker();
		this.jweb.find('.add').click(function(){
			__othis.click_url();
		});
	},
	
	click_url:function(){
		var url=this.jweb.find('.url').val();
		if(!url || url=='http://'){
			this.jweb.find('.url').focus();
			appt.hint(this.jweb.find('[hint]'));
			return;
		}
		this.plus(url);
	},
	
	parse:function(){
		
	},
	
	demo:function(){
		
	},
	
	plus:function(file,vars){
		if(file){
			if(!iso(file)){
				var ext=file.substr(file.lastIndexOf('.')+1);
				file={id:0,name:'pic',url:file,ext:ext,size:0,sizes:'',thumbid:0,thumburl:''};
			}
		}
		else{
			var treeVar=vars.treeVar;
			file={id:treeVar.v("file.id"),name:treeVar.v("file.name"),
				url:treeVar.v("file.urls"),ext:treeVar.v("file.ext"),
				size:treeVar.v("file.size"),sizes:treeVar.v("file.sizes"),
				thumbid:treeVar.v("thumb.id"),thumburl:treeVar.v("thumb.urls"),
			'':''};
		}
		if(this.ps.plus){
			this.ps.plus(file,vars);
			return;
		}
		//dbg.t(file.url);
		this.plusItem(file);
	},
	plusItem:function(file){
		var _src='/images/common/filetype/'+file.ext+'.gif';
		if(inp('png,jpg,jpeg,gif,bmp',file.ext)>0){
			_src=file.thumburl;
			if(!_src) _src=appt.toUploadThumb(file.url,'thumb');
		}
		var html='';
		html='<li _id="'+file.id+'"><a><img src="'+_src+'" alt="'+file.name+'" _id="'+file.id+'" _url="'+file.url+'" _size="'+file.size+'" _sizes="'+file.sizes+'" /></a></li>';
		//alert(html);
		this.jitemsul.append(html);
	},
	
'':''};
