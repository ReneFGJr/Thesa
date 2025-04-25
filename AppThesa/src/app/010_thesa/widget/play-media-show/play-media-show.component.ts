import { Component, Input } from '@angular/core';

@Component({
    selector: 'app-video-play-show',
    templateUrl: './play-media-show.component.html',
    standalone: false
})
export class VideoPlayShowComponent {
@Input() medias: Array<any> | any;

}
