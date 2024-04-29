import { Component, Input } from '@angular/core';

@Component({
  selector: 'app-media-video',
  templateUrl: './video.component.html'
})
export class VideoComponent {
  @Input() public video:Array<any> | any

  ngOnInit()
    {

    }
}
