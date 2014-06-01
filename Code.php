<?php
class Code implements IConvertible
{
	private $content;
	private $language;
	
	function __construct ($content, $language = null)
	{
		$this->content = $content;
		$this->language = (empty ($language) ? null : $language);
	}
	
	public function md ()
	{
		return '```' . $this->language . PHP_EOL
			. $this->content
			. '```';
	}
	
	public function rst ()
	{
		//TODO// $this->language information gets lost //
		
		$lines = explode (PHP_EOL, $this->content);
		$str = '::' . PHP_EOL
			. '  ' . PHP_EOL;
		
		foreach ($lines as $line)
			$str .= '  ' . $line . PHP_EOL;
		
		return $str;
	}
	
	public function html ()
	{
		if (strtolower ($this->language) == 'php')
			return '<kbd data-language="' . htmlentities ($this->language) . '">'
			. highlight_string ($this->content, true)
			. '</kbd>';
		else
			return '<pre data-language="' . htmlentities ($this->language) . '">'
			. $this->content
			. '</pre>';
	}
}