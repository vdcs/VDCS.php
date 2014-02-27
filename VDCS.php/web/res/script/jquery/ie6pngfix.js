
(function($){
	//alert($.browser.version);
	var pic_space="/images/space.gif";
	if($.browser.msie && $.browser.version=="6.0"){
		$("img[src*=png]").each(function(){
			var s=this.src;
			var iWidth=this.width;
			var iHeight=this.height;
			this.src=pic_space;
			this.width=iWidth;
			this.height=iHeight;
			this.style.filter="progid:DXImageTransform.Microsoft.AlphaImageLoader(src="+s+", sizingmethod=scale)";
		});
		$("*").each(function(){
			var s=$(this).css('background-image');
			if(s.indexOf(".png")!=-1){
				s=s.split('url("')[1].split('")')[0];
				$(this).css('background-image', 'none');
				this.style.filter="progid:DXImageTransform.Microsoft.AlphaImageLoader(src="+s+", sizingmethod=crop)";
			}
		});
		$("input[src*=png]").each(function(){
			var s=this.src;
			var iWidth=this.width;
			var iHeight=this.height;
			this.src=pic_space;
			this.width=iWidth;
			this.height=iHeight;
			this.style.filter="progid:DXImageTransform.Microsoft.AlphaImageLoader(src="+s+", sizingmethod=scale)";
		});
	}
})(jQuery);
