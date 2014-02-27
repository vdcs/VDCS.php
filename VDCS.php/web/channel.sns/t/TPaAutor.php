<?
class TPaAutor extends ChannelPaBase
{
	
	public function doParse(&$that)
	{
		$this->theme->setDir('_page');
		$this->theme->setPage('blank');

		$this->issave=queryx('save')=='yes'?true:false;
		
		switch(query('action')){
			case 'move':		$this->parseMove($that);break;
			default:		$this->parseAutor($that);break;
		}
	}
	
	public function parseAutor($that)
	{
		if(!$this->ua->isLogin()){
			debugx('nologin');
			return;
		}
		if(!$this->ua->id>20000){
			debugx('nopower');
			return;
		}
		
		$this->pathres=appDirPath('upload/autor/');
		//debugx($this->pathres);
		clearFileCaches();
		$tableFile=utilFile::getDirTable($this->pathres);
		debugTable($tableFile);
		debugx('Autor file: '.$tableFile->getRow());
		debugx(dcsExecTime());
		
		$this->dirsave='/upload/web/';
		$this->pathsave=appDirPath('upload/web/');
		$tableFile->doBegin();
		while($tableFile->isNext()){
			$ispost=false;$iserr=false;
			$path=$tableFile->iv('path');
			$message=getPathPart($path,'name');
			if(!$this->issave){
				debugx($path);
				continue;
			}
			if($tableFile->iv('type')=='file'){
				$medias=array();
				$resave=self::doSaveHash($path,$this->pathsave,$savepath,$savefile);
				//debugx($resave.', '.$savefile);
				if($resave>0){
					array_push($medias,['pic',$this->dirsave.$savefile]);
					$ispost=true;
				}
				else{
					$iserr=true;
					
				}
			}
			elseif($tableFile->iv('type')=='folder'){
				$medias=array();
				$tableFileSub=utilFile::getDirTable($path);
				//debugTable($tableFileSub);
				$tableFileSub->doBegin();
				while($tableFileSub->isNext()){
					$subpath=$tableFileSub->iv('path');
					$resave=self::doSaveHash($subpath,$this->pathsave,$savepath,$savefile);
					//debugx($resave.', '.$savefile);
					if($resave>0){
						array_push($medias,['pic',$this->dirsave.$savefile,$tableFileSub->iv('name')]);
						$ispost=true;
					}
					else{
						$iserr=true;
					}
				}
				if(!$iserr) utilFile::doDirDel($path);
			}
			if($ispost) $this->autorPost($message,$medias);
			if($iserr) debugx('error: '.$path);
		}
		debugx(dcsExecTime());
		
	}
	
	public function autorPost($message,$medias)
	{
		debugx($message);
		debuga($medias);
		$type=1;
		$treeData=newTree();
		$treeData->addItem('type',$type);
		$treeData->addItem('message',$message);
		$treeData->addItem('medias',$medias);
		$treeData->addItem('tagids',query('tagids'));
		$_status=TMlogPost::send($this->ua,$treeData);
		return $_status;
	}
	
	public static function doSaveHash($file,&$basepath,&$savepath='',&$savefile='')
	{
		if(!isDir($basepath)) return -1;
		utilIO::dirBuildHash($basepath);
		$filehash=utilCoder::toMD5($file);
		$filetype=getPathPart($file,'ext');
		$hashdir=strtolower(substr($filehash,0,2)).'/';
		//$hashdir='';
		$savefile=$hashdir.$filehash.'.'.$filetype;
		$savepath=$basepath.$savefile;
		//if(!isFile($file)) debugx($file.' -> '.$savepath);
		$re=0;
		if(isFile($file)){
			$re=utilFile::doFileMove($file,$savepath);
			//$re=true;
		}
		elseif(DCS::isURL($file)){
			$re=VDCSHTTP::curlSave($file,[],$savepath);
		}
		return $re;
	}
	
	
	public function parseMove($that)
	{
		$this->pathsave=appDirPath('upload/web/');
		
		$id=queryi('id');
		$sql='select * from db_mlog where id>'.$id.' order by id asc limit 0,100';
		if(!$this->issave) debugx($sql);
		$tableData=DB::queryTable($sql);
		//debugTable($tableData);
		$tableData->doBegin();
		while($tableData->isNext()){
			$medias=$tableData->iv('medias');
			debugx($medias);
			$_pattern='/web\\\\\/([\w\.]*)\"/ies';
			//debugx(utilRegex::toPattern($_pattern));
			$_matches=utilRegex::toMatches($medias,$_pattern);
			//debuga($_matches);
			//debugx(count($_matches[1]));
			$medias2=$medias;
			for($m=0;$m<count($_matches[0]);$m++){
				$file=$_matches[1][$m];
				$file2=substr($file,0,2).'/'.$file;
				//debugx($file);
				$medias2=r($medias2,$file,$file2);
				if($this->issave) utilFile::doFileMove($this->pathsave.$file,$this->pathsave.$file2);
			}
			if(count($_matches[0])>0){
				debugx($medias2);
				$sql='update db_mlog set medias='.DB::q($medias2,1).' where id='.$tableData->ivi('id');
				if(!$this->issave) debugx($sql);
				if($this->issave) DB::exec($sql);
			}
			//break;
		}
		debugx(dcsExecTime());
	}
	
}
?>