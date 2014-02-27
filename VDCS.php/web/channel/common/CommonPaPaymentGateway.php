<?
class CommonPaPaymentGateway extends ChannelPaBase
{
	
	public function doParse(&$that)
	{
		$sp=queryx('sp');
		$tradeno=PaymentAction::tradeno();
		
		$params=newTree();
		$params->addItem('tradeno',$tradeno);
		$params->addItem('money',utilCode::toPrice(queryn('money')));
		$params->addItem('module',queryx('module'));
		$params->addItem('type',queryx('type'));
		$params->addItem('value',queryx('type'));
		$params->addItem('name',query('name'));
		$params->addItem('desc',query('desc'));
		$params->addItem('linkurl',query('linkurl'));
		
		if(!$params->getItem('desc')) $params->addItem('desc','支付 '.$params->getItem('money').' 元');
		
		$treeData=newTree();
		$treeData->addItem('sp',$sp);
		$treeData->doAppendTree($params);

		//网关处理
		switch($sp){
			case 'alipay':
				$params->addItem('notify_url',DCS::url($this->cfg->toLinkURL('pami','p=payment&m=alipay&mi=notify')));		//,APP_BASEURL
				$params->addItem('return_url',DCS::url($this->cfg->toLinkURL('pami','p=payment&m=alipay&mi=return')));
				//debugTree($params);
				$sp_url=PaymentAlipayAction::buildTransactionURL($params);
				break;
			case 'yeepay':
				$params->addItem('return_url',DCS::url($this->cfg->toLinkURL('pami','p=payment&m=yeepay&mi=return')));
				$sp_url=PaymentYeepayAction::getTransactionURL();
				$sp_form=PaymentYeepayAction::buildTransactionForm($params,false);
				$this->theme->setAction('yeepay');
				break;
		}

		//创建支付进程
		$treeData->addItem('uurc',$this->ua->rc);
		$treeData->addItem('uuid',$this->ua->id);
		$treeData->addItem('sp_ip',DCS::ip());
		$treeData->addItem('sp_agent',DCS::agent());
		$treeData->addItem('status',1);
		$treeData->addItem('tim',DCS::timer());
		//debugTree($treeData);
		PaymentAction::create($treeData);
		
		//模板变量
		$this->treeVar->addItem('sp',$sp);
		$this->treeVar->addItem('tradeno',$tradeno);
		$this->treeVar->addItem('sp_url',$sp_url);
		$this->treeVar->addItem('sp_form',$sp_form);

		if(DCS::isLocal()){
			$this->treeVar->addItem('refresh.second',9999);
			$this->treeVar->addItem('url.jump','');
		}
		else{
			$this->treeVar->addItem('refresh.second',3);
			$this->treeVar->addItem('url.jump','yes');
		}
	}
	
	public function doThemeCache(&$that)
	{
		
	}
	
}
?>