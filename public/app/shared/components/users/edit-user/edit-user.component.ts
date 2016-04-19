import {Component, EventEmitter, Input, Output} from 'angular2/core';
import {CanActivate} from 'angular2/router'
import {tokenNotExpired} from 'angular2-jwt/angular2-jwt'
import {UserService} from '/app/shared/services/user.service'

@Component({
    selector: 'respond-edit-user',
    templateUrl: './app/shared/components/users/edit-user/edit-user.component.html',
    providers: [UserService]
})

@CanActivate(() => tokenNotExpired())

export class EditUserComponent {

  routes;
  errorMessage;

  // model to store
  model: {
    email: '',
    firstName: '',
    lastName: '',
    role: '',
    password: '',
    retype: '',
    language: 'en'
  };

  _visible: boolean = false;

  @Input()
  set visible(visible: boolean){

    // set visible
    this._visible = visible;

    // reset model
    this.model = {
      email: '',
      firstName: '',
      lastName: '',
      role: '',
      password: '',
      retype: '',
      language: 'en'
    };

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
   * Inits component
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

    if(this.model.password != this.model.retype) {
      console.log('[respond.error] password mismatch');
      toast.show('failure', 'The password does not match the retype field');
      return;
    }

    // add user
    this._userService.edit(this.model.email, this.model.firstName, this.model.lastName, this.model.role, this.model.password, this.model.language)
                     .subscribe(
                       data => { this.success(); },
                       error => { this.errorMessage = <any>error; this.error(); }
                      );

  }

  /**
   * Handles a successful edit
   */
  success() {

    toast.show('success');

    this._visible = false;
    this.onUpdate.emit(null);

  }

  /**
   * Handles an error
   */
  error() {

    console.log('[respond.error] ' + this.errorMessage);
    toast.show('failure');

  }


}