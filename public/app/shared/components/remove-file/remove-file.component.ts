import {Component, EventEmitter, Input, Output} from 'angular2/core';
import {CanActivate} from 'angular2/router'
import {tokenNotExpired} from 'angular2-jwt/angular2-jwt'
import {FileService} from '/app/shared/services/file.service'
import {RouteService} from '/app/shared/services/route.service'

@Component({
    selector: 'respond-remove-file',
    templateUrl: './app/shared/components/remove-file/remove-file.component.html',
    providers: [FileService, RouteService]
})

@CanActivate(() => tokenNotExpired())

export class RemoveFileComponent {

  routes;
  errorMessage;

  // model to store
  model: {
    name: '',
    url: ''
  };

  _visible: boolean = false;

  @Input()
  set visible(visible: boolean){

    // set visible
    this._visible = visible;

  }

  @Input()
  set file(file){

    // set visible
    this.model = file;

  }

  get visible() { return this._visible; }

  @Output() onCancel = new EventEmitter<any>();
  @Output() onUpdate = new EventEmitter<any>();

  constructor (private _fileService: FileService, private _routeService: RouteService) {}

  /**
   * Init files
   */
  ngOnInit() {

  }

  /**
   * Hides the modal
   */
  hide() {
    this._visible = false;
    this.onCancel.emit(null);
  }

  /**
   * Submits the form
   */
  submit() {

    this._fileService.remove(this.model.name)
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