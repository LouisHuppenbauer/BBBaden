<?php
	function get($url) {
		return json_decode(file_get_contents($url), true);
	}
