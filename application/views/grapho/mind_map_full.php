<?php
$xfile = '_0';
if (strlen($file) > 0)
	{
		$path = $_SERVER['SCRIPT_FILENAME'];
		$path = troca($path,'index.php','');
		$path = '';
		$arq = $path.'xml/flare_'.$file.'.csv';
		if (file_exists($arq))
			{
				$xfile = '_'.$file;
			}
	}
?>
<style>

.node circle {
  fill: #333388;
}

.node text {
  font: 12px sans-serif;
  color: red;
}

.node--internal circle {
  fill: #5555FF;
}

.node--internal text { 
  text-shadow: 0 1px 0 #fff, 0 -1px 0 #fff, 1px 0 0 #fff, -1px 0 0 #fff;
}

.link {
  fill: none;
  stroke: #555;
  stroke-opacity: 0.4;
  stroke-width: 1.5px;
}

</style>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <svg width="960" height="1400"></svg>
        </div>
    </div>
</div>
<script src="<?php echo base_url('js/d3.v4.min.js');?>"></script>
<script>

var svg = d3.select("svg"),
    width = +svg.attr("width"),
    height = +svg.attr("height"),
    g = svg.append("g").attr("transform", "translate(40,0)");

var tree = d3.tree()
    .size([height, width - 160]);

var stratify = d3.stratify()
    .parentId(function(d) { return d.id.substring(0, d.id.lastIndexOf(".")); });

d3.csv("<?php echo base_url('xml/flare'.$xfile.'.csv');?>", function(error, data) {
  if (error) throw error;


  var root = stratify(data)
      .sort(function(a, b) { return (a.height - b.height) || a.id.localeCompare(b.id); });

  var link = g.selectAll(".link")
    .data(tree(root).descendants().slice(1))
    .enter().append("path")
      .attr("class", "link")
      .attr("d", function(d) {
        return "M" + d.y + "," + d.x
            + "C" + (d.y + d.parent.y) / 2 + "," + d.x
            + " " + (d.y + d.parent.y) / 2 + "," + d.parent.x
            + " " + d.parent.y + "," + d.parent.x;
      });

  var node = g.selectAll(".node")
    .data(root.descendants())
    .enter().append("g")
      .attr("class", function(d) { return "node" + (d.children ? " node--internal" : " node--leaf"); })
      .attr("transform", function(d) { return "translate(" + d.y + "," + d.x + ")"; })

  node.append("circle")
      .attr("r", 2.5);

  node.append("text")
      .attr("dy", 3)
      .attr("x", function(d) { return d.children ? -8 : 8; })
      .style("text-anchor", function(d) { return d.children ? "end" : "start"; })
      .text(function(d) { return d.id.substring(d.id.lastIndexOf(".") + 1); });
});

</script>