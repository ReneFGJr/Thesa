import { environment } from './../../../../environments/environment';
import { Component } from '@angular/core';
import { ServiceThesaService } from '../../../000_core/service/service-thesa.service';
import { ServiceStorageService } from '../../../000_core/service/service-storage.service';
import { ActivatedRoute } from '@angular/router';

@Component({
  selector: 'app-configuration',
  templateUrl: './configuration.component.html',
  styleUrl: './configuration.component.scss',
  standalone: false,
})
export class ConfigurationComponent {
  data: any;
  thesa: any;
  id: number = 0;
  termID: number = 0;
  url: string = '';
  editMode: boolean = false;

  constructor(
    private serviceThesa: ServiceThesaService,
    private serviceStorage: ServiceStorageService,
    private router: ActivatedRoute
  ) {}

  sections = [
    { id: 'Title', title: 'Thesa' },
    { id: 'Type', title: 'Tipo do Thesa' },
    { id: 'Descript', title: 'Descrição' },
    { id: 'Methodology', title: 'Metodologia' },
    { id: 'Audience', title: 'Público Alvo' },
    { id: 'Language', title: 'Idiomas' },
    { id: 'License', title: 'Licença' },
    { id: 'Visibility', title: 'Visibilidade' },
    { id: 'Themes', title: 'Thema e Incones' },
    { id: 'Relations', title: 'Tipos de Relações' },
    { id: 'Members', title: 'Membros' },
  ];

  selectedSection = 'Title';

  selectSection(id: string) {
    this.selectedSection = id;
  }

  update() {
    this.serviceThesa.api_post('th/' + this.id, []).subscribe(
      (res) => {
        this.data = res;
        /* Edição */
        if (this.data.editMode == 'allow') {
          this.editMode = true;
        } else {
          this.data = false;
        }
      },
      (error) => error
    );
  }

  ngOnInit() {
    this.url = environment.apiUrl;
    this.data = this.router.params.subscribe((params) => {
      this.id = +params['id']; // (+) converts string 'id' to a number
      this.update();
    });
  }
}
