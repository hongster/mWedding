<section class="grid_12">
	<h2>#<?php echo HTML::chars($tag); ?></h2>
</section>
<div class="clear"></div>

<section class="grid_12" ng:controller="GuestCtrl">
	<table class="search_result">
		<tr>
			<th>Guest</th>
			<th>Table</th>
			<th>Arrived</th>
		</tr>
		
		<tr ng:repeat="guest in guests.$orderBy(['has_arrived', '+name', '+table_name'])">
			<td><a href="<?php echo URL::site('wedding/table', TRUE); ?>/{{guest.table_id}}">{{guest.name}}</a></td>
			<td><a href="<?php echo URL::site('wedding/table', TRUE); ?>/{{guest.table_id}}">{{guest.table_name}}</a></td>
			<td>{{guest.has_arrived | arrivalStatus}}</td>
		</tr>
	</table>
</section>
<div class="clear"></div>

<?php
	// Format data as JSON for use by AngularJS
	$data = array();
	foreach ($guests as $guest)
	{
		$data[] = array(
			'name' => $guest->name,
			'has_arrived' => $guest->has_arrived() ? 1 : 0,
			'table_id' => $guest->table->id,
			'table_name' => $guest->table->name,
		);
	}
?>

<script>
	angular.filter('arrivalStatus', function(input){
		return (input == 1) ? 'YES' : 'NO';
	});
	
	function GuestCtrl() {
		this.guests = <?php echo json_encode($data); ?>;
	}
</script>
