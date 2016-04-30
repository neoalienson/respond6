import {Component, EventEmitter, Input, Output} from 'angular2/core';
import {CanActivate} from 'angular2/router';
import {tokenNotExpired} from 'angular2-jwt/angular2-jwt';
import {GalleryImageService} from '/app/shared/services/gallery-image.service';

@Component({
    selector: 'respond-remove-gallery-image',
    templateUrl: './app/shared/components/galleries/remove-gallery-image/remove-gallery-image.component.html',
    providers: [GalleryImageService]
})

@CanActivate(() => tokenNotExpired())

export class RemoveGalleryImageComponent {

  routes;
  errorMessage;

  // model to store
  model;

  _visible: boolean = false;

  // visible input
  @Input()
  set visible(visible: boolean){

    // set visible
    this._visible = visible;

  }

  get visible() { return this._visible; }

  // image input
  @Input()
  set image(image){

    // set visible
    this.model = image;

  }

  // gallery input
  @Input() gallery;

  // outputs
  @Output() onCancel = new EventEmitter<any>();
  @Output() onUpdate = new EventEmitter<any>();

  constructor (private _galleryImageService: GalleryImageService) {}

  /**
   * Init
   */
  ngOnInit() {

    this.model = {
      id: '',
      name: '',
      url: '',
      caption: ''
    };

  }

  /**
   * Hides the modal
   */
  hide() {
    this._visible = false;
    this.onCancel.emit(null);
  }

  /**
   * Submits the gallery image
   */
  submit() {

    this._galleryImageService.remove(this.model.id, this.gallery.id)
                     .subscribe(
                       data => { this.success(); },
                       error =>  this.errorMessage = <any>error
                      );

  }

  /**
   * Handles a successful submission
   */
  success() {

    this._visible = false;
    this.onUpdate.emit(null);

  }


}