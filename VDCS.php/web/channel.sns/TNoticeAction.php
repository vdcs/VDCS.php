<?
class TNoticeAction extends UcNoticeAction
{
	
	//rootid,talkid,rootuid
	public static function countComment($ua,$params)
	{
		//dcsLog('uuid',$params['rootuid']);
		if($params['rootuid']) self::sets($ua,'{tpx}total_comment={tpx}total_comment+1,{tpx}new_comment={tpx}new_comment+1',toi($params['rootuid']));
	}
	//rootid,talkid,replyuid
	public static function countReply($ua,$params)
	{
		//TNoticeAction::sets($ua,'{tpx}new_reply={tpx}new_reply+1',$params['replyuid']);
		if($params['replyuid']) self::sets($ua,'{tpx}total_comment={tpx}total_comment+1',toi($params['replyuid']));
	}
	//rootid,talkid,atuid
	public static function countAt($ua,$params)
	{
		//dcsLog('atuid',$params['atuid']);
		if($params['atuid']) self::sets($ua,'{tpx}new_at={tpx}new_at+1',toi($params['atuid']));
	}
	
	//评论文章时的at
	public static function countAtComment($ua,$params)
	{
		//dcsLog('atuid',$params['atuid']);
		if($params['atuid']) self::sets($ua,'{tpx}new_atcomment={tpx}new_atcomment+1',toi($params['atuid']));
	}
	
	public static function queryStat($ua,$fields='',$uid=1)
	{
		if(!$fields) $fields='{tpx}total_follow,{tpx}total_fans,{tpx}total_post,{tpx}total_relay,{tpx}total_comment,{tpx}total_fav,{tpx}total_new,{tpx}new_fans,{tpx}new_relay,{tpx}new_comment,{tpx}new_reply,{tpx}new_inbox,{tpx}new_at,{tpx}new_atcomment,{tpx}new_notice';
		$reTree=$ua->queryTree($fields,$uid);
		return $reTree;
	}
	
	/*
	public static function set($ua,$fields,$values,$uid=1)
	{
		return $ua->set($fields,$values,$uid);
	}
	public static function setx($ua,$fields,$values=0,$uid=1)
	{
		$ua->setData($fields,$value);
		if(is_string($fields)){
			$field='{tpx}'.$fields;
			$field=r($field,'{tpx}',$ua->TablePX);
			$fields=[$field];
			$values=[$field=>$values];
		}
		return self::set($ua,$fields,$values,$uid);
	}
	public static function sets($ua,$sets,$uid=1)
	{
		return $ua->update($sets,$uid);
	}
	*/
	
}
