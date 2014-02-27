<?
class ChannelAskMy extends ChannelAskMyBase
{
	public $tableQuestionAsk,$tableQuestionAnswer;
	public function __destruct()
	{
		parent::__destruct();
		unsetr($this->tableQuestionAsk,$this->tableQuestionAnswer);
	}
	
	public function doLoad()
	{
		
	}
	
	public function doParse()
	{
		global $cfg;
		
		$limit=10;
		
		$sqlQuery='q_status=1 and uuid='.$this->ua->id;
		$treeSQL=$this->theme->label->getSQLTree($this->_chn_,'','new',$sqlQuery,$limit);
		//debugTree($treeSQL);
		$sql=$treeSQL->getItem('sql');
		$this->tableQuestionAsk=DB::queryTable($sql);
		$this->tableQuestionAsk=$this->theme->label->toTableFilter($this->_chn_,$this->tableQuestionAsk,$treeSQL,true);
		//debugTable($this->tableQuestionAsk);
		
		$sqlQuery='q_status=1 and q_id in (select rootid from '.$this->AnswerTableName.' where uuid='.$this->ua->id.')';
		$treeSQL=$this->theme->label->getSQLTree($this->_chn_,'','new',$sqlQuery,$limit);
		//debugTree($treeSQL);
		$sql=$treeSQL->getItem('sql');
		$this->tableQuestionAnswer=DB::queryTable($sql);
		$this->tableQuestionAnswer=$this->theme->label->toTableFilter($this->_chn_,$this->tableQuestionAnswer,$treeSQL,true);
		//debugTable($this->tableQuestionAnswer);
		
	}
	
	public function doThemeCache()
	{
		parent::doThemeCache();
		$this->theme->output=CommonTheme::toCacheFilterLoop($this->theme->output,'questionask','cpo.tableQuestionAsk');
		$this->theme->output=CommonTheme::toCacheFilterLoop($this->theme->output,'questionanswer','cpo.tableQuestionAnswer');
	}
	
}
?>