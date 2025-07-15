import { Component } from '@angular/core';
import { FormBuilder, FormGroup } from '@angular/forms';

@Component({
  selector: 'app-theme-upload',
  standalone: false,
  templateUrl: './theme-upload.component.html',
  styleUrl: './theme-upload.component.scss',
})
export class ThemeUploadComponent {
  form: FormGroup;
  imagePreview: string | ArrayBuffer | null = null;

  constructor(private fb: FormBuilder) {
    this.form = this.fb.group({
      image: [null],
    });
  }

  onFileChange(event: any) {
    const file = event.target.files[0];
    if (file) {
      this.form.patchValue({ image: file });
      this.form.get('image')?.updateValueAndValidity();

      const reader = new FileReader();
      reader.onload = () => {
        this.imagePreview = reader.result;
      };
      reader.readAsDataURL(file);
    }
  }

  submit() {
    if (this.form.valid) {
      const file = this.form.get('image')?.value;
      console.log('Imagem pronta para envio:', file);
      // Aqui você pode usar um serviço para enviar o arquivo ao servidor
    }
  }
}
