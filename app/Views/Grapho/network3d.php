<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Rede 3D - Tesauro</title>

    <script src="https://unpkg.com/3d-force-graph"></script>

    <style>
        body {
            margin: 0;
            overflow: hidden;
            background: #111;
            font-family: Arial;
        }

        #graph {
            width: 100vw;
            height: 100vh;
        }

        #title {
            position: absolute;
            top: 10px;
            left: 10px;
            color: #fff;
            z-index: 10;
        }
    </style>
</head>

<body>

    <div id="title">
        <h3>Visualização 3D da Rede</h3>
    </div>

    <div id="graph"></div>

    <script>
        // =============================
        // 1️⃣ COLE AQUI O TEXTO .NET
        // =============================
        const pajekData = `<?= addslashes($netContent) ?>`;


        // =============================
        // 2️⃣ Parser Pajek
        // =============================
        function parsePajek(text) {

            const lines = text.split('\n');
            let mode = '';
            let nodes = [];
            let links = [];

            for (let line of lines) {

                line = line.trim();

                if (line.startsWith('*Vertices')) {
                    mode = 'vertices';
                    continue;
                }

                if (line.startsWith('*Arcs') || line.startsWith('*Edges')) {
                    mode = 'edges';
                    continue;
                }

                if (!line) continue;

                if (mode === 'vertices') {
                    const match = line.match(/^(\d+)\s+"(.+)"$/);
                    if (match) {
                        nodes.push({
                            id: match[1],
                            name: match[2]
                        });
                    }
                }

                if (mode === 'edges') {
                    const parts = line.split(' ');
                    if (parts.length >= 2) {
                        links.push({
                            source: parts[0],
                            target: parts[1]
                        });
                    }
                }
            }

            return {
                nodes,
                links
            };
        }

        const graphData = parsePajek(pajekData);


        // =============================
        // 3️⃣ Renderização 3D
        // =============================
        const Graph = ForceGraph3D()
            (document.getElementById('graph'))
            .graphData(graphData)

            .nodeLabel(node => node.name)
            .nodeAutoColorBy('id')

            .linkOpacity(0.3)
            .linkDirectionalParticles(1)
            .linkDirectionalParticleWidth(1)

            .backgroundColor('#111')

            .nodeRelSize(4)

            .onNodeClick(node => {
                Graph.cameraPosition({
                        x: node.x * 1.5,
                        y: node.y * 1.5,
                        z: node.z * 1.5
                    },
                    node,
                    2000
                );
            });
    </script>

</body>

</html>