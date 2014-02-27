<?
class ChannelCommonCommProberX extends ChannelCommonBaseX
{

	public function doLoad()
	{
		$this->loop=queryi('loop');
		if($this->loop<1) $this->loop=1000;
		$this->addVar('loop',$this->loop);
	}

	protected function addStat($key,$value)
	{
		$this->addVar('stat.'.$key,timerExec());
	}

	public function parse()
	{
		$this->parseBase();
	}
	public function parseBase()
	{
		timerBegin();
		for($n=0;$n<$this->loop;$n++){
			$id=queryi('id');
			$value=query('value');
			$sort=queryx('sort');
			$content=post('content',10000);
			$password=request('password');
		}
		$this->addStat('request',timerExec());
		//##########
		
	}
	
	public function parseIo()
	{
		timerBegin();
		for($n=0;$n<$this->loop;$n++){
			$content=getFile(appFilePath('common.config/app'));
		}
		$this->addStat('file.read',timerExec());
		//##########
		$filepath=appFilePath('common.config/app.test');
		timerBegin();
		for($n=0;$n<$this->loop;$n++){
			doFileWrite($filepath,$content);
		}
		$this->addStat('file.write',timerExec());
		//##########
		doFileDel($filepath);
	}
	
	public function parseConfig()
	{
		timerBegin();
		for($n=0;$n<$this->loop;$n++){
			$treeApp=VDCSDTML::getConfigTree('common.config/app');
		}
		$this->addStat('config.tree',timerExec());
		//##########
	}
	
	public function parseDb()
	{
		timerBegin();
		for($n=0;$n<$this->loop;$n++){
			$treeConfig=DB::queryTree('select * from dbs_config');
		}
		$this->addStat('db.tree',timerExec());
		//##########
		timerBegin();
		for($n=0;$n<$this->loop;$n++){
			$tableConfig=DB::queryTable('select * from dbs_config');
		}
		$this->addStat('db.table',timerExec());
		//##########
	}
	
}
