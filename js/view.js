// Drawing test framework
// Developed by: Gitta Hadabás and Gergely Gyebrószki

// EXTERNAL GLOBAL VARIABLES: drawing


// Getting canvas and creating context
var canvas = document.querySelector('#drawing');
var ctx = canvas.getContext('2d');
 
// Get dimensions
var holder = document.querySelector('#canvasholder');
var holderStyle = getComputedStyle(holder);
canvas.width = parseInt(holderStyle.getPropertyValue('width'));
canvas.height = parseInt(holderStyle.getPropertyValue('height'));

// Set attributes
ctx.lineWidth	= 3;
ctx.lineJoin	= 'round';
ctx.lineCap		= 'round';
ctx.strokeStyle = 'black';
ctx.fillStyle	= 'black';

// restore drawing from json
var drawPath = function(ppts) {
	if (ppts.length < 3) {
		return;
	}
	ctx.beginPath();
	ctx.moveTo(ppts[0].x, ppts[0].y);
	// Draw curve for points
	for (var i = 1; i < ppts.length - 2; i++) {
		var c = (ppts[i].x + ppts[i + 1].x) / 2;
		var d = (ppts[i].y + ppts[i + 1].y) / 2;
		ctx.quadraticCurveTo(ppts[i].x, ppts[i].y, c, d);
	}
	// For the last 2 points
	ctx.quadraticCurveTo(
		ppts[i].x,
		ppts[i].y,
		ppts[i + 1].x,
		ppts[i + 1].y
	);
	ctx.stroke();
};

var restoreJson = function() {
	if (!drawing) {
		console.log("drawing is null!");
	} else {
		for (var i = 0; i < drawing.paths.length; i++) {
			drawPath(drawing.paths[i].points);
		}
	}
}

restoreJson();

window.onload = function () {
var chX = [];
var chY = [];
var last = 0;
for (var i = 0; i < drawing.paths.length; i++) {
	chX.push({x:drawing.paths[i].points[0].t,y:0});
	chY.push({x:drawing.paths[i].points[0].t,y:0});
	for(var j = 0; j < drawing.paths[i].points.length; j++) {
		chX.push({x:drawing.paths[i].points[j].t,y:drawing.paths[i].points[j].x});
		chY.push({x:drawing.paths[i].points[j].t,y:drawing.paths[i].points[j].y});
		last = j;
	}
	chX.push({x:drawing.paths[i].points[last].t,y:0});
	chY.push({x:drawing.paths[i].points[last].t,y:0});
}
var chart = new CanvasJS.Chart("chartContainer",{
	title:{
		text: "Pozíció változása az idő függvényében"
	},
	data: [{
		type: "line",
		dataPoints: chX
	},{
		type: "line",
		dataPoints: chY
	}]
});
chart.render();
}