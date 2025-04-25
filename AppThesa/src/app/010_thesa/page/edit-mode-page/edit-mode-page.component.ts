import { ServiceStorageService } from './../../../000_core/service/service-storage.service';
import { Component } from '@angular/core';
import { ActivatedRoute, Router } from '@angular/router';

@Component({
    selector: 'app-edit-mode-page',
    templateUrl: './edit-mode-page.component.html',
    styleUrl: './edit-mode-page.component.scss',
    standalone: false
})
export class EditModePageComponent {
  data: any;
  editModeLocal: string = 'false';
  thesaID: number = 0;

  constructor(
    private route: Router, // rota pra ler params
    private router: ActivatedRoute,
    private storageService: ServiceStorageService
  ) {}

  ngOnInit() {
    this.editModeLocal = this.router.snapshot.params['id']
    this.thesaID = +this.router.snapshot.params['thesaID']

    this.storageService.setEditMode(this.editModeLocal);
    this.route.navigate(['/thesa', this.thesaID]);
  }
}
