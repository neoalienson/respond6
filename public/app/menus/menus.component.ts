import {Component} from 'angular2/core'
import {tokenNotExpired} from 'angular2-jwt/angular2-jwt'
import {ROUTER_DIRECTIVES, ROUTER_PROVIDERS, CanActivate} from 'angular2/router'
import {MenuService} from '/app/shared/services/menu.service'
import {AddMenuComponent} from '/app/shared/components/add-menu/add-menu.component';
import {RemoveMenuComponent} from '/app/shared/components/remove-menu/remove-menu.component';
import {DrawerComponent} from '/app/shared/components/drawer/drawer.component';

@Component({
    selector: 'respond-menus',
    templateUrl: './app/menus/menus.component.html',
    providers: [MenuService],
    directives: [AddMenuComponent, RemoveMenuComponent, DrawerComponent]
})

@CanActivate(() => tokenNotExpired())

export class MenusComponent {

  id;
  menus;
  items;
  errorMessage;
  selectedMenu;
  selectedItem;
  addVisible: boolean;
  removeVisible: boolean;
  drawerVisible: boolean;

  constructor (private _menuService: MenuService) {}

  /**
   * Init
   *
   */
  ngOnInit() {

    this.id = localStorage.getItem('respond.siteId');
    this.addVisible = false;
    this.removeVisible = false;
    this.drawerVisible = false;
    this.menus = {};
    this.items = {};

    this.list();

  }

  /**
   * Updates the list
   */
  list() {

    this.reset();
    this._menuService.list()
                     .subscribe(
                       data => { this.menus = data; this.success(); },
                       error =>  this.errorMessage = <any>error
                      );
  }

  /**
   * handles the list successfully updated
   */
  success () {

    // default to the first menu returned
    if(this.selectedMenu === null && this.menus.length > 0) {
      this.selectedMenu = this.menus[0];
    }

    // update items
    if(this.selectedMenu !== null) {
      this.listItems();
    }

  }

  /**
   * list items in the menu
   */
  listItems() {

    console.log('listItems for ' + this.selectedMenu.name);

    /*
    this._menuService.listItems(this.selectedMenu.name)
                     .subscribe(
                       data => { this.menus = data; this.success(); },
                       error =>  this.errorMessage = <any>error
                      );*/

  }

  /**
   * Resets screen
   */
  reset() {
    this.removeVisible = false;
    this.addVisible = false;
    this.drawerVisible = false;
    this.menu = {};
  }

  /**
   * Sets the menu to active
   *
   * @param {Menu} menu
   */
  setActive(menu) {
    this.selectedMenu = menu;
  }

  /**
   * Sets the list item to active
   *
   * @param {MenuItem} item
   */
  setItemActive(item) {
    this.selectedItem = item;
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
    this.addVisible = true;
  }

  /**
   * Shows the remove dialog
   *
   * @param {menu} menu
   */
  showRemove(menu) {
    this.removeVisible = true;
    this.menu = menu;
  }

}