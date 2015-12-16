<?php if(count($polls) > 0):?>
<div id="polls">
	<ul>
		<?php foreach ($polls as $poll): ?>
			<li><a href="<?php echo '#'.$poll->id; ?>"><?php echo $poll->code; ?></a></li>
		<?php endforeach; ?>
	</ul>
	<?php foreach ($polls as $poll): ?>
		<div id="<?php echo $poll->id; ?>">
			<div class="map-header">
				<?php echo secure_anchor('polls/view/' . $poll->id, $poll->label).$poll->show_count; ?>
			</div>
			<div class="map-canvas" id="<?php echo $poll->id . "-map"; ?>"></div>
		</div>
	<?php endforeach; ?>
</div>
<?php else: ?>
	<h3>Pas sondages encours!</h3>
<?php endif; ?>

<!-- show most recents sheets -->
<div id="recent_sheets">
	<?php $this->load->view('sheets/_recent_sheets', array('sheets' => $sheets)); ?>
</div>

<div id="locations_error">
	<?php $this->load->view('geolocations/_recent_errors', array('geolocations' => $geolocations)); ?>
</div>

<script type="text/javascript">
var TABS = '#polls';
var GOOGLE_MAP_KEY = '<?php echo $map_key; ?>';
var UPDATE_INTERVAL = '<?php echo $map_refresh; ?>'; //map's data update interval
var IDLE_TIME = '<?php echo $idle_time; ?>';

var mapsids = <?php echo json_encode($polls); ?>;
var data_url = '<?php echo $data_url; ?>';
</script>