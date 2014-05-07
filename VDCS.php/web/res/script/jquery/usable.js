


	$.datepickerBind=function(ele,bind){
		var akey='datepicker';
		var _bind=function(jo,real){
			//alert(jo.outerHTML());
			if(!jo.attr('bind-'+akey)){
				jo.attr('bind-'+akey,'yes');
				jo.on('datepicker',function(){
					dbg.t(1);
					var jthis=$(this),format=jthis.attr(akey)||'yyyy-mm-dd';
					var jpicker=jthis.datepicker({
						format:format,
						onRender: function(date) {
							return date.valueOf();		// < now.valueOf() ? 'disabled' : ''
						}
					}).on('changeDate', function(ev) {
						jpicker.hide();
						if(jthis.attrd('next')) $(ele).find(jthis.attrd('next')+'['+akey+']').trigger('focus');
					}).data('datepicker');
				});
				if(real) jo.trigger('datepicker');
				else{
					jo.on('focus',function(){
						$(this).trigger('datepicker');
						return false
					});
				}
			}
		};
		if(bind) $(ele).find('input['+akey+']').each(function(){_bind($(this))});
		else $(ele).on('focus','input['+akey+']',function(){_bind($(this),true)});
	};
	$.datepickerBind(document);

