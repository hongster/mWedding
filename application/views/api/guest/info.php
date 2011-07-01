<?php
$data = array(
	'table_name' => $guest->table->name,
	'guest_id' => $guest->id, 
	'other_guests' => array());
foreach ($other_guests as $other_guest)
{
	$data['other_guests'][$other_guest->id] = $other_guest->name;
}

echo json_encode($data);
?>