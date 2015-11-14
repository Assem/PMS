<?php

/**
 * Check a string date and convert it from 'd/m/Y' to 'Y-m-d' to
 * be able to save it in DB
 * 
 * @param string $date
 */
function prepareDateForSave($date) {
	if(empty($date)) {
		$date = null;
	} else {
		$date = DateTime::createFromFormat('d/m/Y', $date)->format('Y-m-d');
	}
	
	return $date;
}