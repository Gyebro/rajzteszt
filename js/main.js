// Drawing test framework
// Developed by: Gitta Hadabás and Gergely Gyebrószki

// Version: 0.0.1-DEV
// Note: ~5k samples should generate 40KB data (2+2+4 bytes for two coords and timestamp)

// EXTERNAL GLOBAL VARIABLES: user, stage

// Setting up instructions (based on stage)
// Stage 1-5: firkateszt
// Stage 6-24: körteszt
var errorHolder = document.querySelector('#errorbox');
var instructionHolder = document.querySelector('#instructions');
var instBase = "Ábrázold a következő fogalmat nem figuratív firkákkal – olyan vonalakkal és formákkal, amelyek nem ábrázolnak semmilyen konkrét dolgot: ";
var inst = [
	/*0*/["DÜH"],
	/*1*/["MAGÁNY"],
	/*2*/["SZOMORÚSÁG"],
	/*3*/["BOLDOGSÁG ÉRZÉSE"],
	/*4*/["HANGULATOM AZ UTÓBBI IDŐBEN"],
	/*5*/["MÁS JELLEGŰ FELADAT</br>Rajzolj egy kört, ami TÉGED jelképez!",
	"Most rajzold le a PÁRODAT is egy kör formájában, ami őt jelképezi, ugyanerre a lapra. Ha nincs párod, gondolj egy konkrét, régebbi kapcsolatodra és rajzold le a volt párodat is egy kör formájában, ami őt jelképezi, ugyanerre a lapra."],
	/*6*/["Ábrázold ezen a lapon MAGADAT ÉS PÁRODAT is egy-egy körrel! Ha nincs párod, akkor gondolj ugyanarra a régebbi kapcsolatodra, amire az első feladatban."],
	/*7*/["Ábrázold ezen a lapon magadat és a LEGJOBB BARÁTODAT (férfit vagy nőt) is egy-egy körrel!"],
	/*8*/["Ábrázold ezen a lapon magadat és a SZÁMODRA IDEÁLIS PARTNERT is egy-egy körrel!"],
	/*9*/["Gondolj valakire, AKIVEL KONFLIKTUSBAN ÁLLSZ. Ábrázold ezen a lapon magadat és őt is egy-egy körrel!"],
	/*10*/["Gondolj arra a legfontosabb, belső PROBLÉMÁDRA, ami mostanában foglalkoztat (aktuális, belső konfliktusra, tünetre, betegségre stb.). Ábrázold ezen a lapon magadat és a problémát is egy-egy körrel!"],
	/*11*/["Ábrázold ezen a lapon magadat és az előbbi problémát EGY ÉV MÚLVA egy-egy körrel!"],
	/*12*/["Ábrázold ezen a lapon magadat és PÁRODAT ÖT ÉV MÚLVA egy-egy körrel! Arra a személyre gondolj, akire az első feladatban. Ha aktuálisan nincs párod, akkor hagyd ki a feladatot."],
	/*13*/["Ábrázold ezen a lapon GYENGE ÉNEDET ÉS ERŐS ÉNEDET is egy-egy körrel!"],
	/*14*/["Ábrázold ezen a lapon magadat és a BOLDOGSÁGOT egy-egy körrel!"],
	/*15*/["Ábrázold ezen a lapon magadat és a PÉNZT egy-egy körrel!"],
	/*16*/["Ábrázold ezen a lapon magadat és a SZEXUALITÁST egy-egy körrel!"],
	/*17*/["Ábrázold ezen a lapon magadat és a MUNKÁDAT egy-egy körrel!"],
	/*18*/["Ábrázold ezen a lapon magadat és az EGÉSZSÉGEDET egy-egy körrel!"],
	/*19*/["Ábrázold ezen a lapon magadat és a SZOMORÚSÁGOT egy-egy körrel!"],
	/*20*/["Ábrázold ezen a lapon magadat és a SZABADSÁGOT egy-egy körrel!"],
	/*21*/["Ábrázold ezen a lapon magadat és a HÁLÁT egy-egy körrel!"],
	/*22*/["Ábrázold ezen a lapon magadat és a SPIRITUALITÁST egy-egy körrel!"],
	/*23*/["Ábrázold ezen a lapon magadat és a HANGULATODAT AZ UTÓBBI IDŐBEN egy-egy körrel!"]
];
var steps = [
	/*0-4*/1,1,1,1,1,
	/*5*/2,
	/*6-12*/1,1,1,1,1,1,1,
	/*13*/1,
	/*14-23*/1,1,1,1,1,1,1,1,1,1
];
var defSel = "Kattints a téged ábrázoló kör vonalára melyet a program piros színnel fog kiemelni!";
var selects = [
	/*0-4*/0,0,0,0,0,
	/*5*/1,
	/*6-12*/1,1,1,1,1,1,1,
	/*13*/2,
	/*14-23*/1,1,1,1,1,1,1,1,1,1
];
var instSel = [
	/*0-4*/"","","","","",
	/*5*/"",
	/*6-12*/"","","","","","","",
	/*13*/"Válaszd ki az ERŐS énedet.",
	/*14-23*/"","","","","","","","","",""
];

var s = stage-1;
var phase = 0; // 0: drawing, 1: selecting, 2: saving
var drawingCount = steps[s];
var selectCount = selects[s];
var drw = 0;
var sel = 0;
var prevDrawingPaths = 0;
var drawingPaths = 0;
var selected = false;
var selection = [-1, -1, -1];

// Display initial instruction
var instruction = ""; 
if ((stage > 0) && (stage <= 5)) {
	// Firkateszt prefix
	instruction+=instBase;
}
instruction+=inst[s][drw];
instructionHolder.innerHTML = instruction;

// Getting canvas and creating context
var canvas = document.querySelector('#drawing');
var ctx = canvas.getContext('2d');
 
// Get dimensions
var holder = document.querySelector('#canvasholder');
var holderStyle = getComputedStyle(holder);
canvas.width = parseInt(holderStyle.getPropertyValue('width'));
canvas.height = parseInt(holderStyle.getPropertyValue('height'));

// Creating a temporary canvas
var tmp_canvas = document.createElement('canvas');
var tmp_ctx = tmp_canvas.getContext('2d');
tmp_canvas.id = 'tmp_canvas';
tmp_canvas.width = canvas.width;
tmp_canvas.height = canvas.height;

// Append to holder
holder.appendChild(tmp_canvas);

// Mouse coord holder
var mouse = {x: 0, y: 0, t: 0};
var ppts = [];
var touched = false;
var touchTimeStamp = 0;
var startTimeStamp = 0;
var pathid = 0;
var mousedown = false;
var mouseover = false;

// Data holders
var currentpath = [];
var paths = [];

// Set attributes
tmp_ctx.lineWidth	= 3;
tmp_ctx.lineJoin	= 'round';
tmp_ctx.lineCap		= 'round';
tmp_ctx.strokeStyle = 'black';
tmp_ctx.fillStyle	= 'black';

function showError(message) {
	errorHolder.innerHTML = message;
	errorHolder.className = "error";
}

function dismissError() {
	errorHolder.innerHTML = "";
	errorHolder.className = "errorstart";
}

function finishPath() {
	if (phase == 0) {
		// Remove onPaint listener
		tmp_canvas.removeEventListener('mousemove', onPaint, false);
		// Writing to main canvas now
		ctx.drawImage(tmp_canvas, 0, 0);
		// Clearing tmp canvas
		tmp_ctx.clearRect(0, 0, tmp_canvas.width, tmp_canvas.height);
		// Clear ppts
		ppts = [];
		// Add current path to paths
		if (currentpath.length > 0) {
			paths.push(currentpath);
			drawingPaths++;
			currentpath = [];
		}
		return;
	}
}

function startPath(e) {
	if (phase == 0) {
		// Add onPaint listener
		tmp_canvas.addEventListener('mousemove', onPaint, false);
		// Increase pathid
		pathid++;
		// Initialize ppts
		mouse.x = typeof e.offsetX !== 'undefined' ? e.offsetX : e.layerX;
		mouse.y = typeof e.offsetY !== 'undefined' ? e.offsetY : e.layerY;
		mouse.t = e.timeStamp;
		//console.log("Beginning path: "+pathid);
		ppts.push({x: mouse.x, y: mouse.y});
		return;
	}
}

function findClosestPath(e) {
	if(phase == 1){
		var targetx = typeof e.offsetX !== 'undefined' ? e.offsetX : e.layerX;
		var targety = typeof e.offsetY !== 'undefined' ? e.offsetY : e.layerY;
		var closestPath = 0;
		// Omit square root in calculations
		var distance = 8000000; // (2000*sqrt(2))^2
		var currdist = 0;
		for (var i = 0; i < paths.length; i++) {
			for (var j = 0; j < paths[i].length; j++) {
				currdist = Math.pow(targetx-paths[i][j].x,2)+Math.pow(targety-paths[i][j].y,2);
				if (currdist < distance) {
					distance = currdist;
					closestPath = i;
				}
			}
		}
		selected = true;
		// Sel is already incremented
		selection[sel-1] = closestPath;
		console.log(selection);
		highlightPath(closestPath);
	}
}

function highlightPath(pathid) {
	tmp_ctx.strokeStyle = 'red';
	// Draw the path to the tmp canvas with red
	console.log("highlighted path: "+pathid);
	// tmp_canvas is always cleared up before drawing.
	tmp_ctx.clearRect(0, 0, tmp_canvas.width, tmp_canvas.height);
	tmp_ctx.beginPath();
	tmp_ctx.moveTo(paths[pathid][0].x, paths[pathid][0].y);
	// Draw curve for points
	for (var i = 1; i < paths[pathid].length - 2; i++) {
		var c = (paths[pathid][i].x + paths[pathid][i + 1].x) / 2;
		var d = (paths[pathid][i].y + paths[pathid][i + 1].y) / 2;
		tmp_ctx.quadraticCurveTo(paths[pathid][i].x, paths[pathid][i].y, c, d);
	}
	// For the last 2 points
	tmp_ctx.quadraticCurveTo(
		paths[pathid][i].x,
		paths[pathid][i].y,
		paths[pathid][i + 1].x,
		paths[pathid][i + 1].y
	);
	tmp_ctx.stroke();
	tmp_ctx.strokeStyle = 'black';
}

// Mouse capturing using tmp_canvas
tmp_canvas.addEventListener('mousemove', function(e) {
	mouse.x = typeof e.offsetX !== 'undefined' ? e.offsetX : e.layerX;
	mouse.y = typeof e.offsetY !== 'undefined' ? e.offsetY : e.layerY;
	mouse.t = e.timeStamp;
}, false);


// tmp_canvas.mousedown
tmp_canvas.addEventListener('mousedown', function(e) {
	if (touched == false) { touchTimeStamp = e.timeStamp; touched = true; console.log("touched");}
	//console.log("tmp_canvas.mousedown");
	mousedown = true;
	mouseover = true;
	dismissError();
	startPath(e);
	onPaint();
	findClosestPath(e);
}, false);
 
// tmp_canvas.mouseup
tmp_canvas.addEventListener('mouseup', function() {
	//console.log("tmp_canvas.mouseup");
	mousedown = false;
	finishPath();
}, false);

// tmp_canvas.mouseleave
tmp_canvas.addEventListener('mouseleave', function(e) {
	//console.log("tmp_canvas.mouseleave");
	mouseover = false;
	if (mousedown) {
		finishPath();
	}
}, false);

// tmp_canvas.mouseenter
tmp_canvas.addEventListener('mouseenter', function(e) {
	if (touched == false) { touchTimeStamp = e.timeStamp; touched = true; console.log("touched"); }
	if (mousedown) {
		startPath(e);
	}
	mouseover = true;
}, false);

// document.mouseup
document.addEventListener('mouseup', function() {
	//console.log("document.mouseup");
	mousedown = false;
	finishPath();
}, false);

// onPaint event listener
var onPaint = function() {
	if (phase == 0) {
	// Saving current point in the array
	ppts.push({x: mouse.x, y: mouse.y});
	//currentpath.push({x: mouse.x, y: mouse.y, t: mouse.t-startTimeStamp});
	// TODO: Until startTimeStamp is consistent, I'll use touchTimeStamp
	currentpath.push({x: mouse.x, y: mouse.y, t: mouse.t-touchTimeStamp});
	
	// Single point
	if (ppts.length < 3) {
		var b = ppts[0];
		tmp_ctx.beginPath();
		tmp_ctx.arc(b.x, b.y, tmp_ctx.lineWidth / 2, 0, Math.PI * 2, !0);
		tmp_ctx.fill();
		tmp_ctx.closePath();
		return;
	}
	
	// tmp_canvas is always cleared up before drawing.
	tmp_ctx.clearRect(0, 0, tmp_canvas.width, tmp_canvas.height);
	tmp_ctx.beginPath();
	tmp_ctx.moveTo(ppts[0].x, ppts[0].y);
	// Draw curve for points
	for (var i = 1; i < ppts.length - 2; i++) {
		var c = (ppts[i].x + ppts[i + 1].x) / 2;
		var d = (ppts[i].y + ppts[i + 1].y) / 2;
		tmp_ctx.quadraticCurveTo(ppts[i].x, ppts[i].y, c, d);
	}
	// For the last 2 points
	tmp_ctx.quadraticCurveTo(
		ppts[i].x,
		ppts[i].y,
		ppts[i + 1].x,
		ppts[i + 1].y
	);
	tmp_ctx.stroke();
	} // end if (phase==0)
};

// Set up other UI elements
var savebtn = document.querySelector('#savebutton');
savebtn.addEventListener("click", function(){

	// Check for missing step
	if (phase == 0) {
		if(drawingPaths > prevDrawingPaths) {
			//console.log("user has drawn something in this step");
		} else {
			showError("A folytatáshoz rajzolj valamit!");
			return;
		}
	}
	if (phase == 1) {
		if(selected) {
			//console.log("user has selected something in this step");
		} else {
			showError("A folytatáshoz kattints az egyik vonalra!");
			return;
		}
	}

	// Advance phase if necessary
	if ((phase == 0) && (drw == drawingCount-1)) { 
		phase++; //console.log("advancing to select phase");
	}
	if ((phase == 1) && (sel == selectCount)) { 
		phase++; //console.log("advancing to save phase"); 
		
	}

	// Update instruction and increment step
	if (phase == 0) {
		// Next drawing phase
		drw++;
		// Update instruction
		instruction = inst[s][drw];
		instructionHolder.innerHTML = instruction;
		// Reset completion criteria
		prevDrawingPaths = drawingPaths;
	}
	if (phase == 1) {
		// Clear previous selection
		tmp_ctx.clearRect(0, 0, tmp_canvas.width, tmp_canvas.height);
		// Update instruction
		instruction = [instSel[s],defSel][1-sel];
		instructionHolder.innerHTML = instruction;
		// Post increment selection phase
		sel++;
		// Reset completion criteria
		selected = false;
	}
	if (phase == 2) {
		// Disable button
		savebtn.disabled=true;
		var jsonString = '{"paths":[';
		for (var i = 0; i < paths.length; i++) {
			if(i) { jsonString += ","; }
			jsonString += '{"points":[';
			for (var j = 0; j < paths[i].length; j++) {
				if(j) { jsonString += ","; }
				jsonString += '{"x":'+paths[i][j].x+',"y":'+paths[i][j].y+',"t":'+paths[i][j].t+'}';
			}
			jsonString += "]}";
		}
		jsonString += "]}";
		var xmlhttp = new XMLHttpRequest();
		xmlhttp.onreadystatechange=function() {
			// TODO: Hide spinner / loading
			if (xmlhttp.readyState==4 && xmlhttp.status==200) {
				if ( xmlhttp.responseText=="OK" ) {
					console.log("OK");
					// Redirect
					if (stage == 24) {
						window.location = "questions.php?user="+userid;
					} else {
						window.location = "test.php?user="+userid+"&stage="+(stage+1);
					}
				}
				else {
					console.log("Failed to save. Error: "+xmlhttp.responseText);
					// Enable button again
					savebtn.disabled=false;
					showError("Adatbázis hiba, kérlek kattints a mentés gombra!");
				}
			}
		};
		comment = encodeURI(document.getElementById("comment").value);
		// TODO: Display spinner / loading
		xmlhttp.open("POST","save.php",true);
		xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
		xmlhttp.send("id="+userid+"&stage="+stage+"&data="+jsonString+"&sel1="+
			selection[0]+"&sel2="+selection[1]+"&sel3="+selection[2]+"&comment="+comment);

	}
}, false);

// Set up other UI elements
var commentbtn = document.querySelector('#addcomment');
commentbtn.addEventListener("click", function(){
	document.getElementById("addcomment").style.display = 'none';
	document.getElementById("commentfield").style.display = 'block';
}, false);

// Polyfill for Date.now
if (!Date.now) {
  Date.now = function now() {
    return new Date().getTime();
  };
}

// TODO: Firefox uses System start as epoch!

// Save timestamp
startTimeStamp = Date.now();