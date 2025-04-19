import { Component, Input } from '@angular/core';

@Component({
  selector: 'app-image-play',
  templateUrl: './image-play.component.html',
  styleUrl: './image-play.component.scss'
})
export class ImagePlayComponent {
  @Input() medias: Array<any> | any;

}
