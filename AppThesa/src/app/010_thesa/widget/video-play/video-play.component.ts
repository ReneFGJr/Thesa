import { Component, Input } from '@angular/core';

@Component({
  selector: 'app-video-play',
  templateUrl: './video-play.component.html',
  styleUrl: './video-play.component.scss',
})
export class VideoPlayComponent {
  @Input() medias: Array<any> | any;

  ngOnChanges() {
    console.log(this.medias);
  }
}
