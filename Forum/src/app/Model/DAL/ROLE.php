<?php

class ROLE
{
	public static $array = Array(1 => 'ADMIN', 2 => 'MODERATOR', 3 => 'USER');

	public static $ADMIN = 1;
	public static $MODERATOR = 2;
	public static $USER = 3;

	public static function IdToName($id)
	{
		return self::$array[$id];
	}
}