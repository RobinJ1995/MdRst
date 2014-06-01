<?php
class Table implements IConvertible
{
	private $columns;
	private $alignment;
	private $rows;
	
	function __construct ($columns, $alignment, $rows)
	{
		$this->columns = $columns;
		$this->alignment = $alignment;
		$this->rows = $rows;
	}
	
	public function md ()
	{
		$columns = array ($this->columns);
		$rows = array_merge ($columns, $this->rows);
		$n = count ($this->columns);
		$colWidth = $this->calculateColumnWidths ($rows, $n);
		
		$str = '|';
		foreach ($this->columns as $i => $col)
			$str .= ' ' . fillString ($col, $colWidth[$i]) . ' |';
		
		$str .= PHP_EOL . '|';
		foreach ($this->alignment as $i => $align)
			$str .= ($align['l'] ? ':' : ' ')
				. fillString ('', $colWidth[$i], '-')
				. ($align['r'] ? ':' : ' ')
				. '|';
		
		foreach ($this->rows as $row)
		{
			$str .= PHP_EOL . '|';
			
			foreach ($row as $i => $col)
				$str .= ' ' . fillString ($col, $colWidth[$i]) . ' |';
		}
		
		return $str;
	}
	
	public function rst ()
	{
		//TODO// $this->alignment information gets lost //
		
		$columns = array ($this->columns);
		$rows = array_merge ($columns, $this->rows);
		$n = count ($this->columns);
		$colWidth = $this->calculateColumnWidths ($rows, $n);
		
		$str = '+';
		foreach ($this->columns as $i => $col)
			$str .= '-' . fillString ('', $colWidth[$i], '-') . '-+';
		
		$str .= PHP_EOL . '|';
		foreach ($this->columns as $i => $col)
			$str .= ' ' . fillString ($col, $colWidth[$i]) . ' |';
		
		$str .= PHP_EOL . '+';
		foreach ($this->columns as $i => $col)
			$str .= '=' . fillString ('', $colWidth[$i], '=') . '=+';
		
		foreach ($this->rows as $row)
		{
			$str .= PHP_EOL . '|';
			
			foreach ($row as $i => $col)
				$str .= ' ' . fillString ($col, $colWidth[$i]) . ' |';
			
			$str .= PHP_EOL . '+';
			foreach ($this->columns as $i => $col)
				$str .= '-' . fillString ('', $colWidth[$i], '-') . '-+';
		}
		
		return $str;
	}
	
	public function html ()
	{
		$n = count ($this->columns);
		
		$str = '<table>' . PHP_EOL
			. '<thead>' . PHP_EOL
			. '<tr>' . PHP_EOL;
		foreach ($this->columns as $col)
			$str .= '<th>' . $col . '</th>' . PHP_EOL;
		$str .= '</tr>' . PHP_EOL
			. '</thead>' . PHP_EOL
			. '<tbody>' . PHP_EOL;
		
		foreach ($this->rows as $row)
		{
			$str .= '<tr>' . PHP_EOL;
			
			foreach ($row as $i => $col)
			{
				$align = null;
				if ($this->alignment[$i]['l'] && $this->alignment[$i]['r'])
					$align = 'center';
				else if ($this->alignment[$i]['l'])
					$align = 'left';
				else if ($this->alignment[$i]['r'])
					$align = 'right';
				
				$str .= '<td' .  ($align == null ? '' : ' style="text-align: ' . $align . ';"') . '>' . $col . '</td>' . PHP_EOL;
			}
			
			$str .= '</tr>' . PHP_EOL;
		}
		$str .= '</tbody>' . PHP_EOL
			. '</table>';
		
		return $str;
	}
	
	private function calculateColumnWidths ($rows, $n)
	{
		$colWidth = array ();
		
		for ($i = 0; $i < $n; $i++)
			$colWidth[$i] = -1;
		
		foreach ($rows as $row)
		{
			foreach ($row as $i => $col)
			{
				if ($colWidth[$i] < strlen ($col))
					$colWidth[$i] = strlen ($col);
			}
		}
		
		return $colWidth;
	}
}