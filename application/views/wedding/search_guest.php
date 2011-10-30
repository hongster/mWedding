<section class="grid_12">
	<h2>Guest Search</h2>
</section>

<section class="grid_12" ng:controller="GuestController">
	<?php echo Form::open(URL::site('wedding/search_guest', TRUE), array('ng:submit'=>'search()', 'id'=>'search_form')); ?>
		<fieldset>
			<input type="search" name="query" id="query" value="<?php echo HTML::chars($query); ?>" />
			<input type="submit" value="Search" />
		</fieldset>
	<?php echo Form::close(); ?>

	<h2 id="search_status">Searching...</h2>
	<table class="search_result">
		<tr>
			<th>Name</th>
			<th>Table</th>
			<th>Arrived</th>
		</tr>

		<tr ng:repeat="guest in result.guests.$orderBy(['+name', '+table_name', '+has_arrived'])">
			<td><a href="<?php echo URL::site('wedding/table', TRUE); ?>/{{guest.table_id}}">{{guest.name}}</a></td>
			<td><a href="<?php echo URL::site('wedding/table', TRUE); ?>/{{guest.table_id}}">{{guest.table_name}}</a></td>
			<td>{{guest.has_arrived | arrivalStatus}}</td>
		</tr>
	</table>

</section>

<script>
	angular.filter('arrivalStatus', function(input){
		return (input == 1) ? 'YES' : 'NO';
	});

	function GuestController($xhr) {
		self = this;

		self.search = function() {
			// Trim and validate input
			query.value = angular.formatter.trim.format(query.value);
			if (query.value == '') {
				alert('Please specify a guest name.');
				return;
			}

			search_status.innerText = 'Searching...';

			// Perform AJAX search
			$xhr(
				'POST',
				'<?php echo URL::site('wedding/ajax_search_guest', TRUE); ?>',
				'query=' + escape(query.value),
				function(code, data){
					search_status.innerText = data.guests.length + ' result(s) found';
					self.result = data;
				}
			);
		}

		self.search();
	}
</script>