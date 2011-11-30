<?php defined('SYSPATH') or die('No direct script access.');

class View_Helper {
	/**
	 * Convert #hashtag to anchor link
	 * @param string $text Text to be converted
	 * @param string $url The has ':tag' will be replaced with #hashtag
	 */
	public static function tagalizer($text, $url) 
	{
		$result = array();
		$words = explode(' ', $text);
		foreach ($words as $word)
		{
			if (substr($word, 0, 1) != '#') 
			{
				$result[] = $word;
				continue;
			}
			
			// Hashtag found
			$word = substr($word, 1);
			$result[] = '<a href="'.str_replace(':tag', $word, $url).'">'."#$word".'</a>';
		}
		
		return implode(' ', $result);
	}
}
