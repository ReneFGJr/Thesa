import { Component, Input } from '@angular/core';

@Component({
  selector: 'app-theme-config',
  standalone: false,
  templateUrl: './theme-config.component.html',
  styleUrl: './theme-config.component.scss',
})
export class ThemeConfigComponent {
  @Input() thesaID: number = 0;
  @Input() thesa: any;

  onImageUpdate(imageUrl: string): void {
    // Handle the image update logic here
    console.log('Image updated X:', imageUrl);
    this.thesa.icone = imageUrl; // Update the thesa object with the new image URL
    // You can also update the thesa object or perform other actions as needed
  }
}
