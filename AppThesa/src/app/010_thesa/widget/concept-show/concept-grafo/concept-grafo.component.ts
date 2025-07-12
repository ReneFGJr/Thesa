import { Component, ElementRef, Input, ViewChild } from '@angular/core';
import * as d3 from 'd3';

interface TreeNode {
  name: string;
  children?: TreeNode[];
}

@Component({
  selector: 'app-concept-grafo',
  templateUrl: './concept-grafo.component.html',
  styleUrl: './concept-grafo.component.scss',
  standalone: false,
})
export class ConceptGrafoComponent {
  @Input() conceptData: any;

  @ViewChild('treeContainer', { static: true }) treeContainer!: ElementRef;

  data: TreeNode = {
    name: 'Family',
    children: [
      {
        name: 'Ascaphidae',
      },
      {
        name: 'Leopelmatidae',
      },
      {
        name: 'Bombinatoridae',
        children: [
          { name: 'Alytidae (Alytinae)' },
          { name: 'Alytidae (Discoglossinae)' },
        ],
      },
      {
        name: 'Rhinophrynidae',
      },
      {
        name: 'Pipidae',
        children: [
          { name: 'Pipidae (Pipinae)' },
          { name: 'Pipidae (Xenopodinae)' },
        ],
      },
    ],
  };

  constructor() {}

  ngOnInit(): void {
    this.createTree();
  }

  createTree(): void {
    const element = this.treeContainer.nativeElement;
    const width = 400;
    const height = 600;

    const margin = { top: 20, right: 10, bottom: 30, left: 10 };
    const innerWidth = width - margin.left - margin.right;
    const innerHeight = height - margin.top - margin.bottom;

    const svg = d3
      .select(element)
      .append('svg')
      .attr('width', width)
      .attr('height', height)
      .append('g')
      .attr('transform', `translate(${margin.left},${margin.top})`);

    const root = d3.hierarchy<TreeNode>(this.data);

    // Inverte x/y para orientação horizontal
    const treeLayout = d3.tree<TreeNode>().size([innerHeight, innerWidth]);
    treeLayout(root);

    // Desenhar linhas (links)
    svg
      .selectAll('path.link')
      .data(root.links())
      .enter()
      .append('path')
      .attr('class', 'link')
      .attr('fill', 'none')
      .attr('stroke', '#555')
      .attr('stroke-width', 1)
      .attr('d', (d: d3.HierarchyLink<TreeNode>) =>
        d3.line()([
          [d.source.y ?? 0, d.source.x ?? 0], // trocado
          [d.target.y ?? 0, d.target.x ?? 0], // trocado
        ])
      )
      .attr('marker-end', 'url(#arrowhead)');
      ;

    // Desenhar nós
    const node = svg
      .selectAll('g.node')
      .data(root.descendants())
      .enter()
      .append('g')
      .attr('class', 'node')
      .attr('transform', (d: d3.HierarchyNode<TreeNode>) => {
        const point = d as d3.HierarchyPointNode<TreeNode>;
        return `translate(${point.y ?? 0},${point.x ?? 0})`; // trocado
      });

    node
      .append('circle')
      .attr('r', 8)
      .attr('fill', '#ff0')
      .attr('stroke', '#0d6efd')
      .attr('stroke-width', 2);

    node
      .append('text')
      .attr('dy', '.35em')
      .attr('x', 12)
      .style('font-size', '10px')
      .text((d: d3.HierarchyNode<TreeNode>) => {
        const point = d as d3.HierarchyPointNode<TreeNode>;
        return point.data.name;
      });
  }
}
