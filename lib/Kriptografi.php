<?php
include_once 'Database.php';

class Kriptografi extends \Database
{

	protected static $cipher 	= MCRYPT_RIJNDAEL_256;
	protected static $mode 		= MCRYPT_MODE_CBC;
	protected static $salt 		= '383bfa91d6c8f4cc3356584561b5dc46';
	protected static $key;

	public static function hash($password)
	{
		static::$key = hash('SHA256', static::$salt . $password, true);

		return base64_encode( mcrypt_encrypt(static::$cipher, md5(static::$key), $password, static::$mode, md5(md5(static::$key))));
	}

	public static function check($data)
	{
		foreach($data as $dt => $d){
			$field[] = $dt;
			$value[] = $d;
		}
		
		if($field[0] == 'password' ){
			$pwd1 = Database::get('user')->where($field[0], '=', static::hash($value[0]))->result();
			
			return ($pwd1) ? true : false;
		}elseif($field[1] == 'password'){
			$pwd2 = Database::get('user')->where($field[0], '=', $value[0])->where($field[1], '=', static::hash($value[1]))->result();
			
			return ($pwd2) ? true : false;
		}else{
			return false;
		}
	}

	public static function unhash($hash)
	{
		return rtrim( mcrypt_decrypt( static::$cipher, md5(static::$key), base64_decode($hash),  static::$mode, md5(md5(static::$key))));
	}
}