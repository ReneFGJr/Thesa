import { LanguageService } from '../../../000_core/service/language.service';
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
    public readonly language: LanguageService,
    private serviceThesa: ServiceThesaService,
    private serviceStorage: ServiceStorageService,
    private router: ActivatedRoute
  ) {}

  get sections() { return [
    { id: 'Title', title: 'Thesa' },
    { id: 'Type', title: this.language.labels().thesaType },
    { id: 'Descript', title: this.language.labels().description },
    { id: 'Methodology', title: this.language.labels().methodology },
    { id: 'Audience', title: this.language.labels().audience },
    { id: 'Language', title: this.language.labels().languages },
    { id: 'License', title: this.language.labels().license },
    { id: 'Visibility', title: this.language.labels().visibility },
    { id: 'Themes', title: this.language.labels().themeIcons },
    { id: 'Relations', title: this.language.labels().relationTypes },
    { id: 'Members', title: this.language.labels().members },
  ]; }

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
