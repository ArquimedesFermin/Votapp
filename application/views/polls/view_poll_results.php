<?php
	$colors = array(
		"F7464A","FF5A5E",
		"39bc2c","5AD3D1",
		"2956BF","FFC870",
		"5BACD4","7db864",
		"fc3a7a","fc7eab",
		"17b71e","8ab785",
		"27415e","416b9c",
		"5a339c","2a1849",
		"1d492a","3e9c59",
		"959c3c","42451a",
		"452d3e","845778",
		);
	$count = 22;
?>
<script>
var social_data = [
	<?php $c= 0; ?>
	<?php foreach($answers->result() as $r) : ?>
	<?php if(isset($colors[$c])) {
		$col = $colors[$c];
		$c++;
		$high = $colors[$c];
		$c++;
	} else {
		$col = $colors[0];
		$high = $colors[1];
		$c=2;
	}
	?>
		    {
				type: 'bar',
		        value: '<?php echo $r->votes ?>',
		        color: "#<?php echo $col ?>",
		        highlight: "#<?php echo $high ?>",
		        label: "<?php echo $r->answer ?>"
		    },
		    <?php endforeach; ?>
		    
		];

	var options = {animation:false};

	var ctx = $("#pollChart").get(0).getContext("2d");
	var myDoughnutChart = new Chart(ctx).Doughnut(social_data,options);

	var legend = myDoughnutChart.generateLegend();
	 $('#pollTypeChartArea').addClass("clearfix").append(legend);
	 </script>

 <div class="align-center" id="pollTypeChartArea">
		<canvas id="pollChart"></canvas>
	</div>

	<?php
	if(date("y-m-d",$poll->votes_today_timestamp) != date("y-m-d")) {
		$poll->votes_today = 0;
	}

	if(date("y-m",$poll->votes_month_timestamp) != date("y-m")) {
		$poll->votes_month = 0;
	}
	?>

	<hr>
	<table class="table table-bordered">
	<tr><td><?php echo lang("ctn_428") ?>:</td><td><?php echo number_format($poll->votes) ?></td></tr>
	<tr><td><?php echo lang("ctn_429") ?>:</td><td><?php echo number_format($poll->votes_today) ?></td></tr>
	<tr><td><?php echo lang("ctn_430") ?>: </td><td><?php echo number_format($poll->votes_month) ?></td></tr>
	</table>

	<p><a href="<?php echo site_url("polls/results/" . $poll->ID) ?>" class="btn btn-primary btn-sm form-control"><?php echo lang("ctn_431") ?></a></p>
