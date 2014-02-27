<?
class ChannelCommonUpdaterX extends ChannelCommonBaseX
{
	
	protected function parseTime()
	{
		$table=queryx('table');
		$mode=queryx('mode');
		$this->addVar('table',$table);
		$this->addVar('mode',$mode);
		$tables=$this->getTableStruct($table);
		if(!$tables){
			$this->setStatus('error','table');
			return;
		}
		//debuga($tables);
		switch($mode){
			case 'init':
				$sql='update '.$tables[0].' set `'.$tables[2].'`=0';
				DB::exec($sql);
				break;
			default:
				$sql='select `'.$tables[1].'`,`'.$tables[3].'` from '.$tables[0].' where `'.$tables[2].'`<1 order by `'.$tables[1].'` asc limit 0,100';
				$tableList=DB::queryTable($sql);
				//debugTable($tableList);
				$this->addVar('row',$tableList->row());
				$tableList->begin();
				while($tableList->next()){
					$id=$tableList->getItemValue($tables[1]);
					$time=$tableList->getItemValue($tables[3]);
					$tim=VDCSTime::toNumber($time);
					//debugx($id.'='.$time.','.$tim.','.DCS::timec($tim));
					$sql='update '.$tables[0].' set `'.$tables[2].'`='.$tim.' where `'.$tables[1].'`='.$id;
					DB::exec($sql);
				}
				break;
		}
		$this->setSucceed();
	}
	protected function getTableStruct($table)
	{
		$tables=[
			'article' => ['db_article','a_id','a_tim','a_prop5'],
			'news' => ['db_news','a_id','a_tim','a_prop5'],
			'down' => ['db_down','c_id','c_tim','c_prop5'],
			'shop_product' => ['db_shop_product','p_id','p_tim','p_prop5'],
			'shop_order' => ['db_shop_order','id','tim','explain'],
			'announce' => ['dbc_announce','id','tim','prop5'],
			'user' => ['db_user','uid','tim','prop5'],
		];
		return $tables[$table];
	}

}
