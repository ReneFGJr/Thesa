import { Component, SimpleChanges } from '@angular/core';
import { ServiceThesaService } from '../../../000_core/service/service-thesa.service';
import { ServiceStorageService } from '../../../000_core/service/service-storage.service';
import { ActivatedRoute } from '@angular/router';

export interface ThesaNode {
  id: string;
  name?: string;
  lang?: string;
  children?: ThesaNode[];
}
export interface ThesaData {
  thesa?: string;
  topConcepts?: any[];
  map: ThesaNode[];
  time?: string;
}

type Node = { id: string; name?: string; lang?: string; children?: Node[] };
type Data = { thesa?: string; topConcepts?: any[]; map: Node[]; time?: string };


@Component({
  selector: 'app-index-systemic',
  templateUrl: './index-systemic.component.html',
  styleUrl: './index-systemic.component.scss',
  standalone: false,
})
export class IndexSystemicComponent {
  concepts: any;
  data: any;
  tree: any;
  thesa: any;
  id: number = 0;
  termID: number = 0;
  editMode: boolean = false;
  treeText: string = '';
  meta: { thesa?: string; time?: string } | null = null;

  showIds: boolean = true;
  showLang: boolean = true;

  constructor(
    private serviceThesa: ServiceThesaService,
    private serviceStorage: ServiceStorageService,
    private router: ActivatedRoute
  ) {}

  ngOnInit() {
    this.ngOnChanges({});
  }

  ngOnChanges(_: SimpleChanges) {
    this.data = this.router.params.subscribe((params) => {
      this.id = +params['id']; // (+) converts string 'id' to a number

      this.serviceThesa.api_post('th/' + this.id, []).subscribe(
        (res) => {
          this.data = res;
          if (this.data.editMode == 'allow') {
            this.editMode = true;
          } else {
            this.editMode = false;
          }
        },
        (error) => error
      );
    });

    this.serviceThesa.api_post('systematic/' + this.id, []).subscribe(
      (res) => {
        this.tree = res;
        console.log('Systematic Tree:', this.tree?.map);
        const parsed = this.parse(this.tree?.map ?? []);
        console.log('Parsed Tree:', this.tree.thesa, this.tree.time);
        this.meta = { thesa: parsed.thesa, time: parsed.time };
        this.treeText = this.buildForestText(parsed.map ?? []);
      },
      (error) => error
    );
  }

  private parse(src: Data | string): Data {
    if (!src) return { map: [] };
    if (typeof src === 'string') {
      try {
        return JSON.parse(src);
      } catch {
        return { map: [] };
      }
    }
    return src;
  }

  private buildForestText(forest: Node[]): string {
    const out: string[] = [];
    forest.forEach((root, idx) => {
      this.renderRoot(root, out);
      if (idx < forest.length - 1) out.push(''); // linha em branco entre árvores
    });
    return out.join('\n');
  }

  private label(n: Node): string {
    const parts: string[] = [];
    if (this.showIds && n.id !== undefined) parts.push(`[${n.id}]`);
    if (n.name) parts.push(n.name);
    if (this.showLang && n.lang) parts.push(`{${n.lang}}`);
    return parts.join(' ');
  }

  /** Linha do nó raiz sem conector; filhos com conectores */
  private renderRoot(n: Node, out: string[]) {
    out.push(this.label(n));
    const kids = Array.isArray(n.children) ? n.children : [];
    kids.forEach((child, i) =>
      this.renderChild(child, '', i === kids.length - 1, out)
    );
  }

  private renderChild(n: Node, prefix: string, isLast: boolean, out: string[]) {
    out.push(`${prefix}${isLast ? '└─ ' : '├─ '}${this.label(n)}`);
    const kids = Array.isArray(n.children) ? n.children : [];
    const nextPrefix = prefix + (isLast ? '   ' : '│  ');
    kids.forEach((child, i) =>
      this.renderChild(child, nextPrefix, i === kids.length - 1, out)
    );
  }
}
