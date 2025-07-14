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
    name: 'THESA',
    children: []
  };

  constructor() {}

  buildTreeFromConcept(conceptData: any): TreeNode {
    const node: TreeNode = {
      name: conceptData.label,
      children: [],
    };

    // Adiciona narrow (filhos)
    if (Array.isArray(conceptData.narrow) && conceptData.narrow.length > 0) {
      node.children = conceptData.narrow.map((n: any) => ({
        name: n.Term,
      }));
    }

    // Adiciona broader (como pai do conceito atual)
    if (Array.isArray(conceptData.broader) && conceptData.broader.length > 0) {
      return {
        name: conceptData.broader[0].Term,
        children: [node],
      };
    }

    return node;
  }

  ngOnInit(): void {
    this.data = this.buildTreeFromConcept(this.conceptData);
    this.createTree();
  }

  ngOnChanges(): void {
    if (this.conceptData) {
      this.data = this.buildTreeFromConcept(this.conceptData);
      this.createTree();
    }
  }

  createTree(): void {
    const element = this.treeContainer.nativeElement;

    // Limpa o gráfico anterior, se existir
    d3.select(element).selectAll('svg').remove();

    const width = 450;
    const height = 600;

    const margin = { top: 20, right: 120, bottom: 30, left: 90 };
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
      .attr('fill', 'none')
      .attr('stroke', '#555')
      .attr('stroke-opacity', 0.4)
      .attr('stroke-width', 1.5)
      .selectAll('path.link')
      .data(root.links())
      .enter()
      .append('path')
      .attr('class', 'link')
      .attr('d', (d: d3.HierarchyLink<TreeNode>) => {
        const link = d as unknown as d3.HierarchyPointLink<TreeNode>;
        return (
          d3
            .linkHorizontal<
              d3.HierarchyPointLink<TreeNode>,
              d3.HierarchyPointNode<TreeNode>
            >()
            .x((d) => d.y)
            .y((d) => d.x)(link) ?? ''
        );
      });
    //.attr('marker-end', 'url(#arrowhead)');

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
      .attr('r', 4)
      .attr('fill', '#ff0')
      .attr('stroke', '#333')
      .attr('stroke-width', 2);

    node
      .append('text')
      .attr('dy', (d) => (d.children ? -6 : 6))
      .attr('fill', '#333')
      .attr('stroke', 'none')
      .attr('x', (d) => (d.children ? -6 : 6))
      .attr('text-anchor', (d) => (d.children ? 'end' : 'start'))
      .style('font-size', '12px')
      .attr('paint-order', 'stroke')
      .text((d: d3.HierarchyNode<TreeNode>) => {
        const point = d as d3.HierarchyPointNode<TreeNode>;
        return point.data.name;
      });
  }
}
