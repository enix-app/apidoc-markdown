<?php

class TwigGlobal {
	
	public $alias = 'my';

	public function helperTitle(string $filename='')
	{
		$mapper = [
			"html" => "HTML",
			"xml" => "XML",
			"url" => "URL",
		];

		$filename = explode("_", $filename);

		array_walk($filename, function(&$word) use($mapper) {
			$word = str_replace(array_keys($mapper), array_values($mapper), $word);
			$word = ucfirst($word);
		});

		return implode(" ", $filename);
	}

}