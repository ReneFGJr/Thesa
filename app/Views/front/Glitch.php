<canvas id="canv"></canvas>
<style>
@import url(https://fonts.googleapis.com/css?family=Cuprum);
body{
  width:100%; 
  margin:0;
  overflow:hidden;
}
</style>
<script>
Array.prototype.sc = function(s) {
  for (var i = 0; i < this.length; ++i) {
    this[i] = this[i] * s;
  }
  return this;
}

Array.prototype.add = function(v) {
  for (var i = 0; i < this.length; ++i) {
    this[i] = this[i] + v[i];
  }
  return this;
}

Array.prototype.as = function(s, dt) {
  for (var i = 0; i < this.length; ++i) {
    this[i] = this[i] + dt * s[i];
  }
  return this;
}

Array.prototype.step = function(f, dt) {
  return this.as(f(this), dt);
}

Array.prototype._step = function(f, dt) {
  var curr = this.slice(0).step(f, dt / 2);
  return this.add(f(curr).sc(dt));
}

Array.prototype.calc = function(f, dt) {
  var a1 = f(this);
  var a2 = f(this.slice(0).as(a1, dt / 2));
  var a3 = f(this.slice(0).as(a2, dt / 2));
  var a4 = f(this.slice(0).as(a3, dt));
  return this.slice(0).add(a1.as(a2, 2).as(a3, 2).add(a4).sc(dt / 6));
}

var c = document.getElementById('canv');
var $ = c.getContext('2d');
c.width = window.innerWidth;
c.height = window.innerHeight;
var arr = [];
var pts = [0.5, 0.5, 0.5];
var a = 10;
var r = 35;
var dr = 0.125;
var b = 8 / 3;

var f = function(x) {
  return [a * (x[1] - x[0]),
    x[0] * (r - x[2]) - x[1],
    x[0] * x[1] - b * x[2]
  ];
};

var dt = 0.005;
var max = [];
var bound = [];
var slc = 1;
var sz = 5000;
var ang = Math.PI / 4;
var u = 0;

var _angs = function(angle) {
  var a1 = Math.cos(angle);
  var a2 = Math.sin(angle);
  var s = Math.sqrt(2);
  return function(x) {
    var t1 = (a1 * x[0] - a2 * x[2]) / s;
    var t2 = (a2 * x[0] + a1 * x[2]) / s;
    return [t1 + t2, t2 - t1 - x[1]];
  }
}
var ans = _angs(ang);


$.draw = function(v, s) {
  this.beginPath();
  var _a = s(ans(v[0]));
  this.moveTo(_a[0], _a[1]);
  for (var i = 1; i < v.length; ++i) {
    _a = s(ans(v[i]));
    this.lineTo(_a[0], _a[1]);
  }
  var g = $.createLinearGradient(_a[0] + _a[1], _a[1] + _a[0], _a[1], 2);
  g.addColorStop(0, 'hsla(' + u + ', 100%, 50%, 1)');
  g.addColorStop(.5, 'hsla(' + u * parseInt(5) * 3 + ', 100%, 50%, 1)');
  g.addColorStop(1, 'hsla(' + i + ', 100%, 50%, 1)');
  this.globalCompositeOperation = 'lighter';
  this.strokeStyle = g;
  this.stroke();
}

var rsz = function(m) {
  var xc = (m[0] + m[1]) / 2;
  var xd = (m[1] - m[0]) * 0.55;
  var yc = (m[2] + m[3]) / 2;
  var yd = (m[3] - m[2]) * 0.55;
  return [xc - xd, xc + xd, yc - yd, yc + yd];
}

var sc = function(s, d) {
  var a = (d[1] - d[0]) / (s[1] - s[0]);
  var b = d[0] - a * s[0];
  var c = (d[2] - d[3]) / (s[2] - s[3]);
  var d = d[2] - c * s[2];
  return function(x) {
    return [a * x[0] + b, c * x[1] + d];
  };
}

var ptmax = function() {
  var x1 = this[0][0],
    x2 = this[0][0],
    y1 = this[0][1],
    y2 = this[0][1];
  for (var i = 1; i < this.length; ++i) {
    var _a = ans(this[i]);
    x1 = Math.max(x1, _a[0]);
    x2 = Math.min(x2, _a[0]);
    y1 = Math.max(y1, _a[1]);
    y2 = Math.min(y2, _a[1]);
  }
  return [x2, x1, y2, y1];
}

var _max_ = function(_b) {
  var x1 = _b[0].max[0],
    x2 = _b[0].max[1],
    y1 = _b[0].max[2],
    y2 = _b[0].max[3];
  for (var i = 1; i < _b.length; ++i) {
    var _a = _b[i].max;
    x2 = Math.min(x2, _a[0]);
    x1 = Math.max(x1, _a[1]);
    y2 = Math.min(y2, _a[2]);
    y1 = Math.max(y1, _a[3]);
  }
  return [x2, x1, y2, y1];
}
for (var i = 0; i < slc; ++i) {
  var _b = [];
  for (var j = 0; j < sz; ++j) {
    _b.push(pts);
    pts = pts.calc(f, dt);
    pts = pts.calc(f, dt);
  }
  var l1 = {
    arr: _b
  };
  l1.arr._max_ = ptmax;
  l1.max = l1.arr._max_();
  arr.push(l1);
}
$.lineWidth = 0.7;

var mx = arr[0].max;

function draw() {
  $.clearRect(0, 0, c.width, c.height);
  $.fillStyle = 'hsla(0,0%,10%,1)';
  $.fillRect(0, 0, c.width, c.height);
  $.textBaseline = 'middle';
  $.font = "3em Cuprum";
  var t = "Lorenz  System ".split("").join(String.fromCharCode(0x2004));
  $.fillStyle = 'hsla(' + u  + ', 100%, 50%, .2)';
  $.fillText(t, (c.width - 
  $.measureText(t).width) * 0.5, c.height * 0.45);
  var t2 = "THESA".split("").join(String.fromCharCode(0x2004));
  $.font = "4em Cuprum";
  $.fillStyle = 'hsla(' + u * parseInt(5) * 5 + ', 100%, 50%, .5)';
  $.fillText(t2, (c.width - $.measureText(t2).width) * 0.5, c.height * 0.57);
  var d = arr.shift();
  for (var i = 0; i < sz; ++i) {
    pts = pts.calc(f, dt);
    pts = pts.calc(f, dt);
    d.arr[i] = pts;
  }
  d.max = d.arr._max_();
  arr.push(d);
  var mx1 = d.max;
  for (var i = 0; i < mx.length; ++i) {
    mx[i] = (10 * mx[i] + mx1[i]) / 11;
  }
  var s = sc(rsz(mx), [0, c.width, 0, c.height]);

  for (var i = 0; i < arr.length; ++i) {
    $.draw(arr[i].arr, s);
  }
  if (r > 180) {
    r = 180;
    dr = -dr / 2;
  }
  if (r < 25) {
    r = 25;
    dr = -dr / 2;
  }
  r += dr;
  ang += 0.02;
  ans = _angs(ang);

};

window.addEventListener('resize', function(){
  c.width = window.innerWidth;
  c.height = window.innerHeight;
}, false);

function run() {
  window.requestAnimationFrame(run);
  draw();
  u -= .2;
}
run(); 
</script>