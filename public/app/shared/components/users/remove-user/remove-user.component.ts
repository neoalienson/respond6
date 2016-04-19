import {Component, EventEmitter, Input, Output} from 'angular2/core';
import {CanActivate} from 'angular2/router'
import {tokenNotExpired} from 'angular2-jwt/angular2-jwt'
import {UserService} from '/app/shared/services/user.service'

@Component({
    selector: 'respond-remove-user',
    templateUrl: './app/shared/components/users/remove-user/remove-user.component.html',
    providers: [UserService]
})

@CanActivate(() => tokenNotExpired())

export class RemoveUserComponent {

  routes;
  errorMessage;

  // model to store
  model: {
    firstName: '',
    lastName: '',
    email: ''
  };

  _visible: boolean = false;

  @Input()
  set visible(visible: boolean){

    // set visible
    this._visible = visible;

  }

  @Input()
  set user(user){

    // set visible
    this.model = user;

  }

  get visible() { return this._visible; }

  @Output() onCancel = new EventEmitter<any>();
  @Output() onUpdate = new EventEmitter<any>();

  constructor (private _userService: UserService) {}

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

    this._userService.remove(this.model.email)
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