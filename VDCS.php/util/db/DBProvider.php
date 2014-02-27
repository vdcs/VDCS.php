<?php
class DBProvider
{
	public $db;
	
	public function __construct($strcfg)
	{
		if ($strcfg['type']) $strcfg['type']='mysql';
		$this->cfg=$strcfg;
		include_once('adodb_'.$strcfg['type'].'.php');
		$cls='drv.'.$strcfg['type'];
		$this->db=new $cls($strcfg);
	}
	
	public function __destruct()
	{
		
	}
	
	public function getQueryNum() { return $this->db->getQueryNum(); }
	
	public function fetch_array($query)
	{
		return $this->db->fetch_array($query);
	}
	
	/*
	##############################
	##############################
	*/
	/*
	[array]	成功
	1		用户名错误
	2		密码错误
	3		锁定
	*/
	public function Manager_query($strusername,$strpassword='')
	{
		$sql="select * from db_employee where e_username='$strusername' limit 0,1";
		$rs=$this->db->rs($sql);
		if ($rs)
		{
			if ($strpassword && $rs['e_password']!=$strpassword) return 2;
			if ($rs['e_lock']==1) return 3;
			return $rs;
		}
		return 1;
	}
	
	
	/*
	##############################
	##############################
	*/
	/*
	0		成功
	1		用户名错误
	2		密码错误
	*/
	public function User_checkLogin($strusername,$strpassword)
	{
		$sql="select u_password from db_user where username='$strusername' limit 0,1";
		$rs=$this->db->rs($sql);
		if ($rs)
		{
			if ($rs['u_password']==$strpassword) return 0;
			else return 2;
		}
		return 1;
	}
	
	/*
	[array]	成功
	1		用户名错误
	2		密码错误
	*/
	public function User_query($strusername,$strpassword='')
	{
		$sql="select * from db_user where username='$strusername' limit 0,1";
		$rs=$this->db->rs($sql);
		if ($rs)
		{
			if ($strpassword && $rs['u_password']!=$strpassword) return 2;
			return $rs;
		}
		return 1;
	}
	
	public function User_add($strdata)
	{
		$sql="insert into db_user(username,u_password,u_realname,u_tim,u_isauth) ";
		$sql.="values('$strdata[username]','$strdata[password]','$strdata[realname]',$strdata[tim],$strdata[isauth])";
		$this->db->exec($sql);
		return $this->db->insert_id();
	}
	
	
	/*
	##############################
	##############################
	*/
	public function Support_add($strdata)
	{
		$sql="insert into db_support_topic(uuid,departmentid,t_topic,t_priority,t_status,t_tim,t_isok,t_re_replierid,t_re_tim,t_re_num,t_re_counter) ";
		$sql.="values($strdata[uuid],$strdata[departmentid],'$strdata[topic]',$strdata[priority],$strdata[status],$strdata[tim],$strdata[isok],$strdata[re_replierid],$strdata[re_tim],$strdata[re_num],$strdata[re_counter])";
		$this->db->exec($sql);
		$tid=$this->db->insert_id();
		$sql="insert into db_support_data(t_id,uuid,staffid,d_remark,d_tim,d_istopic) ";
		$sql.="values($tid,$strdata[uuid],$strdata[staffid],'$strdata[remark]',$strdata[tim],1)";
		$this->db->exec($sql);
		return $tid;
	}
	
	public function Support_reply($strdata)
	{
		$sql="insert into db_support_data(t_id,uuid,staffid,d_remark,d_tim,d_istopic) ";
		$sql.="values($strdata[t_id],$strdata[uuid],$strdata[staffid],'$strdata[remark]',$strdata[tim],0)";
		$this->db->exec($sql);
		$did=$this->db->insert_id();
		if ($strdata[re_replierid]) $sqladd="t_re_num=t_re_num+1,";
		$sql="update db_support_topic set t_status=$strdata[status],t_re_replierid=$strdata[re_replierid],t_re_tim=$strdata[tim],$sqladd t_re_counter=t_re_counter+1 where t_id=$strdata[t_id]";
		$this->db->exec($sql);
		return $did;
	}
	
	public function Support_count_topic($uuid)
	{
		$sql="select count(*) from db_support_topic where uuid=$uuid";
		return $this->db->num($sql);
	}
	
	public function Support_list_query($uuid)
	{
		$sql="select * from db_support_topic where uuid=$uuid order by t_id desc";
		return $this->db->query($sql);
	}
	
	public function Support_topic($tid)
	{
		$sql="select * from db_support_topic where t_id=$tid limit 0,1";
		return $this->db->rs($sql);
	}
	
	public function Support_view_query($tid)
	{
		$sql="select * from db_support_data where t_id=$tid order by d_id asc";
		return $this->db->query($sql);
	}
	
}
?>
