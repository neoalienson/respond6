import {Component, EventEmitter, Input, Output} from 'angular2/core';
import {CanActivate} from 'angular2/router'
import {tokenNotExpired} from 'angular2-jwt/angular2-jwt'
import {PageService} from '/app/shared/services/page.service'
import {RouteService} from '/app/shared/services/route.service'

@Component({
    selector: 'respond-remove-page',
    templateUrl: './app/shared/components/pages/remove-page/remove-page.component.html',
    providers: [PageService, RouteService]
})

@CanActivate(() => tokenNotExpired())

export class RemovePageComponent {

  routes;
  errorMessage;

  // model to store
  model: {
    url: '',
    title: '',
    description: '',
    keywords: '',
    callout: '',
    layout: 'content',
    language: 'en'
  };

  _visible: boolean = false;

  @Input()
  set visible(visible: boolean){

    // set visible
    this._visible = visible;

  }

  @Input()
  set page(page){

    // set visible
    this.model = page;

  }

  get visible() { return this._visible; }

  @Output() onCancel = new EventEmitter<any>();
  @Output() onUpdate = new EventEmitter<any>();
  @Output() onError = new EventEmitter<any>();

  constructor (private _pageService: PageService, private _routeService: RouteService) {}

  /**
   * Init pages
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

    this._pageService.remove(this.model.url)
                     .subscribe(
                       data => { this.success(); },
                       error =>  { this.onError.emit(<any>error); }
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