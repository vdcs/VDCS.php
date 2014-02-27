<?
class ChannelCommonBaseMessage extends ChannelCommonBase
{
	protected $TableName	= 'dbc_';
	protected $TablePX	= 'm_';
	protected $FieldID	= 'm_id';
	protected $TableFields	= [
			'base:add'	=> '
				channel,rootid,dataid,uurc,uuid,
				m_names,m_location,m_marks,m_prop1,m_prop2,m_prop3,m_prop4,m_prop5,
				m_nickname,m_befrom,m_im,m_im1,m_im2,m_im3,m_im4,m_im5,
				m_realname,m_email,m_mobile,m_call,
				m_company,m_url,m_address,m_postcode,m_phone,m_fax,
				m_topic,m_remark,
				sp_ip,sp_agent,m_status,m_tim
'
];
	
}
?>