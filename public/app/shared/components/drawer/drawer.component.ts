import {Component, EventEmitter, Input, Output} from 'angular2/core';
import {ROUTER_DIRECTIVES, CanActivate} from 'angular2/router'
import {tokenNotExpired} from 'angular2-jwt/angular2-jwt'

@Component({
    selector: 'respond-drawer',
    templateUrl: './app/shared/components/drawer/drawer.component.html',
    directives: [ROUTER_DIRECTIVES]
})

@CanActivate(() => tokenNotExpired())

export class DrawerComponent {

  _visible: boolean = false;

  @Input()
  set visible(visible: boolean){
    this._visible = visible;
  }

  get visible() { return this._visible; }

  @Output() onHide = new EventEmitter<any>();

  constructor() {}

  /**
   * Init pages
   */
  ngOnInit() { }

  /**
   * Hides the add page modal
   */
  hide() {
    this._visible = false;
    this.onHide.emit(null);
  }

}