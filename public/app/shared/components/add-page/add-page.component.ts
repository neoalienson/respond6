import {Component, EventEmitter, Input, Output} from 'angular2/core';
import {CanActivate} from 'angular2/router'
import {tokenNotExpired} from 'angular2-jwt/angular2-jwt'
import {PageService} from '/app/shared/services/page.service'

@Component({
    selector: 'respond-add-page',
    templateUrl: './app/shared/components/add-page/add-page.component.html',
    providers: [PageService]
})

@CanActivate(() => tokenNotExpired())

export class AddPageComponent {

  _visible: boolean = false;

  @Input()
  set visible(visible: boolean){
    this._visible = visible;
  }

  get visible() { return this._visible; }

  @Output() onCancel = new EventEmitter<any>();
  @Output() onAdd = new EventEmitter<any>();

  constructor (private _pageService: PageService) {}

  /**
   * Init pages
   */
  ngOnInit() { }

  /**
   * Hides the add page modal
   */
  hide() {
    this._visible = false;
    this.onCancel.emit(null);
  }

  /**
   * Adds a page
   */
  addPage() {
    alert('[respond] add page');
    this._visible = false;
    this.onAdd.emit(null);
  }


}