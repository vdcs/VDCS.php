
ui.uploadx={
	///p.php?cp=common&x=x&p=upload&m=&x=x&channel=news&sorts=&types=
	selector:'a[href="#uploadx"]',
	initer:function(jwrap){
		var that=this;
		//jwrap.find('a[href="#upload"]').click();
		var _load=function(e){
			if(ui.lock.is('upload')) return;ui.lock.en('upload')
			e.preventDefault()
			var jo=$(this);
			ui.tipload(jo);
			that.loader(function(){
				jwrap.off('mouseenter',that.selector,_load);
				ui.tipload(jo,'remove');
				ui.mini.show('Uploadx loaded!',{timer:1});
				//dbg.t('Uploadx loaded!');
				that.bind(jwrap);
			});
			return false
		};
		jwrap.on('mouseenter',that.selector,_load);
	},
	loader:function(cnext){
		var that=this;
		if(this.loader_status>0){
			if(this.loader_status>1) cnext();
			return
		}
		this.loader_status=1;
		var urlRes=[];
		urlRes.push($c.url("script")+'upload/uploadx.js?'+$c.vrt(1));
		$.include(urlRes,function(){
			that.loader_status=2;
			cnext()
		})
	},
	bind:function(jwrap){
		var that=this;
		var jai=null,juploadx=null;
		var _bind=function(ja,once){
			if(ja==jai) return;jai=ja;
			//alert(ja.attrd('vars'));
			var sets=s2o(ja.attrd('vars'),'&','=');
			//dbg.o(sets);
			var opt={upload_channel:sets.channel,upload_sorts:sets.sorts,upload_filetype:sets.filetype,queue_limit:1};
			var callback={};
			callback.complete=function(vars){
				var treeVar=vars.treeVar;
				that.plus(jwrap,ja,treeVar,sets);
			};
			if(juploadx) juploadx.remove();
			juploadx=$uploadx.btnElement(null,ja,{inbody:true,display:'show',sessionid:jwrap.attr('sessionid')});
			$uploadx.btnInit(juploadx,opt,callback);
		};
		//jwrap.find(that.selector).each(function(){_bind(ja)});
		jwrap.find(that.selector).on('mouseenter',function(e){
			e.preventDefault()
			_bind($(this),true)
			return false
		});
		/*
		jwrap.on('click',that.upload_selector,function(e){
			e.preventDefault()
			alert($(this).attrd('upload'));
			return false
		});
		*/
	},
	plus:function(jwrap,ja,treeVar,sets){
		var url=treeVar.v('file.urls'),ext=treeVar.v('file.ext'),realnames=treeVar.v('file.names'),
			filetype=treeVar.v('filetype')||sets.filetype,
			fileinput=treeVar.v('fileinput')||sets.fileinput,
			inputtype=treeVar.v('inputtype')||sets.inputtype;	//file.id
		//alert(ja.attrd('vars'));
		//dbg.o(sets);
		//dbg.o(treeVar);
		var _type=filetype||inputtype;
		//alert('_type='+_type+','+filetype+','+inputtype);
		if(_type=='pic' || _type=='affix'){
			var jinput=jwrap.finder(':input[name="'+fileinput+'"]');
			if(jinput){
				jinput.val(url);
			}
		}
		else{
			if(fileinput.substring(0,2)=='i.'){
				var value=dcs.editor.toElementContent(url,{ext:ext,realnames:realnames});
				if(isdebug('data')) alert(fileinput.substring(2)+'('+sets.valuemode+'): '+value);
				dcs.editor.setValue(fileinput.substring(2),value,sets.valuemode)
			}
		}
		//upid
		var jupid=jwrap.findin('_upid'),upid=jupid.val();
		jupid.val(upid+','+treeVar.v('file.id'));
	},
'':''};

/*
sets:
channel=article
sorts=
filetype=pic
filename=20131118162042myjhq
fileinput=a_pic
filesize=
thumbname=
thumbinput=
formname=
valuemode=
inputtype=

treeVar:
mode=
format=
channel=article
sorts=
types=
uurc=user
uuid=0
savedir=article/201311/
filename=20131118162409mqrrr
filetype=
maxsize=2048
linkmode=again
formname=
fileinput=
thumbinput=
valuemode=
inputtype=
total.max=10000
file.ext=png
file.size=66146
file.sizes=64.6KB
file.urls=/upload/article/201311/20131118162409mqrrr.png
file.name=20131118162409mqrrr.png
file.names=login-24.png
file.id=26
thumb.id=-1
status=succeed
message=[succeed]
*/
