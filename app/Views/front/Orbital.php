<canvas id='canv' style="height: 400px;"></canvas>
<style>
body {
  width: 100%;
  margin: 0;
  overflow: hidden;
  background: hsla(0, 0%, 10%, 1);
}


</style>
<script>
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


var c;
var $;
var arr = [];
var copy;
var midX = 250;
var midY = 250;
var _off = 0;
var _fr = 0;
var _t = 3000;

function ready() {
  c = document.getElementById("canv");
  $ = c.getContext("2d");
  c.width = window.innerWidth;
  c.height = window.innerHeight;
  midX = c.width / 2;
  midY = c.height / 2;
  _draw();
  run();
}

function run() {
  window.requestAnimFrame(run);
  go();
}

function _draw() {
  var _currRad = 10;
  var _rad, _radi, _mass, _sp, off_;
  while (_currRad < 400) {
    _mass = Math.random() * Math.random() * 50 + 1;
    _rad = _currRad + _mass / 2;
    _currRad += _mass + 3;

    _radi = Math.random() * Math.PI + Math.PI / 4;
    _sp = Math.random() * Math.PI / 50 + Math.PI / 300;
    off_ = Math.random() * Math.PI * 2;
    arr.push(new _arc(_rad, _radi, _mass, _sp, off_));
  }
  setInterval(_dir, _t / arr.length);
  copy = arr.slice(0);
}

function go() {
  $.clearRect(0, 0, c.width, c.height);
  $.fillStyle = 'hsla(0,0%,10%,1)';
  $.fillRect(0, 0, c.width, c.height);
  for (var i in arr) {
    arr[i].upd().disp();
  }
  _off = Math.cos(_fr / 80) * Math.PI / 60 + Math.sin(_fr / 10) * Math.PI / 100;
  _fr++;
}

function _dir() {
  if (copy.length) {
    copy.splice(Math.floor(Math.random() * copy.length), 1)[0].rev();
  } else {
    for (var i in arr) {
      arr[i].rev();
      arr[i]._sp = Math.random() * Math.PI / 40 + Math.PI / 300;
    }
    copy = arr.slice(0);
  }
}

function _arc(_rad, _radi, _mass, _sp, off_) {
  this._rad = _rad;
  this._radi = _radi;
  this._mass = _mass;
  this._sp = _sp;
  this.off_ = off_;
  this.dir_ = 1;
}
_arc.prototype = {
  rev: function() {
    this.dir_ = this.dir_ ? 0 : 1;
  },
  upd: function() {
    this.off_ += this._sp * this.dir_ + _off;
    return this;
  },
  disp: function() {
    /*Note: only one arc stroke (the white) is necesary - 
    I'm using multiples for some depth && the 
    touch of blue towards the center*/
    
    //black under white strokes  / shadow mimic
    $.beginPath();
    $.strokeStyle = 'hsla(0,0%,0%,.5)';
    $.lineWidth = this._mass + 4;
    $.arc(midX - 2, midY - 2, this._rad, this.off_, this._radi + this.off_, false);
    $.stroke();
    
    //white strokes
    $.beginPath();
    $.strokeStyle = 'hsla(255,255%,255%,1)';
    $.lineWidth = this._mass;
    $.arc(midX, midY, this._rad, this.off_, this._radi + this.off_, false);
    $.stroke();
   
    //black under blue strokes / shadow mimic
    $.beginPath();
    $.strokeStyle = 'hsla(0, 0%, 0%, .6)';
    $.lineWidth = this._mass / 4 + 1;
    $.arc(midX - 1, midY - 1, this._rad / 2, this.off_, this._radi + this.off_, false);
    $.stroke();
    
   //blue strokes
    $.beginPath();
    $.strokeStyle = 'hsla(203, 95%, 25%, 1)';
    $.lineWidth = this._mass / 4;
    $.arc(midX, midY, this._rad / 2, this.off_, this._radi + this.off_, false);
    $.stroke();
  }
};

window.addEventListener('resize', function() {
  c.width = w = window.innerWidth;
  c.height = h = window.innerHeight;
}, false);

ready();
</script>