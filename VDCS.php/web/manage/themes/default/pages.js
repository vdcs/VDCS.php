
var pages={
	channel:'',p:'',m:'',x:'x',action:'',mode:'',taxis:'',params:'',
	title:'表单操作',names:'名称',name:'操作',
	

	init:function(){
		$(function(){pages.initer()})
	},
	initer:function(){
		var that=this;
		$('body').on('click','a[target="tab"]',function(){
			return that.linkClick($(this));
		});
		var fatherw=window.parent;
		//dbg.o(fatherw);
		//window.location.href
		//alert($(document).parent())
		//alert(window.parent)
	},

	linkClick:function(ja){
		var fatherw=window.parent;
		if(ja.attr('href') && fatherw && fatherw.mframe){
			fatherw.mframe.tabOpenA(ja);
			return false
		}
	},
	
'':''};

pages.init();


/*

extendo(pages,{
	
'':''});

*/
