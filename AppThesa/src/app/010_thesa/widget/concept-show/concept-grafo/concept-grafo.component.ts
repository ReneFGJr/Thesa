import { Component, Input } from '@angular/core';
import { Node, Edge } from '@swimlane/ngx-graph';

@Component({
  selector: 'app-concept-grafo',
  templateUrl: './concept-grafo.component.html',
  styleUrl: './concept-grafo.component.scss',
  standalone: false,
})
export class ConceptGrafoComponent {
  @Input() conceptData: any;

nodes: Node[] = [];
  links: Edge[] = [];

  ngOnInit(): void {
    const concept = this.conceptData;

    // Conceito principal
    this.nodes.push({
      id: concept.c_concept,
      label: concept.prefLabel[0]?.Term,
    });

    // Conceitos mais amplos
    for (let b of concept.broader) {
      this.nodes.push({
        id: 'B' + b.id,
        label: b.Term,
      });

      this.links.push({
        id: 'link-b-' + b.id,
        source: 'B' + b.id,
        target: concept.c_concept,
        label: 'broader',
      });
    }

    // Conceitos mais específicos
    for (let n of concept.narrow) {
      this.nodes.push({
        id: 'N' + n.id,
        label: n.Term,
      });

      this.links.push({
        id: 'link-n-' + n.id,
        source: concept.c_concept,
        target: 'N' + n.id,
        label: 'narrower',
      });
    }
  }
}
