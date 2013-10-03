<?php
/**
 * Core Security
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       Cake.Utility
 * @since         CakePHP(tm) v .0.10.0.1233
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

namespace org\cakephp;

/**
 * Security Library contains utility methods related to security
 *
 * @package       Cake.Utility
 */
class Security {

/**
 * Default hash method
 *
 * @var string
 */
	public static $hashType = null;

/**
 * Default cost
 *
 * @var string
 */
	public static $hashCost = '10';

/**
 * Create a hash from string using given method or fallback on next available method.
 *
 * #### Using Blowfish
 *
 * - Creating Hashes: *Do not supply a salt*. Cake handles salt creation for
 * you ensuring that each hashed password will have a *unique* salt.
 * - Comparing Hashes: Simply pass the originally hashed password as the salt.
 * The salt is prepended to the hash and php handles the parsing automagically.
 * For convenience the BlowfishAuthenticate adapter is available for use with
 * the AuthComponent.
 * - Do NOT use a constant salt for blowfish!
 *
 * Creating a blowfish/bcrypt hash:
 *
 * {{{
 * 	$hash = Security::hash($password, 'blowfish');
 * }}}
 *
 * @param string $string String to hash
 * @param string $type Method to use (sha1/sha256/md5/blowfish)
 * @param mixed $salt If true, automatically prepends the application's salt
 *     value to $string (Security.salt). If you are using blowfish the salt
 *     must be false or a previously generated salt.
 * @return string Hash
 */
	public static function hash($string, $type = null, $salt = false) {
		if (empty($type)) {
			$type = self::$hashType;
		}
		$type = strtolower($type);

                if (!is_string($salt)) {
                    throw new \UnexpectedValueException('This hash function requires a salt');
		}
                
		$string = $salt . $string;

		if (!$type || $type === 'sha1') {
			if (function_exists('sha1')) {
				return sha1($string);
			}
			$type = 'sha256';
		}

		if ($type === 'sha256' && function_exists('mhash')) {
			return bin2hex(mhash(MHASH_SHA256, $string));
		}

		if (function_exists('hash')) {
			return hash($type, $string);
		}
		return md5($string);
	}

/**
 * Sets the default hash method for the Security object. This affects all objects using
 * Security::hash().
 *
 * @param string $hash Method to use (sha1/sha256/md5/blowfish)
 * @return void
 * @see Security::hash()
 */
	public static function setHash($hash) {
		self::$hashType = $hash;
	}

/**
 * Sets the cost for they blowfish hash method.
 *
 * @param integer $cost Valid values are 4-31
 * @return void
 */
	public static function setCost($cost) {
		if ($cost < 4 || $cost > 31) {
			trigger_error(__d(
				'cake_dev',
				'Invalid value, cost must be between %s and %s',
				array(4, 31)
			), E_USER_WARNING);
			return null;
		}
		self::$hashCost = $cost;
	}
}
