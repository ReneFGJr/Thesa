import { Component, Input } from '@angular/core';

@Component({
  selector: 'app-video-play',
  templateUrl: './video-play.component.html'
})
export class VideoPlayComponent {
  @Input() medias: Array<any> | any;

  ngOnChanges() {

  }
}
