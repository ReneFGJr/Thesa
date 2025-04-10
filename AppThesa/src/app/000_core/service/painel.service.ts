import { Injectable } from '@angular/core';
import { Offcanvas } from 'bootstrap';

@Injectable({
  providedIn: 'root',
})
export class PainelService {
  constructor() {}

  openConceptPanel(element: string = 'popupConcept') {
    const el = document.getElementById(element);
    if (el) {
      const bsCanvas = new Offcanvas(el);
      bsCanvas.show();
    }
  }

  closeConceptPanel(element: string = 'popupConcept') {
    const el = document.getElementById(element);
    if (el && el.classList.contains('show')) {
      el.classList.remove('show');

      // Remove o backdrop do offcanvas
      const backdrop = document.querySelector('.offcanvas-backdrop');
      if (backdrop) {
        backdrop.remove();
      }

      // Também remove a classe do body, se necessário
      document.body.classList.remove(
        'offcanvas-backdrop',
        'offcanvas-backdrop-open',
        'modal-open'
      );
    }
  }
}
