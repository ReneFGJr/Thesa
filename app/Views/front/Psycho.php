<canvas id='canv'></canvas>
<script>
    c = document.getElementById('canv');
    $ = c.getContext("2d");
    s = 25,
        m = [];
    for (i = 0; i < 10; m[i++] = {
            x: Math.random(),
            y: Math.random(),
            dx: Math.random() * .035,
            dy: Math.random() * .035,
            m: Math.random() * .025
        });

    function draw() {
        window.requestAnimationFrame(draw);
        w = c.width = window.innerWidth,
            h = c.height = window.innerHeight;
        $.globalCompositeOperation = 'source-over';
        $.fillStyle = 'hsla(0,0%,10%,1)';
        $.fillRect(0, 0, c.width, c.height);
        $.globalCompositeOperation = 'lighter';
        for (i = 0; i < 8;) {
            p = m[i++],
                p.x += p.dx,
                p.y += p.dy;
            if (p.x > 1 || p.x < 0) p.dx = -p.dx;
            if (p.y > 1 || p.y < 0) p.dy = -p.dy
        }
        for (x = 0; x <= w; x += s)
            for (y = 0; y <= h; y += s) {
                n = 0;
                for (i = 0; i < 8;)
                    p = m[i++],
                    _x = x - p.x * w,
                    _y = y - p.y * h,
                    d = Math.sqrt(_x * _x + _y * _y) / (h + w),
                    n += d ? Math.min(p.m / (d * d * 25), 2) : 2;
                $.fillStyle = 'hsla(' + x + ', 85%, 60%, 1)';
                $.beginPath(),
                    $.arc(x + .5, y + .5, Math.min(n, 9) * s * .5, 0, 9, 0),
                    $.fill()
            };
    }
    draw();

    window.addEventListener('resize', function() {
        if (w != window.innerWidth && h != window.innerHeight) {
            c.width = w = window.innerWidth;
            c.height = h = window.innerHeight;
        }
    }, false);
</script>