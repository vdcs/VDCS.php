<?
class Oauths
{
	const TABLE_NAME			= 'dbu_oauths';
	const TABLE_CODE_NAME			= 'dbu_oauths_code';
	const TABLE_TOKEN_NAME			= 'dbu_oauths_token';
	
	
	public static function toEncrypt($enstr,$type='md5')
	{
		return utilCoder::toMD5($enstr,1);
	}
	public static function toEncryptCode($enstr,$type='md5')
	{
		return utilCoder::toMD5($enstr);
	}
	public static function toEncryptKey($enstr,$type='md5')
	{
		return utilCoder::toMD5($enstr);
	}
	
}
