import { Component, Input } from '@angular/core';
import { ServiceThesaService } from '../../../000_core/service/service-thesa.service';
import { ServiceStorageService } from '../../../000_core/service/service-storage.service';
import { ActivatedRoute } from '@angular/router';
import { LanguageService } from '../../../000_core/service/language.service';
import { environment } from '../../../../environments/environment';

@Component({
    selector: 'app-pdf-export',
    templateUrl: './export.component.html',
    styleUrl: './export.component.scss',
    standalone: false
})
export class ExportPDFComponent {
  data: any = null;
  isLoading = false;
  loadError = false;
  thesa: any;
  id: number = 0;
  termID: number = 0;
  @Input() editMode: boolean = false;
  constructor(
    public readonly language: LanguageService,
    private serviceThesa: ServiceThesaService,
    private serviceStorage: ServiceStorageService,
    private router: ActivatedRoute
  ) {}

  get authors(): Array<{ fullname: string; affiliation?: string }> {
    const entry = Array.isArray(this.data?.info)
      ? this.data.info.find((item: any) => item?.class === 'Authors')
      : null;
    return Array.isArray(entry?.description?.members) ? entry.description.members : [];
  }

  get infoSections(): Array<{ class: string; description: string }> {
    if (!Array.isArray(this.data?.info)) return [];
    return this.data.info.filter(
      (item: any) => item?.class !== 'Title'
        && item?.class !== 'Authors'
        && typeof item?.description === 'string'
        && item.description.trim() !== ''
    );
  }

  infoLabel(name: string): string {
    const labels = this.language.labels();
    switch (name) {
      case 'Introduction': return labels.exportIntroduction;
      case 'Language': return labels.languages;
      case 'Audience': return labels.audience;
      default: return name;
    }
  }

  statusLabel(): string {
    const labels = this.language.labels();
    switch (Number(this.data?.th_status)) {
      case 1: return labels.publicStatus;
      case 2: return labels.privateStatus;
      case 9: return labels.cancelledStatus;
      default: return '';
    }
  }

  statusClass(): string {
    switch (Number(this.data?.th_status)) {
      case 1: return 'bg-success';
      case 2: return 'bg-secondary';
      default: return 'bg-warning text-dark';
    }
  }

  exportUrl(format: 'xml' | 'turtle' | 'json' | 'txt'): string {
    const url = `${environment.apiUrl}/export/${this.id}/${format}`;
    const apiKey = this.serviceStorage.get('apikey');
    return Number(this.data?.th_status) !== 1 && apiKey
      ? `${url}?apikey=${encodeURIComponent(apiKey)}`
      : url;
  }

  ngOnInit() {
    this.router.params.subscribe((params) => {
      this.id = +params['id'];
      this.data = null;
      this.loadError = false;
      this.isLoading = true;

      this.serviceThesa.api_post('th/' + this.id, []).subscribe({
        next: (res) => {
          const details = res as any;
          this.data = details?.id_th ? details : null;
          this.loadError = this.data === null;
          this.isLoading = false;
        },
        error: () => {
          this.loadError = true;
          this.isLoading = false;
        }
      });
    });
  }
}
