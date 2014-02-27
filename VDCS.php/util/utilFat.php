<?
class utilFat
{
	
	public static function getFileTree($path)
	{
		$path=appFilePath($path);
		//debugx($path);
		$content=getFileContent($path);
		return self::toTree($content);
	}
	
	public static function toTree($content)
	{
		$reTree=newTree();
		$treeCont=utilString::toTree($content,'!##################!','!==================!');
		$treeCont->doBegin();
		for($t=1;$t<$treeCont->getCount();$t++){
			$fielda=toSplit(trim($treeCont->getItemKey()),',');
			foreach($fielda as $field){
				if($field) $reTree->addItem($field,$treeCont->getItemValue());
			}
			$treeCont->doMove();
		}
		return $reTree;
	}

}
?>