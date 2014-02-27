<?
class ChannelTMlogActionX extends ChannelTBasePostX
{
	
	protected function parseLike()
	{
		$id=queryi('id');
		$this->addVar('id',$id);
		$_status=TMlogAction::like($this->ua,$id);
		switch($_status){
			case 1:			$this->setSucceed();break;
			case 2:			$this->setStatus('already');break;
			default:		$this->setStatus('failed');break;
		}
	}
	
}
