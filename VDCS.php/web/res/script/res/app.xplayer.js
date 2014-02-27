
var xplayer={};

extendo(xplayer,{

	initer:function(selector,opt){
		var jplayer=$(selector||'.xplayer');
		this.parserVideo(jplayer.attrd('url'),function(treeInfo){
			if(!treeInfo){
				jplayer.html('<div class="error">Loaded Error!</div>');
				return;
			}
			jplayer.html(treeInfo.v('flash'));
		});
	},
	
	parserVideo:function(url,callback){
		var that=this;
		var _url=app.serve('c/parser','action=video&url='+url);
		//dbg.t(_url);
		if(isdebug('video')) dbg.t('url',_url);
		$ajax({url:_url,value:'xml',ready:function(o){that.parserVideoAnsyc(o,callback)},error:true})
	},
	parserVideoAnsyc:function(xml,callback){	
		//alert(xml);
		var maps=$util.toMapByXML(xml);
		var treeVar=maps.getItemTree('var');
		var treeInfo=false;
		if(treeVar.v('status')=='succeed'){
			treeInfo=newTree();
			treeInfo.add('res',treeVar.v('info.res'));
			treeInfo.add('img',treeVar.v('info.img'));
			treeInfo.add('title',treeVar.v('info.title'));
			treeInfo.add('url',treeVar.v('info.url'));
			treeInfo.add('swf',treeVar.v('info.swf'));
			treeInfo.add('flash',treeVar.v('info.flash'));
		}
		if(callback) callback(treeInfo);
	},
'':''});
