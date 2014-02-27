<?
class apiEntry extends apiBase
{
	
	public function auth()
	{
		$this->authMode('sso');
		$this->authedModel('app');
	}
	
	public function parse()
	{
		$this->parseApp();
	}
	public function parseApp()
	{
		$this->addVar('parser','app');
		$this->addVarTree($this->treeApp,'app.');
		$this->setSucceed();
	}
	
}
