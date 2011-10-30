<section class="grid_12" ng:controller="TableController">
	<h2 id="table_name" ng:click="editTableName()">{{table.name}}</h2>

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
			{{guest.name | tagLinker | html}}
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

		/* Setup inline edit for table name */
		self.editTableName = function() {
			target = angular.element(table_name);
			target.attr('contenteditable', 'true');
			target.trigger('focus');
		}
		self.saveTableName = function() {
			// Input validation
			name = angular.formatter.trim.format(angular.element(table_name).attr('innerText'));
			if (name == '') {
				angular.element(table_name).attr('innerText', self.table.name);
				alert('Please enter a table name.');
				return;
			}

			$xhr(
				'POST',
				'<?php echo URL::site("wedding/ajax_update_table_name/$table_id", TRUE); ?>',
				'table_name=' + escape(name),
				function(code, data) {
					if (! ('err' in data)) self.setTableInfo()
					else console.info(data);
				}
			);
		}

		angular.element(table_name).bind('blur', angular.element(table_name), function(event) {
			target = event.data;
			target.attr('contenteditable', 'false');
			if (target.attr('innerText') != self.table.name)
				self.saveTableName();
		});
		angular.element(table_name).bind('keyup', angular.element(table_name), function(event) {
			switch (event.which) {
				case 27: // esc
					target = event.data;
					target.attr('innerText', self.table.name);
					target.attr('contenteditable', 'false');
				break;
			}
		});
		angular.element(table_name).bind('keydown', angular.element(table_name), function(event) {
			switch (event.which) {
				case 13: // enter
					target = event.data;
					target.attr('contenteditable', 'false');
					if (target.attr('innerText') != self.table.name)
						self.saveTableName();
					return false;
				break;
			}
		});
		/* end: Setup inline edit for table name */

		// Load the table info
		self.setTableInfo();
	}
</script>