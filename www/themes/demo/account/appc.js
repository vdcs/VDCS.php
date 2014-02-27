
extendo(appc,{
	init:function(){var that=this;$(function(){that.initer()})},
	initer:function(){
		this.nav.initer();
		var that=this;
		this.jform=$f.oo('frm_post');
		if(this.jform.length){
			this.jform.finde('submit').click(function(){
				var jthis=$(this);
				if(jthis.attr('_submit')) return;
				jthis.attr('_submit','yes');
				jthis.find('span').text('保存中...');
				that.jform.submit();
			});
		}
	},
	
	nav:extend(app.nav,{
		
	'':''}),
	
	
	list:{q:new VDCS.Queues(),
		add:function(k){
			this.q.add(k);
		},
		init:function(){var that=this;$(function(){that.initer()})},
		initer:function(){
			if(that.q.count()<1) $('.list-items-nodata').show();
		},
	'':''},
	
	
	doAction:function(s){
		$f.setValue('frm_list._select_handle',s);
		$f.setValue('frm_list._backurl',$b.getURL());
		return $formx.doSelectClick(inPart('del,delete',s)<1?0:1);
	},

'':''});
appc.init();
