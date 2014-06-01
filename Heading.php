<?php
class Heading implements IConvertible
{
	private $content;
	private $level;
	
	function __construct ($content, $level = 1)
	{
		$this->content = new String ($content);
		$this->level = (int) $level;
	}
	
	public function md ()
	{
		$str = '';
		
		for ($i = 0; $i < $this->level; $i++)
			$str .= '#';
		
		$str .= ' ' . $this->content;
		
		return $str;
	}
	
	public function rst ()
	{
		// https://groups.google.com/forum/#!msg/sage-devel/1EsW4c_VB8Q/SNiVMZKb5YcJ //
		
		$l = $this->level;
		$h = array ('#', '=', '-', '~', '^', '*');
		if ($l <= count ($h))
			$c = $h[$l - 1];
		else
			$c = '?';
		
		$str = $this->content . PHP_EOL;
		for ($i = 0; $i < strlen ($this->content); $i++)
			$str .= $c;
		
		return $str;
	}
	
	public function html ()
	{
		return '<h' . $this->level . '>'
			. htmlentities ($this->content)
			. '</h' . $this->level . '>';
	}
}
?>