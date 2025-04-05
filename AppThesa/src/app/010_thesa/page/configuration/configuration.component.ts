import { environment } from './../../../../environments/environment';
import { Component } from '@angular/core';
import { ServiceThesaService } from '../../../000_core/service/service-thesa.service';
import { ServiceStorageService } from '../../../000_core/service/service-storage.service';
import { ActivatedRoute } from '@angular/router';
import { AngularEditorConfig } from '@kolkov/angular-editor';



@Component({
  selector: 'app-configuration',
  templateUrl: './configuration.component.html',
  styleUrl: './configuration.component.scss',
})
export class ConfigurationComponent {
  data: any;
  thesa: any;
  id: number = 0;
  termID: number = 0;
  url: string = '';
  constructor(
    private serviceThesa: ServiceThesaService,
    private serviceStorage: ServiceStorageService,
    private router: ActivatedRoute,
  ) {}

  sections = [
    { id: 'title', title: 'Tesauro' },
    { id: 'descript', title: 'Descrição' },
    { id: 'methodology', title: 'Metodologia' },
    { id: 'icon', title: 'Thema e Incones' },
    { id: 'members', title: 'membros' },
  ];

  selectedSection = 'title';

  selectSection(id: string) {
    this.selectedSection = id;
  }

  ngOnInit() {
    this.url = environment.apiUrl;
    this.data = this.router.params.subscribe((params) => {
      this.id = +params['id']; // (+) converts string 'id' to a number

      this.serviceThesa.api_post('th/' + this.id, []).subscribe(
        (res) => {
          this.data = res;
        },
        (error) => error
      );
    });
  }
}
