<canvas id='canv' style="height: 400px;" ></canvas>
<style>
@import url(https://fonts.googleapis.com/css?family=Roboto:wght@100);
body{
  width:100%; 
  overflow:hidden;
  margin:0;
  background:hsla(0, 95%, 5%, 1);
}
canvas{
  background:hsla(0, 95%, 5%, 1);
  font-family: 'RobotoThin', cursive;
}
</style>
<script>
var $, c;
var arr = [];
var t1;

c = document.getElementById("canv");
$ = c.getContext("2d");
c.width = window.innerWidth;
c.height = window.innerHeight;
$.fillStyle = "transparent";
$.fillRect(0, 0, c.width, c.height);

function go() {
  draw();
  d();
}

function red() {
  $.globalCompositeOperation = 'source-over';
  $.fillStyle = "hsla(0, 95%, 5%, .7)";
  $.fillRect(0, 0, c.width, c.height);

  for (var i = 0; i < arr.length; i += 10) {
    arr[i].draw();
    arr[i].upd();
  }
}

function draw() {
  t1 = 'THESA';
  $.fillStyle = "hsla(0, 95%, 25%, 1)";
  $.font = "bold 100px 'Righteous'";
  $.textBaseline = 'middle';
  $.fillText(t1, (c.width - $.measureText(t1).width) * 0.5, c.height * 0.5);
}

function d() {
  var id = $.getImageData(0, 0, c.width, c.height);
  var px = 0;
  var s = "";
  for (var y = 0; y < c.height; y++) {
    for (var x = 0; x < c.width; x++) {
      s += "|" + id.data[px] + "," + id.data[px + 1] + "," + id.data[px + 2] + "," + id.data[px + 3];

      if (id.data[px] != 0) {
        arr.push(new p(x, y));
      }
      px += 4;
    }
  }
  var t = setTimeout(run, 2000);
}

function p(x, y) {
  this.x = x;
  this.y = y;
  this.vx = srnd() * 20;
  this.vy = srnd() * 20;
  this.dt = 0.5;
  this.s = rnd(1, 25);
  this.draw = function() {
    $.globalCompositeOperation = 'lighter';
    $.beginPath();
    $.arc(this.x, this.y, this.s, 0, Math.PI * 2, false);
    $.fillStyle = 'hsla(0, 95%, 35%, .6)';
    $.fill();
  }
  this.upd = function() {
    if (this.dt > 0.1) {
      this.dt -= 0.001;
    }
    this.x += this.vx * this.dt;
    this.y += this.vy * this.dt;

    this.bnd();
  }
  this.bnd = function() {
    var r = this.s / 2;
    if (this.x < r) {
      this.x = r;
      this.vx *= -1;
    }
    if (this.y < r) {
      this.y = r;
      this.vy *= -1;
    }
    if (this.x > c.width - r) {
      this.x = c.width - r;
      this.vx *= -1;
    }
    if (this.y > c.height - r) {
      this.y = c.height - r;
      this.vy *= -1;
    }
  }
}

window.addEventListener('resize', function() {
  c.width = window.innerWidth;
  c.height = window.innerHeight;
}, false);

var srnd = function() {
  return Math.random() * 2 - 1;
}
var rnd = function(min, max) {
  return Math.random() * (max - min) + min;
}

window.requestAnimFrame = (function() {
  return window.requestAnimationFrame ||
    window.webkitRequestAnimationFrame ||
    window.mozRequestAnimationFrame ||
    window.oRequestAnimationFrame ||
    window.msRequestAnimationFrame ||
    function(callback) {
      window.setTimeout(callback, 1000 / 60);
    };
})();

function run() {
  window.requestAnimationFrame(run);
  red();
}
go();  
</script>