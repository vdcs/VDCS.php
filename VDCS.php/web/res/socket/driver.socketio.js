
var driver_socketio=function(opts){
	this._var=ox({
		route:'base',route_path:'',
		'debug.log':false,
		'debug.t':false,
	'':''},opts);
	this.set=function(key,value){if(typeof(value)!=='undefined')this._var[key]=value;return this._var[key]};
	this.route=function(value){return this.set('route',value)};
	this.routePath=function(value){return this.set('route_path',value)};

	this.setURL=function(value){
		this.debug('setURL',value);
		this.set('server.url',value);
	};
	this.getURL=function(){return this.set('server.url')+this.routePath()};
	
	this._connect=false;this._lock=false;this._total=0;
	this.islock=function(debug){
		if(this._lock && debug){
			this.debug('islock','[locked]');
		}
		return this._lock
	};
	this.isconnect=function(debug){
		if(!this._connect && debug){
			this.debug('isconnect','[no]');
		}
		return this._connect
	};
	this.connectLock=function(lock){
		this._lock=lock;
		this.debug('socket.lock',lock?'locked':'unlock');
	};
	this.connect=function(callback,callback_error){
		var that=this;
		if(this._lock){
			this.debug('connect','locked');
			return null;
		}
		this.connectLock(true);
		if(this._connect){
			this.debug('connect','conntected');
			this.connectLock(false);
			callback(that.set('route'));
			return this.io;
		}
		this.debug('connect','ing..');
		this._connect_callback=callback;
		this._connect_callback_error=callback_error;
		if(!this.io){
			var _url=this.getURL();
			if(!_url){
				this.debug('connect','need server url!');
				this.connectLock(false);
				return null;
			}
			this.debug('connect.server.url',_url);
			this.io=io.connect(_url);
			//dbg.o(this.io.socket);
			this.connectEvent();
		}
		if(this._total>0){
			this.debug('socket','reconnect');
			this.io.socket.reconnect()
		}
		return this.io;
	};
	this.connectEvent=function(){
		var that=this;
		/*
		this.io.socket.of('/service')
			.on('connect_failed', function (reason) {
				console.error('unable to connect to namespace', reason);
			})
			.on('connect', function () {
				console.info('sucessfully established a connection with the namespace');
			});
		*/
		//that._total++;
		this.io.socket.on('error',function(reason){
			that.debug('socket.error',reason||'[error]');
			//handshake unauthorized
			that.connectLock(false);
			if(that._connect_callback_error) that._connect_callback_error(that.set('route'));
		});
		this.io.on('connect',function(data){
			that.debug('socket.on.connect',data||'succeed');
			that._connect=true;
			that.connectLock(false);
			that._total++;
			that.debug('socket.on.connect','total='+that._total);
			if(that._connect_callback) that._connect_callback(that.set('route'));
		});
		this.io.on('connecting',function(data){
			that.debug('socket.on.connecting',data||'[trigger]');
		});
		this.io.on('disconnect',function(data){
			that.debug('socket.on.disconnect',data||'[trigger]');
			that._connect=false;
			that.connectLock(false);
			if(that._connect_callback_error) that._connect_callback_error(that.set('route'));
			if(that._disconnect_callback) that._disconnect_callback(that.set('route'));
		});
		this.io.on('connect_failed',function(reason){
			that.debug('socket.on.connect_failed',reason||'[trigger]');
		});
		this.io.on('error',function(reason){
			that.debug('socket.on.error',reason||'[trigger]');
			//handshake unauthorized
			that.connectLock(false);
			if(that._connect_callback_error) that._connect_callback_error(that.set('route'));
		});
		this.io.on('message',function(message,callback){
			that.debug('socket.on.anything',message);
		});
		this.io.on('anything',function(data,callback){
			that.debug('socket.on.anything',data);
		});
		this.io.on('reconnect_failed',function(data){
			that.debug('socket.on.reconnect_failed',data||'[trigger]');
		});
		this.io.on('reconnect',function(data){
			that.debug('socket.on.reconnect',data||'[trigger]');
		});
		this.io.on('reconnecting',function(nextRetry){
			var data=nextRetry?('Reconnecting in '+nextRetry+' seconds.'):'';
			that.debug('socket.on.reconnecting',data||'[trigger]');
		});
	};
	this.disconnect=function(callback){
		var that=this;
		if(!this.io){
			this.debug('disconnect','socket error!');
			return false;
		}
		if(this._lock){
			this.debug('disconnect','locked');
			return null;
		}
		this.connectLock(true);
		this.debug('disconnect','ing..');
		this._disconnect_callback=callback;
		this.io.disconnect();
		return true;
	};

	this.debug=function(type,data){
		var _instance=this.set('route');
		var _type='['+$time.getNow(true)+']'+'['+(_instance?_instance+'/':'')+type+']';
		if(this.set('debug.log')){
			console.log(_type);
			console.log(data);
		}
		this.debugPut(_type+JSON.stringify(data));
	};
	this.debugPut=function(data){
		this.jdebug_message=this.jdebug_message||$('#debug_message');
		if(this.jdebug_message.length>0) this.jdebug_message.append(data+BR);
		else if(this.set('debug.t')) dbg.t('socket',data);
	};

};

/*

io={
	socket=[object]
	name=
	flags=[object]
	json=[object]
	ackPackets=0
	acks=[object]
	$events=[object]
	on=[function]
	addListener=[function]
	once=[function]
	removeListener=[function]
	removeAllListeners=[function]
	listeners=[function]
	emit=[function]
	$emit=[function]
	of=[function]
	packet=[function]
	send=[function]
	disconnect=[function]
	onPacket=[function]
}

io.socket={
	options=[object]
	connected=false
	open=false
	connecting=true
	reconnecting=false
	namespaces=[object]
	buffer=[object]
	doBuffer=false
	on=[function]
	addListener=[function]
	once=[function]
	removeListener=[function]
	removeAllListeners=[function]
	listeners=[function]
	emit=[function]
	of=[function]
	publish=[function]
	handshake=[function]
	getTransport=[function]
	connect=[function]
	setHeartbeatTimeout=[function]
	packet=[function]
	setBuffer=[function]
	flushBuffer=[function]
	disconnect=[function]
	disconnectSync=[function]
	isXDomain=[function]
	onConnect=[function]
	onOpen=[function]
	onClose=[function]
	onPacket=[function]
	onError=[function]
	onDisconnect=[function]
	reconnect=[function]
}

*/
