<?
class UIPacker
{
	
	public static function toPackerJS($s,$head='',$mode='Normal')
	{
		//include_once('JavaScriptPacker.php');
		$packer=new JavaScriptPacker($s, 'Normal', true, false);
		$prefix='';$postfix='';
		//$prefix=NEWLINE;$postfix=NEWLINE;
		if($head) $prefix=getFileContent(_BASE_PATH.'r/head'.(($head!='def')?('.'.$head):'').'.txt');
		return $prefix.trim($packer->pack()).$postfix;
	}
	
}
