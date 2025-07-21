import { ServiceThesaService } from './../../../../000_core/service/service-thesa.service';
import { HttpClient, HttpEvent, HttpEventType } from '@angular/common/http';
import { Component, Input } from '@angular/core';
import { environment } from '../../../../../environments/environment';

@Component({
  selector: 'app-theme-upload',
  standalone: false,
  templateUrl: './theme-upload.component.html',
  styleUrl: './theme-upload.component.scss',
})
export class ThemeUploadComponent {
  selectedFile: File | null = null;
  previewUrl: string | ArrayBuffer | null = null;
  uploadProgress: number = 0;
  uploadSuccess: boolean | null = null;
  data: Array<any> | any;
  @Input() thesaID: number = 0; // ID do Thesa, pode ser passado como input

  constructor(private http: HttpClient, private ServiceThesa: ServiceThesaService) {}

  onFileSelected(event: any): void {
    const file = event.target.files[0];
    if (file && file.type.startsWith('image/')) {
      this.selectedFile = file;

      const reader = new FileReader();
      reader.onload = (e) => (this.previewUrl = reader.result);
      reader.readAsDataURL(file);
    } else {
      alert('Por favor, selecione uma imagem válida.');
    }
  }

  uploadImage(): void {
    if (!this.selectedFile) return;

    let dt = {
      fileUpload: this.previewUrl as string,
      thesaID: this.thesaID, // Substitua pelo ID real do Thesa
    };

    console.log('Selected file:', dt);

    const url = 'uploadSchema';
    this.ServiceThesa.api_post(url, dt).subscribe(
      (res) => {
        this.data = res;
        console.log('Upload successful:', this.data);
      },
      (error) => error
    );
  }
}
