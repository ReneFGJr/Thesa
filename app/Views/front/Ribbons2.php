<canvas id='canv' ></canvas>
<h4>HyperCube</h4>

<style>
@import url(https://fonts.googleapis.com/css?family=Poiret+One);

body{
  width:100%; 
  margin:0;
  overflow:hidden;
  /* background:hsla(0,0%,10%,1); */
  font-family:'Poiret One', serif;
}

#canv
  {
    top:0;
    left:0;
    width:50%;
    height:50%;
    z-index:-1;
  }
h4{
  position:absolute;
  width:100%;
  left:0;
  text-align:center;
  bottom:0;
  color:hsla(0,0%,5%,1);
  letter-spacing:8px;
  font-size:2em;
}
   
</style>

<script>window.requestAnimFrame = (function() {
  return window.requestAnimationFrame ||
    window.webkitRequestAnimationFrame ||
    window.mozRequestAnimationFrame ||
    window.oRequestAnimationFrame ||
    window.msRequestAnimationFrame ||
    function(callback) {
      window.setTimeout(callback, 1000 / 60);
    };
})();

var c = document.getElementById('canv');
var $ = c.getContext('2d');

function Edge(p1, p2) {
  this.p1 = p1;
  this.p2 = p2;
  Edge.edges.push(this);
}
Edge.prototype.draw = function() {
  line(this.p1.coord, this.p2.coord);
};
Edge.edges = [];
Edge.draw = function() {
  for (var i in this.edges) {
    this.edges[i].draw();
  }
};

function Pt() {
  this.x = arguments[0] || 0;
  this.y = arguments[1] || 0;
  this.z = arguments[2] || 0;
  this._x = this.x;
  this._y = this.y;
  this._z = this.z;
  this.coord = {
    x: 0,
    y: 0
  };
}

function Obj() {
  this.x = arguments[0] || 0;
  this.y = arguments[1] || 0;
  this.z = arguments[3] || 0;
  this.pts = [];
}
Obj.prototype.add = function(pt) {
  this.pts.push(pt);
  return pt;
};
Obj.prototype.rotX = function(n) {
  for (i in this.pts) {
    var p = this.pts[i];
    var y = p.y * Math.cos(n) - p.z * Math.sin(n);
    var z = p.y * Math.sin(n) + p.z * Math.cos(n);
    p.y = y;
    p.z = z;
  }
};
Obj.prototype.rotY = function(n) {
  for (i in this.pts) {
    var p = this.pts[i];
    var x = p.x * Math.cos(n) - p.z * Math.sin(n);
    var z = p.x * Math.sin(n) + p.z * Math.cos(n);
    p.x = x;
    p.z = z;
  }
};
Obj.prototype.rotZ = function(n) {
  for (i in this.pts) {
    var p = this.pts[i];
    var x = p.x * Math.cos(n) - p.y * Math.sin(n);
    var y = p.x * Math.sin(n) + p.y * Math.cos(n);
    p.x = x;
    p.y = y;
  }
};
Obj.prototype.pts_ = function() {
  var i, len;
  for (i in this.pts) {
    this.pts[i]._x = this.pts[i].x + this.x;
    this.pts[i]._y = this.pts[i].y + this.y;
    this.pts[i]._z = this.pts[i].z + this.z;
  }
  return this.pts;
};

Obj.cube = function() {
  var cube = new Obj();
  var _e = (arguments[0] || 200) / 2;

  var p1 = cube.add(new Pt(_e, _e, _e));
  var p2 = cube.add(new Pt(-_e, _e, _e));
  var p3 = cube.add(new Pt(-_e, _e, -_e));
  var p4 = cube.add(new Pt(_e, _e, -_e));
  var p5 = cube.add(new Pt(_e, -_e, _e));
  var p6 = cube.add(new Pt(-_e, -_e, _e));
  var p7 = cube.add(new Pt(-_e, -_e, -_e));
  var p8 = cube.add(new Pt(_e, -_e, -_e));

  new Edge(p1, p2);
  new Edge(p2, p3);
  new Edge(p3, p4);
  new Edge(p4, p1);

  new Edge(p5, p6);
  new Edge(p6, p7);
  new Edge(p7, p8);
  new Edge(p8, p5);

  new Edge(p1, p5);
  new Edge(p2, p6);
  new Edge(p3, p7);
  new Edge(p4, p8);

  return cube;
};

function Cube() {
  this.objs = [];
}
Cube.prototype.app = function(obj) {
  this.objs.push(obj);
};
Cube.prototype.pts = function() {
  var joined = [];
  var i, len;
  for (i in this.objs) {
    var pts = this.objs[i].pts_();
    for (var p in pts) {
      joined.push(pts[p]);
    }
  }
  return joined;
};

function Persp(canv) {
  this.x = 0;
  this.y = 0;
  this.z = 0;
  this.canv = canv;
}
Persp.prototype.pts = function() {
  var i, len;
  var pts = this.canv.pts();
  for (i in pts) {
    pts[i]._x = pts[i]._x - this.x;
    pts[i]._y = pts[i]._y - this.y;
    pts[i]._z = pts[i]._z - this.z;
  }
  return pts;
};

function Disp(v) {
  this.v = v;
  this.ang = 60;
}
Disp.prototype.hold = function() {

  var _s = ((_w > _h) ? _w : _h) * 0.5;
  var pov = 1 / Math.tan(this.ang * 0.5 * Math.PI / 180);

  var pts_3 = this.v.pts();
  var pts_2 = [];
  var i, len;
  for (i in pts_3) {
    coord = {
      x: null,
      y: null
    };
    coord.x = pts_3[i]._x / pts_3[i]._z * pov * _s;
    coord.y = pts_3[i]._y / pts_3[i]._z * pov * _s;
    pts_3[i].coord = coord;
  }
  return pts_3;
};

function View(d) {
  this.d = d;
}
View.prototype.draw = function() {
  var pts = this.d.hold();
  var i, len;

  for (i in pts) {
    pts[i].coord = {
      x: (_w / 2) + pts[i].coord.x,
      y: (_h / 2) + pts[i].coord.y
    };
  }
  Edge.draw();
};

function line(p1, p2) {
  //shadow mimic
  $.beginPath();
  $.strokeStyle = 'hsla(0,0%,5%,.7)';
  $.lineWidth = 8;
  $.moveTo(p1.x+1, p1.y+1);
  $.lineTo(p2.x+1, p2.y+1);
  $.closePath();
  $.stroke();
 //cube lines 
  $.beginPath();
  $.strokeStyle = 'hsla(255,255%,255%,.7)';
  $.lineWidth = 4;
  $.moveTo(p1.x, p1.y);
  $.lineTo(p2.x, p2.y);
  $.closePath();
  $.stroke();
}

var _w = window.innerWidth;
var _h = window.innerHeight;

var cb = new Cube();

var cubes = [];

for (var i = 0; i < 3; i++) {var cube = Obj.cube(60 - (i * 50));
cube.x = 0;
cube.y = 0;
  cube.z = 200;

  cb.app(cube);
  cubes.push(cube);
}

var v = new Persp(cb);
v.x = 0;
v.y = 0;
v.z = 0;
var d = new Disp(v);
var vw = new View(d);

function resize() {;

  _w = c.width = window.innerWidth;
  _h = c.height = window.innerHeight;
}

function run() {

  window.requestAnimFrame(run);
  resize();
  $.clearRect(0, 0, _w, _h);

  for (var i = 0; i < cubes.length; i++) {
    cubes[i].rotX(0.01 * (.5 + i) * 0.5);
    cubes[i].rotY(0.02 * (.5 + i) * 0.5);
    cubes[i].rotZ(0.03 * (.5 + i) * 0.5);
  }
  vw.draw();
};
run();  
</script>