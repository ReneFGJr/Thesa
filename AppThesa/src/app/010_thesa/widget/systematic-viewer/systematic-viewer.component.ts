import { Component, Input } from '@angular/core';

interface ThesaNode {
  id: string;
  name?: string;
  lang?: string;
  children?: ThesaNode[];
}

@Component({
  selector: 'app-systematic-viewer',
  standalone: false,
  templateUrl: './systematic-viewer.component.html',
  styleUrl: './systematic-viewer.component.scss',
})
export class SystematicViewerComponent {
  @Input() map: ThesaNode[] = [];

  textOutput: string = '';

  ngOnInit() {
    this.textOutput = this.buildText(this.map);
  }

  buildText(nodes: ThesaNode[], depth: number = 0): string {
    let output = '';
    const indent = (n: number) => '  '.repeat(n); // 2 spaces per level

    for (const node of nodes) {
      output += `${indent(depth)}- ${node.name}\n`;
      if (node.children && node.children.length > 0) {
        output += this.buildText(node.children, depth + 1);
      }
    }

    return output;
  }
}
