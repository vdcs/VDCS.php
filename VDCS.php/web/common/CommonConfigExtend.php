<?
class CommonConfigExtend
{
	
	
	/*
	########################################
	########################################
	########################################
	########################################
	*/
	public function getStat($t,$p='')
	{
		global $cfg;
		$channel=$cfg->getChannel();
		$re=-1;
		switch($t){
			//case 'counter':
			case 'total':
				$re=$cfg->getData('total.'.$channel);
				break;
			case 'total.view':
				$re=$cfg->getDat($channel.'.total.view');
				if(strlen($re)<1){
					$sql=$cfg->chn->getSQLStruct('sql.total.view');
					if(len($sql)<1) $sql='select sum({$table.px}total_view) from {$table.name}';
					$sql=$cfg->chn->toSQLParse($sql);
					$re=DB::queryInt($sql);
					$cfg->setDat($channel.'.total.view',$re);
				}
				break;
			case 'new':
				$re=$cfg->getDat($channel.'.new');
				if(strlen($re)<1){
					$sql=$cfg->chn->getSQLStruct('sql.total.view');
					if(len($sql)<1) $sql='select count(*) from {$table.name} where {$table.px}tim>={$tim.num}';
					$sql=$cfg->chn->toSQLParse($sql);
					$re=DB::queryInt($sql);
					$cfg->setDat($channel.'.new',$re);
				}
				break;
			case 'online':
				$re=$cfg->getOnline($p);
				break;
			default:
				if($t){
					$re=$cfg->getData($t);
				}
				break;
		}
		return $re;
	}
	
}
?>