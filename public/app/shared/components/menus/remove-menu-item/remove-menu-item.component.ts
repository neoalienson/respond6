import {Component, EventEmitter, Input, Output} from 'angular2/core';
import {CanActivate} from 'angular2/router';
import {tokenNotExpired} from 'angular2-jwt/angular2-jwt';
import {MenuItemService} from '/app/shared/services/menu-item.service';

@Component({
    selector: 'respond-remove-menu-item',
    templateUrl: './app/shared/components/menus/remove-menu-item/remove-menu-item.component.html',
    providers: [MenuItemService]
})

@CanActivate(() => tokenNotExpired())

export class RemoveMenuItemComponent {

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

  // menu input
  @Input()
  set item(item){

    // set visible
    this.model = item;

  }

  // menu input
  @Input() menu;

  // index
  @Input() index

  // outputs
  @Output() onCancel = new EventEmitter<any>();
  @Output() onUpdate = new EventEmitter<any>();

  constructor (private _menuItemService: MenuItemService) {}

  /**
   * Init
   */
  ngOnInit() {

    this.model = {
      html: '',
      url: ''
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
   * Submits the form
   */
  submit() {

    this._menuItemService.remove(this.menu.id, this.index)
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