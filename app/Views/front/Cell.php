<canvas id='canv'></canvas>
<style>
/*
BG Gradient courtesy of https://codepen.io/taylorvowell/pen/BkxbC > it's gorgeous - thanks!
*/

html{
height:100%;
background: #092756;
 background: -moz-radial-gradient(0% 100%, ellipse cover, rgba(104,128,138,.4) 10%,rgba(138,114,76,0) 40%),-moz-linear-gradient(top, rgba(57,173,219,.25) 0%, rgba(42,60,87,.4) 100%), -moz-linear-gradient(-45deg, #670d10 0%, #092756 100%);
 background: -webkit-radial-gradient(0% 100%, ellipse cover, rgba(104,128,138,.4) 10%,rgba(138,114,76,0) 40%), -webkit-linear-gradient(top, rgba(57,173,219,.25) 0%,rgba(42,60,87,.4) 100%), -webkit-linear-gradient(-45deg, #670d10 0%,#092756 100%);
 background: -o-radial-gradient(0% 100%, ellipse cover, rgba(104,128,138,.4) 10%,rgba(138,114,76,0) 40%), -o-linear-gradient(top, rgba(57,173,219,.25) 0%,rgba(42,60,87,.4) 100%), -o-linear-gradient(-45deg, #670d10 0%,#092756 100%);
 background: -ms-radial-gradient(0% 100%, ellipse cover, rgba(104,128,138,.4) 10%,rgba(138,114,76,0) 40%), -ms-linear-gradient(top, rgba(57,173,219,.25) 0%,rgba(42,60,87,.4) 100%), -ms-linear-gradient(-45deg, #670d10 0%,#092756 100%);
 background: -webkit-radial-gradient(0% 100%, ellipse cover, rgba(104,128,138,.4) 10%,rgba(138,114,76,0) 40%), linear-gradient(to bottom, rgba(57,173,219,.25) 0%,rgba(42,60,87,.4) 100%), linear-gradient(135deg, #670d10 0%,#092756 100%);
}
body{
  margin:0;
  width:100%;
  overflow:hidden;
}
</style>
<script>
window.requestAnimFrame = (function() {
  return window.requestAnimationFrame ||
    window.webkitRequestAnimationFrame ||
    window.mozRequestAnimationFrame ||
    window.oRequestAnimationFrame ||
    window.msRequestAnimationFrame ||
    function(loop) {
      window.setTimeout(callback, 1000 / 60);
    };
})();

var c = document.getElementById("canv"),
    $ = c.getContext("2d");

var w = c.width = window.innerWidth,
    h = c.height = window.innerHeight;

window.addEventListener('resize', function() {
  c.width = w = window.innerWidth;
  c.height = h = window.innerHeight;
});

var _p;
var _prev;
var _arr = [];

var cols = [
  'hsla(0, 5%, 95%, .15)',
  'hsla(0, 95%, 25%, .1)',
  'hsla(291, 95%, 35%, .1)',
  'hsla(235, 95%, 15%, .1)',
  'hsla(325, 95%, 45%, .1)'
];

var rndCol = function() {
  var len = cols.length;

  var hues = Math.floor(Math.random() * len);
  return cols[hues];
}

var rng = function(_fm, _to) {
  return _fm + (Math.random() * (_to - _fm));
}

var _obj = function(_idx, _x, _y) {
  this.idx = _idx + Math.random();
  this.t = (Math.PI / 2);
  this.x = _x;
  this.y = _y;
  this.s = 1;
  this.col = "hsla(255,255%,255%,1)";
  this.ax = rng(-1, 1);
  this.ay = rng(-1, 1);
  this.dist = 0;
  this.conn = 0;
  this._conn = [];

  this.r = 10;
  this.sp = .002;
  this.col = rndCol();
  this.draw = function() {

    $.moveTo(this.x, this.y);
    $.fillStyle = this.col;

    this.t += this.sp;
    this.t = this.t == 1 ? 0 : this.t;

    this.x += Math.sin(Math.cos(this.idx * 0.5) +
      (this.t * this.idx * .4)) * this.r;
    this.y += Math.cos(Math.cos(this.idx * 0.5) +
      (this.t * this.idx * .52)) * this.r;
  }
  this._bind = function() {
    this.conn = 0;
    this._conn = [];
    this._fill = false;

    for (var i in _arr) {
      _p = _arr[i];
      this.dist = Math.sqrt(Math.pow(_p.x - this.x, 2) +
        Math.pow(_p.y - this.y, 2));

      if (this.conn < 8 && this.dist < 1.8e2) {
        this.conn++;
        this._conn.push(_p);
      }

      if (this.conn == 8 && this._fill == false) {

        $.beginPath();

        $.fillStyle = this.col;

        $.moveTo(this._conn[0].x, this._conn[0].y);

        var j = 1;
        for (j in this._conn)
          $.lineTo(this._conn[j].x, this._conn[j].y);
        $.lineTo(this._conn[0].x, this._conn[0].y);
        $.fill();
        this._fill = true;
      }
    }
  }
}

for (var i = 0; i < 1.8e2; ++i) {
  _p = new _obj(i, w / 2, h / 2);
  _p.draw();
  _arr.push(_p);
}

var run = function() {
  window.requestAnimFrame(run);
  $.clearRect(0, 0, w, h);
  $.fillStyle = 'transparent';
  $.fillRect(0, 0, w, h);

  for (var i in _arr) {
    _p = _arr[i];
    _p.draw();
    _p._bind();
  }
}
run();

/* **
var key functions:
--------
c: canvas
$: context
w: canvas width
h: canvas height
_p: particle point
_prev: previous particle point position
_arr: particle point array
cols: color array
len: length of color array
hues: colors listed in the color array
rng: range of point connection [ _to, _from ]
_obj: make the object
_idx: point index
_t: theta
s: size
ax: 'another' x point w/in given range
ay: 'another' y point w/in given range
dist: distance
conn: number of connections
_conn: connection array
r: radius
sp: speed
col: color
_bind: make it all work together 
_fill: connection colorfill
run: animation loop

** */    
</script>