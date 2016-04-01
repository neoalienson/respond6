import {Component, EventEmitter, Input, Output} from 'angular2/core';
import {CanActivate} from 'angular2/router'
import {tokenNotExpired} from 'angular2-jwt/angular2-jwt'
import {MenuService} from '/app/shared/services/menu.service'

@Component({
    selector: 'respond-remove-menu',
    templateUrl: './app/shared/components/remove-menu/remove-menu.component.html',
    providers: [MenuService]
})

@CanActivate(() => tokenNotExpired())

export class RemoveMenuComponent {

  routes;
  errorMessage;

  // model to store
  model: {
    id: ''
  };

  _visible: boolean = false;

  @Input()
  set visible(visible: boolean){

    // set visible
    this._visible = visible;

  }

  @Input()
  set menu(menu){

    // set visible
    this.model = menu;

  }

  get visible() { return this._visible; }

  @Output() onCancel = new EventEmitter<any>();
  @Output() onUpdate = new EventEmitter<any>();

  constructor (private _menuService: MenuService) {}

  /**
   * Init
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

    this._menuService.remove(this.model.id)
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