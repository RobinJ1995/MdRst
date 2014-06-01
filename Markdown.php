<?php
class Markdown implements IConvertible
{
	private $content = array ();
	
	function __construct ($md)
	{
		$lines = explode (PHP_EOL, $md);
		
		$mode = null;
		$remember = null;
		
		foreach ($lines as $i => $line)
		{
			$c = firstChar ($line);
			$line = rtrim ($line);
			
			do
			{
				$handled = true;
			
				if ($mode == null)
				{
					if ($c == '#' && strlen (str_replace ('#', '', $line)) > 0)
					{
						$level = mdHeadingLevel ($line);
						$content = trim (substr ($line, $level));

						$this->content[] = new Heading ($content, $level);
					}
					else if (wholeLine ($line, '=')) // The whole line consists of # // Previous line was h1 //
					{
						$previousIndex = count ($this->content) - 1;
						$previous = $this->content[$previousIndex];

						if ($previous instanceof String)
							$this->content[$previousIndex] = new Heading ($previous, 1);
					}
					else if (wholeLine ($line, '-')) // The whole line consists of - // Previous line was h2 //
					{
						$previousIndex = count ($this->content) - 1;
						$previous = $this->content[$previousIndex];

						if ($previous instanceof String)
							$this->content[$previousIndex] = new Heading ($previous, 2);
					}
					else if (startsWith ($line, '```'))
					{
						$lang = trim (substr ($line, 3));

						$mode = 'code';
						$remember = array
						(
							'lang' => $lang,
							'content' => ''
						);
					}
					else if (startsWith ($line, '* ') || startsWith ($line, '+ ') || startsWith ($line, '- '))
					{
						$mode = 'ulist';
						$remember = array
						(
							'items' => array
							(
								trim (substr ($line, 2))
							)
						);
					}
					else if (startsWithNumberDot ($line) !== false)
					{
						$pos = startsWithNumberDot ($line) + 1;
						
						$mode = 'olist';
						$remember = array
						(
							'items' => array
							(
								trim (substr ($line, $pos))
							)
						);
					}
					else if (startsWith ($line, '|'))
					{
						$mode = 'table';
						$cols = mdTableColSplit ($line);
						
						$mode = 'table';
						$remember = array
						(
							'cols' => $cols,
							'align' => null,
							'rows' => array ()
						);
					}
					else
					{
						$this->content[] = new String ($line);
					}
				}
				else if ($mode == 'code')
				{
					if (startsWith ($line, '```'))
					{
						$this->content[] = new Code ($remember['content'], $remember['lang']);
						
						$mode = null;
						$remember = null;
					}
					else
					{
						$remember['content'] .= $line . PHP_EOL;
					}
				}
				else if ($mode == 'ulist')
				{
					if (startsWith ($line, '* ') || startsWith ($line, '+ ') || startsWith ($line, '- '))
					{
						$remember['items'][] = trim (substr ($line, 2));
					}
					else
					{
						$this->content[] = new UnorderedList ($remember['items']);
						
						$mode = null;
						$remember = null;
						
						$handled = false;
					}
				}
				else if ($mode == 'olist')
				{
					if (startsWithNumberDot ($line))
					{
						$pos = startsWithNumberDot ($line) + 1;
						$remember['items'][] = trim (substr ($line, $pos));
					}
					else
					{
						$this->content[] = new OrderedList ($remember['items']);
						
						$mode = null;
						$remember = null;
						
						$handled = false;
					}
				}
				else if ($mode == 'table')
				{
					if (startsWith ($line, '|'))
					{
						if ($remember['align'] === null)
						{
							$chars = str_split ($line);
							$colId = -1;
							$remember['align'] = array ();
							
							foreach ($chars as $i => $char)
							{
								if ($char == '|')
								{
									$colId++;
								}
								else 
								{
									if (! isset ($remember['align'][$colId]))
										$remember['align'][$colId] = array
										(
											'l' => null,
											'r' => null
										);
									
									if ($char == ':')
									{
										if ($remember['align'][$colId]['l'] === null)
											$remember['align'][$colId]['l'] = true;
										else
											$remember['align'][$colId]['r'] = true;
									}
								}
							}
						}
						else
						{
							$remember['rows'][] = mdTableColSplit ($line);
						}
					}
					else
					{
						$this->content[] = new Table ($remember['cols'], $remember['align'], $remember['rows']);
						
						$mode = null;
						$remember = null;
						
						$handled = false;
					}
				}
			} while (! $handled);
		}
	}
	
	public function md ()
	{
		$str = '';
		
		foreach ($this->content as $obj)
			$str .= $obj->md () . PHP_EOL;
		
		return $str;
	}
	
	public function rst ()
	{
		$str = '';
		
		foreach ($this->content as $obj)
			$str .= $obj->rst () . PHP_EOL;
		
		return $str;
	}
	
	public function html ()
	{
		$str = '';
		
		foreach ($this->content as $obj)
			$str .= $obj->html () . PHP_EOL;
		
		return $str;
	}
}