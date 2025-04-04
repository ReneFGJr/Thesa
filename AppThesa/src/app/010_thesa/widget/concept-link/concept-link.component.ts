import { Component, Input } from '@angular/core';
import { environment } from '../../../../environments/environment';

@Component({
  selector: 'app-concept-link',
  templateUrl: './concept-link.component.html',
  styleUrl: './concept-link.component.scss',
})
export class ConceptLinkComponent {
  @Input() conceptLink: any = null;
  text: string = '';
  icon: string = '';
  link: string = '';

  ngOnChanges() {
    if (this.conceptLink) {
      this.text = 'thesa:' + this.conceptLink;
      this.link = environment.Url+'/c/' + this.conceptLink;
    }
  }

  copyToClipboard(text: string) {
    navigator.clipboard
      .writeText(text)
      .then(() => {
        console.log('Link copiado:', text);
        // Opcional: exibir toast ou mensagem visual
        alert('Link copiado para a área de transferência!');
      })
      .catch((err) => {
        console.error('Erro ao copiar:', err);
      });
  }
}
