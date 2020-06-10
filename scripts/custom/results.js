$(document).ready(function() {
	var ctx = $("#myChart").get(0).getContext("2d");
	var options: {
    animation: {
        duration: 0, // general animation time
    },
    hover: {
        animationDuration: 0, // duration of animations when hovering an item
    },
    responsiveAnimationDuration: 0, // animation duration after a resize
}
		// This will get the first returned node in the jQuery collection.
	var myLineChart = new Chart(ctx).Line(data_graph, {options});
});