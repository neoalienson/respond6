import {Component, EventEmitter, Input, Output} from 'angular2/core';
import {CanActivate} from 'angular2/router';
import {tokenNotExpired} from 'angular2-jwt/angular2-jwt';
import {GalleryService} from '/app/shared/services/gallery.service';

@Component({
    selector: 'respond-remove-gallery',
    templateUrl: './app/shared/components/galleries/remove-gallery/remove-gallery.component.html',
    providers: [GalleryService]
})

@CanActivate(() => tokenNotExpired())

export class RemoveGalleryComponent {

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

  // gallery input
  @Input()
  set gallery(gallery){
  
    console.log(gallery);

    // set visible
    this.model = gallery;

  }

  // outputs
  @Output() onCancel = new EventEmitter<any>();
  @Output() onUpdate = new EventEmitter<any>();

  constructor (private _galleryService: GalleryService) {}

  /**
   * Init
   */
  ngOnInit() {

    this.model = {
      id: '',
      name: ''
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
   * Submits the gallery
   */
  submit() {

    this._galleryService.remove(this.model.id)
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