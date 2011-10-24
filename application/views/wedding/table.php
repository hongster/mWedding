<section class="grid_12" ng:controller="TableController">
	<h2>{{table.name}}</h2>
	
	<?php echo Form::open("wedding/add_guest/$table_id"); ?>
		<label for="guest_name">Guest name:</label>
		<input type="text" name="guest_name" id="guest_name" />
		<input type="submit" value="Add Guest" />
	<?php echo Form::close(); ?>
	
	<p>
		<span class="title">Attandence:</span> {{table.guests.$count('has_arrived==1')}} / {{table.guests.$count()}}
	</p>

	<h3>Waiting</h3>
	<ul ng:repeat="guest in table.guests.$filter({has_arrived:0})">
		<li>{{guest.name | tagLinker}}</li>
	</ul>
	
	<h3>Arrived</h3>
	<ul ng:repeat="guest in table.guests.$filter({has_arrived:1})">
		<li>{{guest.name | tagLinker | html}}</li>
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
			console.log(words.join(" "));
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