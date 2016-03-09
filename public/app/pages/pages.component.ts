import {Component} from 'angular2/core'
import {tokenNotExpired} from 'angular2-jwt/angular2-jwt'
import {ROUTER_DIRECTIVES, ROUTER_PROVIDERS, CanActivate} from 'angular2/router'
import {PageService} from '/app/shared/services/page.service'
import {AddPageComponent} from '/app/shared/components/add-page/add-page.component';

@Component({
    selector: 'respond-pages',
    templateUrl: './app/pages/pages.component.html',
    providers: [PageService],
    directives: [AddPageComponent]
})

@CanActivate(() => tokenNotExpired())

export class PagesComponent {

  pages;
  errorMessage;
  selectedPage;
  showAddPage: boolean;

  constructor (private _pageService: PageService) {}

  /**
   * Init pages
   *
   */
  ngOnInit() {

    this.showAddPage = false;

    this.list();

  }

  /**
   * Updates the list
   */
  list() {

    this._pageService.list()
                     .subscribe(
                       data => { this.pages = data; },
                       error =>  this.errorMessage = <any>error
                      );
  }

  /**
   * Resets an modal booleans
   */
  reset() {
    this.showAddPage = false;
  }

  /**
   * Sets the list item to active
   *
   * @param {Page} page
   */
  setActive(page) {
    this.selectedPage = page;
  }

  /**
   * Shows the add dialog
   */
  showAdd() {
    this.showAddPage = true;
  }

  /**
   * Shows the remove dialog
   *
   * @param {Page} page
   */
  showRemove(page) { alert('[respond] showRemove()'); console.log(page); }

  /**
   * Shows the settings dialog
   *
   * @param {Page} page
   */
  showSettings(page) { alert('[respond] showSettings()'); console.log(page); }

  /**
   * Shows the settings dialog
   *
   * @param {Page} page
   */
  edit(page) { 
    window.location = '/edit?q=matt/' + page.Url;
  }


}