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
          this.meta = {
            thesa: this.data?.th_name,
            time: this.data?.time,
          };

          this.serviceThesa.api_post('systematic/' + this.id, []).subscribe(
            (res) => {
              this.tree = res;
              console.log('Systemic Index', this.tree);
              const parsed = this.parse(this.tree.map); // Não use .map aqui
              this.treeText = JSON.stringify(parsed, null, 2);},
            (error) => error
          );
        },
        (error) => error
      );
    });
  }

  private parse(src: Data | string): Data {
    if (!src) return { map: [] };
    if (typeof src === 'string') {
      try {
        console.log('====', JSON.parse(src));

        return JSON.parse(src);
      } catch {
        return { map: [] };
      }
    }
    return src;
  }

}
