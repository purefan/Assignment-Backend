<?php

	namespace ProspectEye\Log;

	class Log{

		/**
		*	Simple function to log information to console.
		*	Used to illustrate the different states of the Assignment.
		*/
		public static function save($text){
			
			// Don't log anything if PHPUnit is running
			if (defined('PHPUNIT')){
				return false;
			}
			$callers=debug_backtrace();
			
			$args = preg_replace(array('/\s+/','/\n/'), ' ', trim(print_r($callers[1]['args'], true)));
			if (strlen($args) > 25) $args = substr($args, 0, 25) .'...';
			echo $callers[1]['class'] . '->' . $callers[1]['function'] . '('. $args .')';
			
			if (!is_string($text)){
				$text = preg_replace(array('/\s+/', '/\n/'), '', trim(print_r($text, true)));
			}
			echo "\n\t" . $text . "\n";
		}
	}
?>