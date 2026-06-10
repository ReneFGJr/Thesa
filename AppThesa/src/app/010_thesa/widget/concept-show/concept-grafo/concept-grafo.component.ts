import { Component, ElementRef, Input, ViewChild, OnInit, OnChanges } from '@angular/core';
import * as d3 from 'd3';

interface GraphNode extends d3.SimulationNodeDatum {
  id: string;
  name: string;
  type: 'main' | 'broader' | 'narrow' | 'related';
}

interface GraphLink extends d3.SimulationLinkDatum<GraphNode> {
  source: string | GraphNode;
  target: string | GraphNode;
  label: string;
  type: 'broader' | 'narrower' | 'related' | 'exactMatch' | 'closeMatch';
}

@Component({
  selector: 'app-concept-grafo',
  templateUrl: './concept-grafo.component.html',
  styleUrl: './concept-grafo.component.scss',
  standalone: false,
})
export class ConceptGrafoComponent implements OnInit, OnChanges {
  @Input() conceptData: any;

  @ViewChild('treeContainer', { static: true }) treeContainer!: ElementRef;

  nodes: GraphNode[] = [];
  links: GraphLink[] = [];

  constructor() {}

  buildGraphFromConcept(conceptData: any): { nodes: GraphNode[]; links: GraphLink[] } {
    const nodes: GraphNode[] = [];
    const links: GraphLink[] = [];
    const mainId = conceptData.id_c || conceptData.c_concept || 'main';

    // Adiciona o conceito principal
    nodes.push({
      id: mainId,
      name: conceptData.label || conceptData.prefLabel?.[0]?.Term || 'Conceito',
      type: 'main',
    });

    // Adiciona broader (conceitos mais amplos)
    if (Array.isArray(conceptData.broader) && conceptData.broader.length > 0) {
      conceptData.broader.forEach((broader: any, idx: number) => {
        const broaderId = broader.id || `broader-${idx}`;
        nodes.push({
          id: broaderId,
          name: broader.Term || broader.label || 'N/A',
          type: 'broader',
        });
        links.push({
          source: broaderId,
          target: mainId,
          label: 'broader',
          type: 'broader',
        });
      });
    }

    // Adiciona narrow (conceitos mais específicos)
    if (Array.isArray(conceptData.narrow) && conceptData.narrow.length > 0) {
      conceptData.narrow.forEach((narrow: any, idx: number) => {
        const narrowId = narrow.id || `narrow-${idx}`;
        nodes.push({
          id: narrowId,
          name: narrow.Term || narrow.label || 'N/A',
          type: 'narrow',
        });
        links.push({
          source: mainId,
          target: narrowId,
          label: 'narrower',
          type: 'narrower',
        });
      });
    }

    // Adiciona related (conceitos relacionados)
    if (Array.isArray(conceptData.relations) && conceptData.relations.length > 0) {
      conceptData.relations.forEach((related: any, idx: number) => {
        const relatedId = related.id || `related-${idx}`;
        nodes.push({
          id: relatedId,
          name: related.Term || related.label || 'N/A',
          type: 'related',
        });
        links.push({
          source: mainId,
          target: relatedId,
          label: 'related',
          type: 'related',
        });
      });
    }

    return { nodes, links };
  }

  ngOnInit(): void {
    if (this.conceptData) {
      const graph = this.buildGraphFromConcept(this.conceptData);
      this.nodes = graph.nodes;
      this.links = graph.links;
      this.createGraph();
    }
  }

  ngOnChanges(): void {
    if (this.conceptData) {
      const graph = this.buildGraphFromConcept(this.conceptData);
      this.nodes = graph.nodes;
      this.links = graph.links;
      this.createGraph();
    }
  }

  private getColorByType(type: string): string {
    const colorMap: { [key: string]: string } = {
      main: '#9b59b6',
      broader: '#95a5a6',
      narrow: '#95a5a6',
      related: '#95a5a6',
    };
    return colorMap[type] || '#95a5a6';
  }

  private getLinkColor(type: string): string {
    const colorMap: { [key: string]: string } = {
      broader: '#7f8c8d',
      narrower: '#7f8c8d',
      related: '#7f8c8d',
      exactMatch: '#7f8c8d',
      closeMatch: '#7f8c8d',
    };
    return colorMap[type] || '#7f8c8d';
  }

  createGraph(): void {
    const element = this.treeContainer.nativeElement;
    d3.select(element).selectAll('svg').remove();

    // Se não há dados, retorna
    if (this.nodes.length === 0) {
      return;
    }

    const width = 800;
    const height = 600;

    const svg = d3
      .select(element)
      .append('svg')
      .attr('width', width)
      .attr('height', height)
      .attr('viewBox', [0, 0, width, height]);

    // Define arrow markers para diferentes tipos de relações
    const defs = svg.append('defs');

    ['broader', 'narrower', 'related'].forEach((type) => {
      defs
        .append('marker')
        .attr('id', `arrow-${type}`)
        .attr('markerWidth', 10)
        .attr('markerHeight', 10)
        .attr('refX', 25)
        .attr('refY', 3)
        .attr('orient', 'auto')
        .append('path')
        .attr('d', 'M0,0 L0,6 L9,3 z')
        .attr('fill', this.getLinkColor(type));
    });

    // Cria a simulação de força
    const simulation = d3
      .forceSimulation<GraphNode>(this.nodes)
      .force(
        'link',
        d3
          .forceLink<GraphNode, GraphLink>(this.links)
          .id((d) => d.id)
          .distance(120)
          .strength(0.5)
      )
      .force('charge', d3.forceManyBody().strength(-500))
      .force('center', d3.forceCenter(width / 2, height / 2))
      .force('collide', d3.forceCollide().radius(50));

    // Cria o container para os links
    const linkGroup = svg.append('g').attr('class', 'links');

    // Desenha os links
    const links = linkGroup
      .selectAll('line')
      .data(this.links)
      .enter()
      .append('g');

    // Adiciona as linhas
    links
      .append('line')
      .attr('stroke', (d) => this.getLinkColor(d.type))
      .attr('stroke-width', 2)
      .attr('stroke-opacity', 0.6)
      .attr('marker-end', (d) => `url(#arrow-${d.type})`);

    // Adiciona labels nas linhas
    links
      .append('text')
      .attr('font-size', '11px')
      .attr('fill', '#666')
      .attr('text-anchor', 'middle')
      .text((d) => d.label);

    // Cria o container para os nós
    const nodeGroup = svg.append('g').attr('class', 'nodes');

    // Desenha os nós
    const nodes = nodeGroup
      .selectAll('g')
      .data(this.nodes)
      .enter()
      .append('g')
      .attr('class', 'node')
      .call(
        d3
          .drag<SVGGElement, GraphNode>()
          .on('start', (event, d) => {
            if (!event.active) simulation.alphaTarget(0.3).restart();
            d.fx = d.x;
            d.fy = d.y;
          })
          .on('drag', (event, d) => {
            d.fx = event.x;
            d.fy = event.y;
          })
          .on('end', (event, d) => {
            if (!event.active) simulation.alphaTarget(0);
            d.fx = null;
            d.fy = null;
          })
      );

    // Adiciona círculos nos nós
    nodes
      .append('circle')
      .attr('r', (d) => (d.type === 'main' ? 25 : 18))
      .attr('fill', (d) => this.getColorByType(d.type))
      .attr('stroke', '#333')
      .attr('stroke-width', 2);

    // Adiciona texto nos nós
    nodes
      .append('text')
      .attr('x', 0)
      .attr('y', 0)
      .attr('text-anchor', 'middle')
      .attr('dominant-baseline', 'central')
      .style('font-size', (d) => (d.type === 'main' ? '15px' : '14px'))
      .style('font-weight', (d) => (d.type === 'main' ? 'bold' : 'bold'))
      .style('fill', '#000')
      .style('pointer-events', 'none')
      .text((d) => {
        // Limita o texto para não ficar muito grande
        const text = d.name;
        return text.length > 15 ? text.substring(0, 12) + '...' : text;
      });

    // Atualiza as posições durante a simulação
    simulation.on('tick', () => {
      links
        .selectAll('line')
        .attr('x1', (d: any) => (d.source as GraphNode).x!)
        .attr('y1', (d: any) => (d.source as GraphNode).y!)
        .attr('x2', (d: any) => (d.target as GraphNode).x!)
        .attr('y2', (d: any) => (d.target as GraphNode).y!)        ;

      links
        .selectAll('text')
        .attr(
          'x',
          (d: any) =>
            ((d.source as GraphNode).x! + (d.target as GraphNode).x!) / 2,
        )
        .attr(
          'y',
          (d: any) =>
            ((d.source as GraphNode).y! + (d.target as GraphNode).y!) / 2,
        )
        .style('font-size', (d) => ('13px'));

      nodes.attr('transform', (d: any) => `translate(${d.x},${d.y})`);
    });
  }
}
