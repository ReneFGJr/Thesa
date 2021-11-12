<canvas id = 'canv'></canvas>
<style>
body {
  #background: hsla(0, 0%, 5%, 1);
  overflow: hidden;
  margin: 0;
  width: 100%;
}    
</style>
<script>
var obj = {
  rad: {
    base: 120,
    vary: 30,
    step: Math.PI / 180,

  },
  c: {
    w: window.innerWidth,
    h: window.innerHeight
  },
  lay: {
    num: 10,
    dist: 28,
    diff: Math.PI / 90
  }
};

function Circle(x, y, step) {
  this.x = x;
  this.y = y;
  this.step = step

}

Circle.prototype.draw = function($) {
  var grd = $.createLinearGradient(this.x + this.x * 2, this.y + this.y * 2, this.x + this.x * 2, 1);
  grd.addColorStop(0, "hsla(232, 95%, 70%, 1)");
  grd.addColorStop(0.5, "hsla(267, 25%, 45%, 1)");
  grd.addColorStop(1, "hsla(233, 80%, 50%, 1)");
  this.step += obj.rad.step;
  this.col = grd;

  $.beginPath();
  var r = obj.rad.base + Math.sin(this.step) * obj.rad.vary;
  $.arc(this.x, this.y, r, 0, 2 * Math.PI, true);
  $.strokeStyle = this.col;
  $.stroke();
}

function init() {
  var d = obj.lay.dist,
    t = Math.PI / 3,
    x = obj.c.w / 2,
    y = obj.c.h / 2,

    clay = [
      [new Circle(x, y, 0)]
    ],
    circ, lay, s, pt, ptx, pty, dx, dy;

  for (lay = 1; lay < obj.lay.num; lay++) {
    circ = [];

    for (s = 0; s < 6; s++) {

      ptx = x + d * lay * Math.cos(t * s);
      pty = y + d * lay * Math.sin(t * s);
      dx = d * Math.cos(t * s + t * 2);
      dy = d * Math.sin(t * s + t * 2);
      for (pt = 0; pt < lay; pt++) {

        circ.push(new Circle(ptx + dx * pt, pty + dy * pt, -1 * lay * obj.lay.diff));
      }
    }
    clay.push(circ);
  }
  return clay;
}

var c = document.getElementById('canv');
c.width = obj.c.w;
c.height = obj.c.h;
document.body.appendChild(c);
var $ = c.getContext('2d');
$.fillRect(0, 0, c.width, c.height);
$.globalCompositeOperation = 'lighter';

window.addEventListener('resize', function() {
  c.width = w = window.innerWidth;
  c.height = h = window.innerHeight;
}, false);

var clay = init();

function run() {
  var i, j, ilen, jlen;
  $.clearRect(0, 0, obj.c.w, obj.c.h);

  for (i = 0, ilen = clay.length; i < ilen; i++) {

    for (j = 0, jlen = clay[i].length; j < jlen; j++) {
      clay[i][j].draw($);

    }
  }

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

function go() {
  run();
  window.requestAnimFrame(go);
}
go();  
</script>