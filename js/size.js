var slider = document.querySelector('#sizeslider');
resizeBoxes(" "+slider.value+"px");
resizeParagraphs(" "+(slider.value/20.0)+"px");
slider.addEventListener('input', function() {
	console.log(slider.value);
	resizeBoxes(" "+slider.value+"px");
	resizeParagraphs(" "+(slider.value/20.0)+"px");
}, false);

function resizeBoxes(w) {
  var selects = document.getElementsByClassName("filterbox");
  var il=selects.length;
  for(var i=0; i<il; i++){
     selects[i].style.width = w;
  }
}

function resizeParagraphs(fs) {
  var selects = document.getElementsByTagName("p");
  var il=selects.length;
  for(var i=0; i<il; i++){
     selects[i].style.fontSize = fs;
  }
}