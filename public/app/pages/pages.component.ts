import {Component} from 'angular2/core'
import {tokenNotExpired} from 'angular2-jwt/angular2-jwt'
import {ROUTER_DIRECTIVES, ROUTER_PROVIDERS, CanActivate} from 'angular2/router'
import {PageService} from '/app/shared/services/page.service'
import {AddPageComponent} from '/app/shared/components/add-page/add-page.component';
import {DrawerComponent} from '/app/shared/components/drawer/drawer.component';

@Component({
    selector: 'respond-pages',
    templateUrl: './app/pages/pages.component.html',
    providers: [PageService],
    directives: [AddPageComponent, DrawerComponent]
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

    this.addPageVisible = false;
    this.drawerVisible = false;

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
    this.addPageVisible = false;
    this.drawerVisible = false;
  }

  /**
   * Sets the list item to active
   *
   * @param {Page} page
   */
  setActive(page) {
    this.reset();
    this.selectedPage = page;
  }
  
  /**
   * Shows the drawer
   */
  toggleDrawer() {
    this.drawerVisible = !this.drawerVisible;
  }

  /**
   * Shows the add dialog
   */
  showAdd() {
    this.addPageVisible = true;
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