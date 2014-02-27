
widget.icart={
	selector:'#headbar .cart',
	serveRoute:'shop/cart',

	initerHeader:function(){
		var that=this;
		this.jwrap=$(this.selector+' dd');
		var _url=app.serve(this.serveRoute,'action=list');
		$ajax({url:_url,value:'xml',ready:function(o){that.parseAsync(o)},error:false})
	},
	parseAsync:function(xml){
		var that=this;
		var maps=$util.toMapByXML(xml);
		this.treeVar=maps.getItemTree('var');
		this.tableList=maps.getItemTable('item');
		var _status=this.treeVar.v('status');
		if(_status!='succeed'){
			this.jwrap.find('.loading p span').html('数据错误！');
			return
		}
		$(this.selector+' a:first i').text(this.tableList.row());
		this.jwrap.html($('#tpl_cart_box').html());
		var jbox=this.jwrap.find('.cart_box');
		var jcont=jbox.find('ul');
		var _price=0;
		var tpl_item=$('#tpl_cart_box_item').html();
		this.tableList.begin();
		for(var i=1;i<=this.tableList.row();i++){
			var treeItem=this.tableList.getItemTree();
			treeItem.extractJson('settings');
			var apptype=treeItem.v('apptype');
			var oe=(i+1)%2+1;
			treeItem.addItem('_oe_',oe);
			treeItem.addItem('_sn_',i);
			_price+=treeItem.vn('price');
			var _html=tpl_item;
			_html=that.refille($(_html),treeItem).outerHTML();
			_html=$dtml.filterItem(_html,treeItem);
			//_html=$dtml.filterItem(_html,treeItem);
			var jitem=$(_html).appendTo(jcont);
			this.tableList.move();
		}
		jbox.find('h5 .money i').text($code.toPrice(_price));
	},
	refille:function(jitem,treeItem){
		var that=this;
		jitem.find('[data-refill]').each(function(){
			var jo=$(this);
			var selector=r(jo.attr('data-refill'),'{val}',treeItem.v(jo.attr('data-refill-field')));
			that.refillei(jitem,jo,selector);
		});
		//alert(jitem.outerHTML());
		return jitem
	},
	refillei:function(jitem,jo,selector){
		var _html=$('.cart_refill_box[data-refill="'+selector+'"]').html();
		if(_html) jo.append(_html);
	},
	
'':''};
