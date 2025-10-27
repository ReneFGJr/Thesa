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
    }

    // ✅ Remove backdrop se existir
    const backdrop = document.querySelector('.offcanvas-backdrop');
    if (backdrop) {
      backdrop.remove();
    }

    // ✅ Remove classes residuais
    document.body.classList.remove(
      'offcanvas-backdrop',
      'offcanvas-backdrop-open',
      'modal-open'
    );

    // ✅ Restaura o scroll do body
    document.body.style.overflow = '';
    document.body.style.paddingRight = '';

    // ✅ Corrige possíveis travas visuais no HTML também (caso o Bootstrap tenha aplicado)
    const html = document.documentElement;
    html.style.overflow = '';
    html.classList.remove('offcanvas-backdrop', 'modal-open');
  }
}
