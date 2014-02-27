<?
class libPaging
{
	protected $_data=array();
	protected $_listnum=20;		//每页显示数量
	protected $_total=-1;		//总数
	protected $_pagenum=5;		//分页数
	protected $_page=-1;		//当前页
	protected $_pagetotal=1;		//总页次
	protected $_PageBase=0;		//总页次
	
	protected $istemplate=false,$_templatePath='';
	
	
	function __construct()
	{
		$this->_data['cfg.url']='?';
		$this->_data['cfg.url.connect']='';
		$this->_data['cfg.urls']='?page={$page}';
		$this->_data['cfg.tpl']='default';
		
		$this->_data['db.table']='';
		$this->_data['db.id']='id';
		$this->_data['db.field']='*';
		$this->_data['db.order']='';
		
		$this->_data['lang.unit']='';
		$this->_data['lang.names']='notes';
		/*
		$this->_data['lang.page_total']='Total:';
		$this->_data['lang.page_first']='First';
		$this->_data['lang.page_previous']='Previous';
		$this->_data['lang.page_next']='Next';
		$this->_data['lang.page_last']='Last';
		*/
		
		$this->_templatePath='common.config/control/paging/';
	}
	
	public function __destruct()
	{
		
	}
	
	
	/*
	##############################
	##############################
	*/
	public function setListNum($n) { if(is_numeric($n)) $this->_listnum=(int)$n; }
	public function getListNum() { return $this->_listnum; }
	
	public function getNumEnd(){return -1;}
	
	public function setTotal($n) { if(is_numeric($n)) $this->_total=(int)$n; }
	public function getTotal() { return $this->_total; }
	
	public function setPage($n) { if(is_numeric($n)) $this->_page=(int)$n; }
	public function getPage() { return $this->_page; }
	
	public function setPageNum($n) { if(is_numeric($n)) $this->_pagenum=(int)$n; }
	public function getPageNum() { return $this->_pagenum; }
	
	public function getPageTotal() { return $this->_pagetotal; }
	public function getPageBase() { return $this->_PageBase; }
	
	
	/*
	##############################
	##############################
	*/
	public function setConfig($strkey,$strer)
	{
		$this->_data['cfg.'.$strkey]=$strer;
		if($strkey=='url'){
			if(strpos($strer,'{$page}')===false){
				$urlconnect='';
				if(!$strer) { $urlconnect='?'; }
				elseif(strpos($strer,'?')===false) { $urlconnect='?'; }
				else{
					//echo $urlconnect;
					if(substr($strer,-1)!='?' && substr($strer,-1)!='&') { $urlconnect='&'; }
				}
				$this->_data['cfg.url.connect']=$urlconnect;
				$strer.=$urlconnect.'page={$page}';
			}
			$this->_data['cfg.urls']=$strer;
		}
	}
	public function getConfig($strkey) { return $this->_data['cfg.'.$strkey]; }
	
	public function setLang($strkey,$strer) { $this->_data['lang.'.$strkey]=$strer; }
	public function getLang($strkey) { return $this->_data['lang.'.$strkey]; }
	
	public function setDB($strkey,$strer) { $this->_data['db.'.$strkey]=$strer; }
	public function getDB($strkey) { return $this->_data['db.'.$strkey]; }
	
	
	/*
	##############################
	##############################
	*/
	public function getSQL($t='')
	{
		$re='';
		switch($t){
			case 'count':
				$re='select count(*) from '.$this->_data['db.table'];
				if($this->_data['db.query']) $re.=' where '.$this->_data['db.query'];
				break;
			case 'querys':
				$re='select '.$this->_data['db.field'].' from '.$this->_data['db.table'];
				if($this->_data['db.query']) $re.=' where '.$this->_data['db.query'];
				if($this->_data['db.order']) $re.=' order by '.$this->_data['db.order'];
				break;
			case 'limit':
				$re.='limit '.(($this->_page-1)*$this->_listnum).','.$this->_listnum;
				break;
			case 'query':
			default:
				$re='select '.$this->_data['db.field'].' from '.$this->_data['db.table'];
				if($this->_data['db.query']) $re.=' where '.$this->_data['db.query'];
				if($this->_data['db.order']) $re.=' order by '.$this->_data['db.order'];
				$re.=' limit '.(($this->_page-1)*$this->_listnum).','.$this->_listnum;
				break;
		}
		return $re;
	}
	
	
	/*
	##############################
	##############################
	*/
	public function doParse()
	{
		if($this->_total<0 || $this->_page<0){
			if($this->_total<0) $this->_total=DB::queryInt($this->getSQL('count'));
			if($this->_page==-1) { $this->_page=queryi('page'); }
			if($this->_page<1) $this->_page=1;
		}
		if($this->_total>$this->_listnum){
			$this->_pagetotal=self::toPageTotal($this->_total,$this->_listnum);
		}
		if($this->_page>$this->_pagetotal || $this->_page<1) $this->_page=1;
		$this->_PageBase=($this->_page-1)*$this->_listnum;
	}
	
	public static function toPageTotal($total,$listnum=10)
	{
		$re=(int)($total/$listnum);
		if($total>($re*$listnum)) $re++;
		return $re;
	}
	
	
	/*
	##############################
	##############################
	*/
	public function toTable(&$db=null)
	{
		//debugx($this->getSQL('query'));
		if($this->_total>0){
			return DB::queryTable($this->getSQL('query'));
		}
		else{
			return newTable();
		}
	}
	
	public function toString()
	{
		if(!$this->istemplate) $this->loadTemplate('');
		$re=$tmp='';
		$tplitem=$this->_data['tpl.item'];
		$tplitem=rd($tplitem,'urls',$this->_data['cfg.urls']);
		$tplitems=$this->_data['tpl.items'];
		$tplitems=rd($tplitems,'urls',$this->_data['cfg.urls']);
		
		$ppp=(int)($this->_pagenum/2);
		if($ppp*2!=$this->_pagenum) $ppp--;
		$pl=$this->_page-$ppp-1;
		$pr=$pl+$this->_pagenum-1;
		if($pl<1){
			$pr=$pr-$pl+1;
			$pl=1;
			if($pr>$this->_pagetotal) $pr=$this->_pagetotal;
		}
		if($pr>$this->_pagetotal){
			$pl=$pl+$this->_pagetotal-$pr;
			$pr=$this->_pagetotal;
			if($pl<1) $pl=1;
		}
		//debugx($pl.'-'.$this->_page.'-'.$pr);
		
		if($this->_page>1){
			$tmp=$this->_data['tpl.first'];
			$tmp=rd($tmp,'urls',$this->_data['cfg.urls']);
			$tmp=rd($tmp,'page',1);
			$re.=$tmp;
			$tmp=$this->_data['tpl.prev'];
			$tmp=rd($tmp,'urls',$this->_data['cfg.urls']);
			$tmp=rd($tmp,'page',$this->_page-1);
			$re.=$tmp;
		}
		else{
			$tmp=$this->_data['tpl.firsts'];
			$tmp=rd($tmp,'urls',$this->_data['cfg.urls']);
			$tmp=rd($tmp,'page',1);
			$re.=$tmp;
			$tmp=$this->_data['tpl.prevs'];
			$tmp=rd($tmp,'urls',$this->_data['cfg.urls']);
			$tmp=rd($tmp,'page',1);
			$re.=$tmp;
		}
		for ($i=$pl;$i<=$pr;$i++){
			$tmp=($i==$this->_page)?$tplitems:$tplitem;
			$tmp=rd($tmp,'page',$i);
			$re.=$tmp;
		}
		if($this->_page==$this->_pagetotal){
			$tmp=$this->_data['tpl.nexts'];
			$tmp=rd($tmp,'urls',$this->_data['cfg.urls']);
			$tmp=rd($tmp,'page',$this->_pagetotal);
			$re.=$tmp;
			$tmp=$this->_data['tpl.lasts'];
			$tmp=rd($tmp,'urls',$this->_data['cfg.urls']);
			$tmp=rd($tmp,'page',$this->_pagetotal);
			$re.=$tmp;
		}
		else if($this->_page<$this->_pagetotal){
			$tmp=$this->_data['tpl.next'];
			$tmp=rd($tmp,'urls',$this->_data['cfg.urls']);
			$tmp=rd($tmp,'page',$this->_page+1);
			$re.=$tmp;
			$tmp=$this->_data['tpl.last'];
			$tmp=rd($tmp,'urls',$this->_data['cfg.urls']);
			$tmp=rd($tmp,'page',$this->_pagetotal);
			$re.=$tmp;
		}
		$tmp=$this->_data['tpl.frame'];
		$tmp=rd($tmp,'items',$re);
		$tmp=rd($tmp,'total',$this->_total);
		$tmp=rd($tmp,'listnum',$this->_listnum);
		$tmp=rd($tmp,'pagetotal',$this->_pagetotal);
		$tmp=rd($tmp,'page',$this->_page);
		$tmp=rd($tmp,'unit',$this->_data['lang.unit']);
		$tmp=rd($tmp,'names',$this->_data['lang.names']);
		$tmp=rd($tmp,'url',$this->_data['cfg.url']);
		$tmp=rd($tmp,'urls',$this->_data['cfg.urls']);
		return $tmp;
	}
	
	
	/*
	########################################
	########################################
	*/
	public function loadTemplate($style)
	{
		$this->istemplate=true;
		if(!$style && !$this->_templatePath) $style='__text';
		switch($style){
			case'__table':
				$html='';
				$html.='<table cellspacing=1 cellpadding=4 class=paging>';
				$html.='<tr>';
				$html.='<td class=bar>{$total}{$unit}{names}</td>';
				$html.='<td class=bar>{$page}/{$pagetotal}</td>';
				$html.='{$items}';
				//html.='<td class=btn><input type=text name=_paging size=5 maxlength=10></td>';
				$html.='</tr>';
				$html.='</table>';
				$this->_data['tpl.frame']=$html;
				$this->_data['tpl.first']='<td class=td><a href="{$url}">First</a></td>';
				$this->_data['tpl.firsts']='';
				$this->_data['tpl.prev']='<td class=td><a href="{$url}page={$pagenow}">Previous</a></td>';
				$this->_data['tpl.prevs']='';
				$this->_data['tpl.item']='<td class=td><a href="{$url}page={$pagenow}">{$pagenow}</a></td>';
				$this->_data['tpl.items']='<td class=tds><strong><u>{$pagenow}</u></strong></td>';
				$this->_data['tpl.next']='<td class=td><a href="{$url}page={$pagenow}">Next</a></td>';
				$this->_data['tpl.nexts']='';
				$this->_data['tpl.last']='<td class=td><a href="{$url}page={$pagetotal}">Last</a></td>';
				$this->_data['tpl.lasts']='';
				break;
			case'__text':
				$this->_data['tpl.frame']='Total: {$total}{$unit}{names},{$page}/{$pagetotal},{$items}';
				$this->_data['tpl.first']='<a href="{$url}">First</a> ';
				$this->_data['tpl.firsts']='';
				$this->_data['tpl.prev']='<a href="{$url}page={$pagenow}">Previous</a> ';
				$this->_data['tpl.prevs']='';
				$this->_data['tpl.item']='<a href="{$url}page={$pagenow}">{$pagenow}</a> ';
				$this->_data['tpl.items']='<strong><u>{$pagenow}</u></strong> ';
				$this->_data['tpl.next']='<a href="{$url}page={$pagenow}">Next</a> ';
				$this->_data['tpl.nexts']='';
				$this->_data['tpl.last']='<a href="{$url}page={$pagetotal}">Last</a> ';
				$this->_data['tpl.lasts']='';
				break;
			default:
				if(!$style) $style='default';
				if(right($this->_templatePath,1)!='/') $this->_templatePath.='/';
				$stylefile=$this->_templatePath.$style;
				//debugx($stylefile);
				$this->setTemplateTree(VDCSDTML::getConfigCacheTree($stylefile));
				break;
		}
	}
	
	public function setTemplatePath($path) { $this->_templatePath=$path; }
	public function setTemplateTree($treeTpl=null)
	{
		if(!isTree($treeTpl)){$this->loadTemplate('_text');}
		else{
			$treeTpl->doBegin();
			for($i=0;$i<$treeTpl->getCount();$i++){
				$this->_data[$treeTpl->getItemKey()]=$treeTpl->getItemValue();
				$treeTpl->doMove();
			}
		}
		$this->istemplate=true;
	}
	
	
	/*
	########################################
	########################################
	*/
	public static function toShortString($total,$list_num,$len,$tpl_item,$tpl)
	{
		$re='';
		$page_total=($total/$list_num);
		if($total>($page_total*$list_num)) $page_total++;
		if($page_total>1){
			if(ins($tpl_item,'{$page}')==-1) $tpl_item.='{$page}';
			if(ins($tpl,'{$items}')==-1) $tpl.='{$items}';
			for($i=2;$i<=$page_total;$i++){
				$re.='<a href="'.rd($tpl_item,'page',$i).'">'.$i.'</a> ';
			}
			$re=trim($re);
			$re=rd($tpl,'items',$re);
		}
		return $re;
	}
	
	
	/*
	@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
	@@@@@@@@@@@@@ DTML Dispose @@@@@@@@@@@@@
	@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
	*/
	public function toDTML($re)
	{
		if(ins($re,'<paging:')>0) $re=r($re,'<paging:string>',$this->toString());
		if(ins($re,'<paging:')>0){
			$re=r($re,'<paging:listnum>',$this->_listnum);
			//re=r($re,'<paging:numend>',$this->_NumEnd);
			$re=r($re,'<paging:total>',$this->_total);
			$re=r($re,'<paging:pagenum>',$this->_pagenum);
			$re=r($re,'<paging:page>',$this->_page);
			$re=r($re,'<paging:pagetotal>',$this->_pagetotal);
			$re=r($re,'<paging:pagebase>',$this->_PageBase);
		}
		return $re;
	}
}
