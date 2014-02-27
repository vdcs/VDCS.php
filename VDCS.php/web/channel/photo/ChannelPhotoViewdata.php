<?
class ChannelPhotoViewdata extends ChannelContentViewdata
{
	
	public function doLoadPre()
	{
		$this->theme->setPage('view');
		$this->theme->setModule('pics');
	}
	
}
?>