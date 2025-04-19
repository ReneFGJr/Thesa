import { ElementRef, Injectable, ViewChild } from '@angular/core';
import { Offcanvas } from 'bootstrap';

@Injectable({
  providedIn: 'root',
})
export class PainelService {
  offcanvasInstance!: Offcanvas;
  @ViewChild('offcanvasNovo', { static: true }) offcanvasEl!: ElementRef;

  constructor() {}

  openConceptPanel(element: string = 'popupConcept') {
    if (!element) {
      return;
    }
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
