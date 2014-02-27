<?php
class ChannelCommonDemoX extends ChannelCommonBaseX
{
	//根据ajax请求的地址中action中的值调用相关方法，如action=list就会调用parseList方法;如果action=add则调用parseAdd方法
	
	//ajax请求的url地址: /common/demo.x?action=list&name=Alusa&age=24
	public function parseList()
	{
		$name=querys('name');//Alusa (querys获取字符串型)
		$age=queryi('age');//24 (queryi获取字符串型)
		$infos='My name is '.$name.' and my age is '.$age;
		$this->addVar('infos',$infos);//将数据加入到xml中
		
		/*
		//生成的xml文件
		<xcml version="1.0" model="map">
		<configure>
			<node>var</node>
			<nodes>item</nodes>
		</configure>
		<var>
			<portal>test</portal>	//当前p
			<action>list</action>	//当前action
			<status>init</status>	//状态，可以通过$this->setStatus('xxx');来设置
			<infos>My name is Alusa and my age is 24</infos>  //通过addVar()添加的
		</var>
		</xcml>
		*/
	}
	
	//ajax请求的url地址: /common/demo.x?action=advance
	public function parseAdvance()
	{
		$this->addVar('hello','Hello Kitty !');
		$this->addVar('says','This is say !');
		
		$table=newTable();
		
		$people=newTree();
		
		$people->addItem('name','Jack');
		$people->addItem('age',10);
		$table->addItem($people);
		
		$people->addItem('name','Helen');
		$people->addItem('age',20);
		$table->addItem($people);
		
		$people->addItem('name','Bob');
		$people->addItem('age',15);
		$table->addItem($people);
		
		$this->addTable('people',$table);//增加一个people节点,默认有一个item节点
		
		/*
		<xcml version="1.0" model="map">
		<configure>
			<node>var</node>
			<nodes>people,item</nodes>
			<field.people>name,age</field.people>
		</configure>
		<var>
			<portal>test</portal>
			<action>advance</action>
			<status>init</status>
			<hello>Hello Kitty !</hello>
			<says>This is say !</says>
		</var>
		<people>
			<name>Jack</name>
			<age>10</age>
		</people>
		<people>
			<name>Helen</name>
			<age>20</age>
		</people>
		<people>
			<name>Bob</name>
			<age>15</age>
		</people>
		</xcml>
		
		*/
	}
	
	public function parseOther()
	{
		//table
		$sql=DB::sqlQuery('db_user','uid,email,name','uid<10010');
		$this->resTable=DB::queryTable($sql);
		$this->resTable->doAppendFields('number,number1');//增加字段
		$i=1;
		$this->resTable->doItemBegin();
		while($this->resTable->isNext()){	//对$resTable进行循环用isNext()
			$this->resTable->setItemValue('number',$i);//为字段设置值
			$this->resTable->setItemValue('number1',$i+1);
			$i++;	
		}
		$fields=$this->resTable->getFields();//获取table对象的所以字段
		debugs($fields);//uid,email,name,number,number1
		debugTable($this->resTable);
		/*
		[1]	10000	gm@gameadd.cn		Game+		1	2
		[2]	10001	1192726720@qq.com	test		2	3
		[3]	10002	fincos@qq.com		Finco		3	4
		[4]	10003	marics.1124@gmail.com	maric		4	5
		[5]	10004	ranom@qq.com		Ranom		5	6
		[6]	10005	haha@qq.com		haha		6	7
		[7]	10006	37970626@qq.com		martine		7	8
		[8]	10007	hehe@test.com		hehe		8	9
		[9]	10008	sh@test.com		shsh		9	10
		[10]	10009	3797062611@qq.com	martine		10	11
		*/
		
		
		//tree
		$this->addVarTree($tree);//将一个tree对象增加到var节点里面
		
		$sql1=DB::sqlQuery('db_user','uid,email,name','uid=10001');
		$this->resTree=DB::queryTree($sql1);
		$this->resTree->addItem('number','1111111');//为tree增加一个字段
		$this->resTree->setItem('uid','00000');//改变tree的字段的值
		
		$this->resTree->doAppendTree($tree);//为resTree对象增加一个tree对象
		
		debugTree($this->resTree);
		
		/*
			total:4
			[0]	uid	00000
			[1]	email	119272@qq.com
			[2]	name	test
			[3]	number	1111111
		*/
	}
	
}
?>