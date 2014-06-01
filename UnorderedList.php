<?php
class UnorderedList implements IConvertible
{
	private $items = array ();
	
	function __construct (array $items)
	{
		$this->items = $items;
	}
	
	public function md ()
	{
		$str = '';
		
		foreach ($this->items as $item)
			$str .= '* ' . $item . PHP_EOL;
		
		return $str;
	}

	public function rst ()
	{
		$str = '';
		
		foreach ($this->items as $item)
			$str .= '- ' . $item . PHP_EOL;
		
		return $str;
	}

	public function html ()
	{
		$str = '<ul>';
		
		foreach ($this->items as $item)
			$str .= '<li>' . $item . '</li>' . PHP_EOL;
		$str .= '</ul>';
		
		return $str;
	}
}