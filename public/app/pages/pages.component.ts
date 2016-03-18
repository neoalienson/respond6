import {Component} from 'angular2/core'
import {tokenNotExpired} from 'angular2-jwt/angular2-jwt'
import {ROUTER_DIRECTIVES, ROUTER_PROVIDERS, CanActivate} from 'angular2/router'
import {PageService} from '/app/shared/services/page.service'
import {AddPageComponent} from '/app/shared/components/add-page/add-page.component';
import {PageSettingsComponent} from '/app/shared/components/page-settings/page-settings.component';
import {RemovePageComponent} from '/app/shared/components/remove-page/remove-page.component';
import {DrawerComponent} from '/app/shared/components/drawer/drawer.component';

@Component({
    selector: 'respond-pages',
    templateUrl: './app/pages/pages.component.html',
    providers: [PageService],
    directives: [AddPageComponent, PageSettingsComponent, RemovePageComponent, DrawerComponent]
})

@CanActivate(() => tokenNotExpired())

export class PagesComponent {

  page;
  pages;
  errorMessage;
  selectedPage;
  addPageVisible: boolean;
  removePageVisible: boolean;

  constructor (private _pageService: PageService) {}

  /**
   * Init pages
   *
   */
  ngOnInit() {

    this.id = localStorage.getItem('respond.siteId');
    this.addPageVisible = false;
    this.removePageVisible = false;
    this.pageSettingsVisible = false;
    this.drawerVisible = false;
    this.page = {};

    this.list();

  }

  /**
   * Updates the list
   */
  list() {
  
    this.reset();
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
    this.removePageVisible = false;
    this.addPageVisible = false;
    this.pageSettingsVisible = false;
    this.drawerVisible = false;
    this.page = {};
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
  showRemove(page) { 
    this.removePageVisible = true;
    this.page = page;
  }

  /**
   * Shows the settings dialog
   *
   * @param {Page} page
   */
  showSettings(page) { 
    this.pageSettingsVisible = true;
    this.page = page;
  }

  /**
   * Shows the settings dialog
   *
   * @param {Page} page
   */
  edit(page) {
    window.location = '/edit?q=' + this.id + '/' + page.Url + '.html';
  }


}