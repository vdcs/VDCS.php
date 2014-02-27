<?
class ChannelPhotoViewpics extends ChannelContentViewData
{
	
	public function doLoadPre()
	{
		$this->theme->setPage('view');
		$this->theme->setModule('pics');
	}
	
}
?>