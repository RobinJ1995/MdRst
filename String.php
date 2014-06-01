<?php
class String implements IConvertible
{
	private $content;
	
	function __construct ($content)
	{
		$this->content = (string) $content;
	}
	
	function __toString ()
	{
		return $this->content;
	}

	public function md ()
	{
		return $this->content;
	}

	public function rst ()
	{
		return $this->content;
	}

	public function html ()
	{
		return $this->content;
	}
}
?>