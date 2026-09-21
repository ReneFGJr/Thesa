import { Component, computed, inject } from '@angular/core';
import { LanguageService } from '../../../000_core/service/language.service';
import { aboutTranslations } from './about-translations';

@Component({
  selector: 'app-about-us',
  standalone: false,
  templateUrl: './about-us.component.html',
  styleUrl: './about-us.component.scss',
})
export class AboutUsComponent {
  readonly language = inject(LanguageService);
  readonly labels = computed(() => aboutTranslations[this.language.currentLanguage()]);
  version = 'v1.0';
  year = new Date().getFullYear();
}
