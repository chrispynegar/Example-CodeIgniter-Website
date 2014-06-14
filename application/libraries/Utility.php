<?php

class Utility {

	/**
	 * Slugify
	 * 
	 * Converts a string into a url slug
	 * 
	 * @access public
	 * @param string $str
	 * @return string
	*/
	public function slugify($str) {
		// replace non letter or digits by -
		$slug = preg_replace('~[^\\pL\d]+~u', '-', $str);

		// trim
		$slug = trim($slug, '-');

		// transliterate
		$slug = iconv('utf-8', 'us-ascii//TRANSLIT', $slug);

		// lowercase
		$slug = strtolower($slug);

		// remove unwanted characters
		$slug = preg_replace('~[^-\w]+~', '', $slug);

		if(empty($slug)) {
			return 'n-a';
		}

		return $slug;
	}

}