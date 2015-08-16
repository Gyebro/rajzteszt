var chart = new CanvasJS.Chart("chartContainer",{
	title:{
		text: label
	},
	//toolTipContent: "<span style='\"'color: {color};'\"'><strong>{name}</strong></span> <br/> <strong>Index</strong> {y} $<br/> <strong>Boldogság</strong> {x} ",
	toolTip:{
		content:"{name}, boldogság: {x}, idx: {y}" ,
	},
	data: [{
		type: "scatter",
		dataPoints: pts
	}]
});
chart.render();