import {Component} from 'angular2/core'
import {HTTP_PROVIDERS}    from 'angular2/http'
import {RouteConfig, Router, RouteParams, ROUTER_DIRECTIVES} from 'angular2/router'
import {UserService} from '/app/shared/services/user.service'

@Component({
    selector: 'respond-login',
    templateUrl: './app/login/login.component.html',
    providers: [UserService],
    directives: [ROUTER_DIRECTIVES]
})

export class LoginComponent {

    data;
    id;
    errorMessage;

    constructor (private _userService: UserService, private _routeParams: RouteParams, private _router: Router) {}

    ngOnInit() {
        this.id = this._routeParams.get('id');
        localStorage.setItem('respond.siteId', this.id);
    }

    /**
     * Login to the app
     *
     * @param {Event} event
     * @param {string} email The user's login email
     * @param {string} password The user's login password
     */
    login(event, email, password) {

        event.preventDefault();

        this._userService.login(this.id, email, password)
                     .subscribe(
                       data => { this.data = data; this.success(); },
                       error =>  this.errorMessage = <any>error
                      );

    }

    /**
     * Handles a successful login
     */
    success() {

      // set token
      this.setToken(this.data.token);

      // navigate
      this._router.navigate( ['Pages'] );

    }

    /**
     * Routes to the forgot password screen
     */
    forgot() {
      this._router.navigate( ['Forgot', { id: this.id }] );
    }

    /**
     * Sets the token in local storage
     */
    setToken(token) {
        localStorage.setItem('id_token', token)
    }

}