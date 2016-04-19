import {Component} from 'angular2/core';
import {tokenNotExpired} from 'angular2-jwt/angular2-jwt';
import {ROUTER_DIRECTIVES, ROUTER_PROVIDERS, CanActivate} from 'angular2/router';
import {UserService} from '/app/shared/services/user.service';
import {AddUserComponent} from '/app/shared/components/users/add-user/add-user.component';
import {EditUserComponent} from '/app/shared/components/users/edit-user/edit-user.component';
import {RemoveUserComponent} from '/app/shared/components/users/remove-user/remove-user.component';
import {DrawerComponent} from '/app/shared/components/drawer/drawer.component';

@Component({
    selector: 'respond-users',
    templateUrl: './app/users/users.component.html',
    providers: [UserService],
    directives: [AddUserComponent, EditUserComponent, RemoveUserComponent, DrawerComponent]
})

@CanActivate(() => tokenNotExpired())

export class UsersComponent {

  user;
  users;
  errorMessage;
  selectedUser;
  id;
  addVisible: boolean;
  editVisible: boolean;
  removeVisible: boolean;
  drawerVisible: boolean;

  constructor (private _userService: UserService) {}

  /**
   * Init
   *
   */
  ngOnInit() {

    this.id = localStorage.getItem('respond.siteId');
    this.addVisible = false;
    this.editVisible = false;
    this.removeVisible = false;
    this.drawerVisible = false;
    this.user = {};

    this.list();

  }

  /**
   * Updates the list
   */
  list() {

    this.reset();
    this._userService.list()
                     .subscribe(
                       data => { this.users = data; },
                       error =>  this.errorMessage = <any>error
                      );
  }

  /**
   * Resets an modal booleans
   */
  reset() {
    this.addVisible = false;
    this.editVisible = false;
    this.removeVisible = false;
    this.drawerVisible = false;
    this.user = {};
  }

  /**
   * Sets the list item to active
   *
   * @param {User} user
   */
  setActive(user) {
    this.selectedUser = user;
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
   * @param {User} user
   */
  showRemove(user) {
    this.removeVisible = true;
    this.user = user;
  }

  /**
   * Shows the edit dialog
   *
   * @param {User} user
   */
  showEdit(user) {
    this.editVisible = true;
    this.user = user;
  }



}