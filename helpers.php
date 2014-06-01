<?php

function startsWith ($stack, $needle)
{
	return strpos ($stack, $needle) === 0;
}

function firstChar ($string)
{
	return substr ($string, 0, 1);
}

function wholeLine ($line, $char)
{
	return strlen ($line) == substr_count ($line, $char);
}

function startsWithNumberDot ($string)
{
	$dotPos = strpos ($string, '.');
	if ($dotPos === false)
		return false;
	
	$num = substr ($string, 0, $dotPos);
	$isNumNum = is_numeric ($num);
	
	if (! $isNumNum)
		return false;
	else
		return $dotPos;
}

function fillString ($string, $n, $filler = ' ')
{
	while (strlen ($string) < $n)
		$string .= $filler;
	
	return $string;
}

# Markdown specific #
function mdHeadingLevel ($string)
{
	$chars = str_split ($string);
	
	for ($i = 0; $i < count ($chars); $i++)
	{
		if ($chars[$i] !== '#')
			return $i;
	}
}

function mdTableColSplit ($row)
{
	$columns = array ();
	$cols = explode ('|', $row);
	
	foreach ($cols as $col)
	{
		if (! empty ($col))
			$columns[] = trim ($col);
	}
	
	return $columns;
}

?>