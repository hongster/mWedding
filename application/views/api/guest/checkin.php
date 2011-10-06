<?php
echo json_encode(array(
	'guest_id' => $guest->id,
	'table_id' => $guest->table_id,
	'guest_name' => $guest->name,
	'has_arrived' => ($guest->has_arrived()) ? 'TRUE' : 'FALSE',
));
?>
