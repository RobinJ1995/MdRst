<?php
require_once ('helpers.php');
require_once ('IConvertible.php');
require_once ('Markdown.php');
require_once ('Heading.php');
require_once ('String.php');
require_once ('Code.php');
require_once ('UnorderedList.php');

class MdRst
{
	public static function md ($md)
	{
		return new Markdown ($md);
	}
	
	public static function mdFile ($file)
	{
		return self::md (file_get_contents ($file));
	}
}
?>