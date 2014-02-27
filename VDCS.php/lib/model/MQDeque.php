<?
class MQDeque
{
	public $queue  = array();
	public $length = 0;
   
	public function frontAdd($node){
		array_unshift($this->queue,$node);
		$this->countqueue();
	}
	public function frontRemove(){
		$node = array_shift($this->queue);
		$this->countqueue();
		return $node;
	}
	  
	public function rearAdd($node){
		array_push($this->queue,$node);
		$this->countqueue();
	}
	 
	public function rearRemove(){
		$node = array_pop($this->queue);
		$this->countqueue();
		return $node;
	}
	 
	public function countqueue(){
		$this->length = count($this->queue);    
	}
}

/*
$fruit = new deque();
echo $fruit -> length;
$fruit -> frontAdd("Apple");
$fruit -> rearAdd("Watermelon");
echo '<pre>';
print_r($fruit);
echo '</pre>';
*/

/*
deque，全名double-ended queue，是一种具有队列和栈的性质的数据结构。双端队列中的元素可以从两端弹出，其限定插入和删除操作在表的两端进行。双向队列（双端队列）就像是一个队列，但是你可以在任何一端添加或移除元素。而双端队列是一种数据结构，定义如下：

A deque is a data structure consisting of a list of items, on which the following operations are possible.

push(D,X) -- insert item X on the rear end of deque D.
pop(D) -- remove the front item from the deque D and return it.
inject(D,X) -- insert item X on the front end of deque D.
eject(D) -- remove the rear item from the deque D and return it.
Write routines to support the deque that take O(1) time per operation.

翻译：双端队列（deque）是由一些项的表组成的数据结构，对该数据结构可以进行下列操作：

push(D,X) 将项X 插入到双端队列D的前端
pop(D) 从双端队列D中删除前端项并将其返回
inject(D,X) 将项X插入到双端队列D的尾端
eject(D) 从双端队列D中删除尾端项并将其返回
编写支持双端队伍的例程，每种操作均花费O（1）时间
*/
