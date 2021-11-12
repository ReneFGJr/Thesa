<canvas id="canv"></canvas>
<style>
* {
    margin: 0;
    padding: 0;
    overflow:hidden;
}

canvas {
	 width:100%; 
   height:100vh;
	 background: hsla(0,5%, 5%, 1);
}
</style>

<script>
var c= document.getElementById("canv");
	var $ = c.getContext("2d");
	
	var w = window.innerWidth;
var h = window.innerHeight;
	c.width = w;
	c.height = h;
	
	var parts = [];
	for(var i = 0; i < 40; i++){
		parts.push(new part());
	}
	
	function part(){
		this.loc = {
    x: Math.random()*w, y: Math.random()*h
 };
    this.rad = 0;
    this.sp= 3;
    this.ang = Math.random()*360;
    this.r = Math.round(Math.random()*255);
		    this.g = Math.round(Math.random()*255);
		    this.b = Math.round(Math.random()*255);
	}
	
	function draw(){
		$.globalCompositeOperation = "source-over";
$.fillStyle = "hsla(0, 0%, 0%, 0.08)";
$.fillRect(0, 0, w, h);
		$.globalCompositeOperation = "lighter";
		
		for(var i = 0; i < parts.length; i++){
			var p = parts[i];
			$.fillStyle = "hsla(255,255%,255%,1)";
			$.fillRect(p.loc.x, p.loc.y, p.rad, p.rad);
			
			for(var n = i+1; n < parts.length; n++){
				var p2 = parts[n];

        $.beginPath();
        $.lineWidth = 1;
        $.moveTo(p.loc.x, p.loc.y);
        $.lineTo(p2.loc.x, p2.loc.y);

        var dx = p.loc.x - p2.loc.x;
        var dy = p.loc.y - p2.loc.y;
        var d = Math.sqrt(dx*dx+dy*dy);
        var d2= Math.pow(0.5,Math.round(d/100));

        var nr = Math.round(p.r*d2);
        var ng = Math.round(p.g*d2);
        var nb = Math.round(p.b*d2);


        $.strokeStyle = "rgba("+nr+", "+ng+", "+nb+", 1)";
        $.stroke();
			}
			
			p.loc.x = p.loc.x + p.sp*Math.cos(p.ang*Math.PI/180);
			p.loc.y = p.loc.y + p.sp*Math.sin(p.ang*Math.PI/180);

var con=false;

      if(p.loc.x < 0) {
        con=true;
        p.loc.x = 0;
        if(p.ang<180){
          p.ang = 180 - p.ang;
        } else {
          p.ang = 360 - (p.ang - 180);
        }
      }
      if(p.loc.x > w) {
        con=true;
        p.loc.x = w;
        if(p.ang<180){
          p.ang = 180 - p.ang;
        } else {
          p.ang = 180 + (360 - p.ang);
        }
      }
      if(p.loc.y < 0) {
        con=true;
        p.loc.y = 0;
        if(p.ang<180){
          p.ang = 180 - p.ang;
        } else {
          p.ang = 180 + (180 - p.ang);
        }
      }
      if(p.loc.y > h) {
        con=true;
        p.loc.y = h;
        if(p.ang>270){
          p.ang = 360 - p.ang;
        } else {
          p.ang = 180 - (p.ang - 180);
        }
      }
      if(con){
        p.r = Math.round(Math.random()*255);
        p.g = Math.round(Math.random()*255);
        p.b = Math.round(Math.random()*255);
      }
		   }
	}
	setInterval(draw, 30);



</script>