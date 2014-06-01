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
	
	$num = substr ($string, 0, $dotPos - 1);
	$isNumNum = is_numeric ($num);
	
	if (! $isNumNum)
		return false;
	else
		return $dotPos;
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

?>