<?php
/* Generates SVG from JSON data (only paths)
 *
 */
function generateSVG($did,$d) {
	$svgfile = fopen("rajz/".$did.".svg", "w");
	// Assemble svg data
	$svg = '<?xml version="1.0" standalone="no"?>
<!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 1.1//EN" "http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd">
<svg width="800" height="565" viewBox="0 0 800 565" xmlns="http://www.w3.org/2000/svg" version="1.1">
<path d="';
	for($i=0;$i<count($d["paths"]);$i++) {
		// Move to
		$x1=$d["paths"][$i]["points"][0]["x"];
		$y1=$d["paths"][$i]["points"][0]["y"];
		$svg.="M ";
		$svg.=$x1.",".$y1." ";
		// Control point and quadratic curve
		for($j=1;$j<count($d["paths"][$i]["points"])-1;$j++){
			$x=$d["paths"][$i]["points"][$j]["x"];
			$y=$d["paths"][$i]["points"][$j]["y"];
			$x1=$d["paths"][$i]["points"][$j+1]["x"];
			$y1=$d["paths"][$i]["points"][$j+1]["y"];
			$cx=($x+$x1)/2.0;
			$cy=($y+$y1)/2.0;
			$svg.="Q ".$x.",".$y." ".$cx.",".$cy." ";
		}
		$svg.="\n";
	} 
	$svg.='" fill="none" stroke="black" stroke-width="3" />
</svg>';
	// End of assembling data
	fwrite($svgfile, $svg);
};

/* Generates SVG from JSON data path and circle overlays
 *
 */
function generateSVGc($did,$d,$fit,$annotated,$label) {
	$svgfile = fopen("rajz/".$did.".svg", "w");
	// Assemble svg data
	$svg = '<?xml version="1.0" standalone="no"?>
<!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 1.1//EN" "http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd">
<svg width="800" height="565" viewBox="0 0 800 565" xmlns="http://www.w3.org/2000/svg" version="1.1">
<path d="';
	for($i=0;$i<count($d["paths"]);$i++) {
		// Move to
		$x1=$d["paths"][$i]["points"][0]["x"];
		$y1=$d["paths"][$i]["points"][0]["y"];
		$svg.="M ";
		$svg.=$x1.",".$y1." ";
		// Control point and quadratic curve
		for($j=1;$j<count($d["paths"][$i]["points"])-1;$j++){
			$x=$d["paths"][$i]["points"][$j]["x"];
			$y=$d["paths"][$i]["points"][$j]["y"];
			$x1=$d["paths"][$i]["points"][$j+1]["x"];
			$y1=$d["paths"][$i]["points"][$j+1]["y"];
			$cx=($x+$x1)/2.0;
			$cy=($y+$y1)/2.0;
			$svg.="Q ".$x.",".$y." ".$cx.",".$cy." ";
		}
		$svg.="\n";
	} 
	$svg.='" fill="none" stroke="black" stroke-width="3" />';
	// Add circles, first one is the 'self'-circle
	for($k=0;$k<sizeof($fit);$k++) {
		$cx=$fit[$k]["cx"];
		$cy=$fit[$k]["cy"];
		$r=$fit[$k]["r"];
		$svg.='<circle cx="'.$cx.'" cy="'.$cy.'" r="'.$r.'" fill="none" ';
		if ($k==0) {
			$svg.='stroke="red" stroke-width="2" />';
			$svg.='<text x="'.$cx.'" y="'.$cy.'" fill="red" font-size="20">Én</text>';
		} else if (($annotated != -1) && ($k == 1)) {
			$svg.='stroke="green" stroke-width="2" />';
			$svg.='<text x="'.$cx.'" y="'.($cy+12).'" fill="green" font-size="20">'.$label.'</text>';
		} else {
			$svg.='stroke="blue" stroke-width="2" />'; 
		}
	}
	$svg.='</svg>';
	// End of assembling data
	fwrite($svgfile, $svg);
};

/* Generates SVG from JSON data path and *labeled* circle overlays
 * Here fit must contain objects with members cx,cy,r,color,label
 *
 */
function generateSVGcombo($dname,$d,$fit) {
	$svgfile = fopen("rajz/".$dname.".svg", "w");
	// Assemble svg data
	$svg = '<?xml version="1.0" standalone="no"?>
<!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 1.1//EN" "http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd">
<svg width="800" height="565" viewBox="0 0 800 565" xmlns="http://www.w3.org/2000/svg" version="1.1">';

	/*
	$svg.='<path d="';
	for($i=0;$i<count($d["paths"]);$i++) {
		// Move to
		$x1=$d["paths"][$i]["points"][0]["x"];
		$y1=$d["paths"][$i]["points"][0]["y"];
		$svg.="M ";
		$svg.=$x1.",".$y1." ";
		// Control point and quadratic curve
		for($j=1;$j<count($d["paths"][$i]["points"])-1;$j++){
			$x=$d["paths"][$i]["points"][$j]["x"];
			$y=$d["paths"][$i]["points"][$j]["y"];
			$x1=$d["paths"][$i]["points"][$j+1]["x"];
			$y1=$d["paths"][$i]["points"][$j+1]["y"];
			$cx=($x+$x1)/2.0;
			$cy=($y+$y1)/2.0;
			$svg.="Q ".$x.",".$y." ".$cx.",".$cy." ";
		}
		$svg.="\n";
	} 
	$svg.='" fill="none" stroke="black" stroke-width="3" />';
	*/
	// Add circles, first one is the 'self'-circle
	for($k=0;$k<sizeof($fit);$k++) {
		$cx=$fit[$k]["cx"];
		$cy=$fit[$k]["cy"];
		$r=$fit[$k]["r"];
		$col = $fit[$k]["color"];
		$lab = $fit[$k]["label"];
		$svg.='<circle cx="'.$cx.'" cy="'.$cy.'" r="'.$r.'" fill="'.$col.'" fill-opacity="0.4" ';
		$svg.='stroke="'.$col.'" stroke-width="2" />';
		$svg.='<text x="'.$cx.'" y="'.($cy).'" fill="'.$col.'" font-size="20">'.$lab.'</text>';
	}
	$svg.='</svg>';
	// End of assembling data
	fwrite($svgfile, $svg);
};
?>