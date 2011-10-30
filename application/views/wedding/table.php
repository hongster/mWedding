<section class="grid_12" ng:controller="TableController">
	<h2>{{table.name}}</h2>

	<?php echo Form::open(URL::site("wedding/add_guest/$table_id", TRUE)); ?>
		<label for="guest_name">Guest name:</label>
		<input type="text" name="guest_name" id="guest_name" />
		<input type="submit" value="Add Guest" />
	<?php echo Form::close(); ?>

	<p>
		<span class="title">Attandence:</span> {{table.guests.$count('has_arrived==1')}} / {{table.guests.$count()}}
	</p>

	<h3>Waiting</h3>
	<ul class="guest-list">
		<li ng:repeat="guest in table.guests.$filter({has_arrived:0}).$orderBy('name')">
			{{guest.name | tagLinker}}
			[<a href="<?php echo URL::site('/wedding/guest_checkin', TRUE); ?>/{{guest.guest_id}}">Checkin</a>]
		</li>
	</ul>

	<h3>Arrived</h3>
	<ul class="guest-list">
		<li ng:repeat="guest in table.guests.$filter({has_arrived:1}).$orderBy('name')">
			{{guest.name | tagLinker | html}}
			[<a href="<?php echo URL::site('/wedding/guest_checkout', TRUE); ?>/{{guest.guest_id}}">Checkout</a>]
		</li>
	</ul>

</section>
<div class="clear"></div>

<script>
	/* Convert tags to links */
	angular.filter(
		'tagLinker',
		function(str) {
			var baseURL = '<?php echo URL::site('wedding/tag', TRUE); ?>/';

			var words = str.split(' ');
			for (i=0;i<words.length;i++) {
				if (words[i].charAt(0) != '#' || words[i].length == 1)
					continue;

				words[i] = '<a href="'+baseURL+words[i].substr(1)+'">'+words[i]+'</a>';
			}
			return words.join(" ");
		}
	);

	function TableController($xhr) {
		self = this;

		self.setTableInfo = function() {
			$xhr(
				'GET',
				'<?php echo URL::site("wedding/ajax_table/$table_id", TRUE); ?>',
				function(code, response) {
					self.table = response;
				}
			);
		};

		// Load the table info
		self.setTableInfo();
	}
</script>