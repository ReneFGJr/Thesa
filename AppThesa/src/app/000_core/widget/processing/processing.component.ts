import { Component, Input } from '@angular/core';

@Component({
    selector: 'app-processing',
    templateUrl: './processing.component.html',
    styleUrl: './processing.component.scss',
    standalone: false
})
export class ProcessingComponent {
  @Input() message: string = 'Carregando...';
  loadingImage: string = 'assets/img/loading-2.gif';

  ngOnInit() {
    if (
      this.message == null ||
      this.message == undefined ||
      this.message == ''
    ) {
      this.message = 'Carregando...';
    }
  }
}
