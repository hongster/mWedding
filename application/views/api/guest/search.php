<?php
$data = array();

foreach ($guests as $guest) {
	$data[$guest->id] = $guest->name;
}

echo json_encode($data);
?>