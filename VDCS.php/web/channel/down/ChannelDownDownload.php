<?
class ChannelDownDownload extends ChannelContentViewdata
{
	public $tableData=null;
	
	public function doParse()
	{
		if(!$this->view->isDat()){
			go('./');
			return;
		}
		$this->view->doParse();
		$this->view->doParsePic();
		
		$this->doDownload();
		//if($this->view->isPopedomCheck()) { $this->doDownload(); }
	}
	
	public function doDownload()
	{
		$tmpID=queryi('id');
		$tmpDataID=queryi('dataid');
		
		$this->tableData=$cpo->getDataTable($cfg->getChannel(), $tmpID);
		$tmpURL=$this->tableData->getFieldValue('id','',$tmpDataID,'url');
		if (len($tmpURL)>0){
			if (ins($tmpURL,'://') < 1){
				$tmpServerID=$this->tableData->getFieldValue('id','',$tmpDataID,'sp_serverid');
				if (toNum($tmpServerID)>0){
					$tmpSQL='select top 1 url from dbs_server where channel=\''. $cfg->getChannel() .'\' and id=' . $tmpServerID;
					$tmpServerURL=DB::queryTree($tmpSQL)->getItem('url');
					$tmpURL=$tmpServerURL . $tmpURL;
				}else{
					if (left($tmpURL,1)<>'/') { $tmpURL= appURL('upload') . $tmpURL; }
				}
			}
			$tmpSQL=$cfg->chn->getSQLStruct('data.update');
			$tmpSQL=rd($tmpSQL,'rootid',$cpO->view->id);
			$tmpSQL=rd($tmpSQL,'id',$tmpDataID);
			$tmpSQL=rd($tmpSQL,'dataid',$tmpDataID);
			DB::execBatch($tmpSQL);
		}
		if (len($tmpURL)<5) { $tmpURL='./'; }
		//debugx($tmpURL);
		go($tmpURL);
	}
}
?>