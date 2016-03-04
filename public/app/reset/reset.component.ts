import {Component} from 'angular2/core'
import {HTTP_PROVIDERS}    from 'angular2/http'
import {RouteParams, ROUTER_DIRECTIVES} from 'angular2/router'
import {UserService} from '/app/shared/services/user.service'

@Component({
    selector: 'respond-reset',
    templateUrl: './app/reset/reset.component.html',
    providers: [UserService]
})

export class ResetComponent {

    data;
    id;
    token;
    errorMessage;

    constructor (private _userService: UserService, private _routeParams: RouteParams) {}

    ngOnInit() {
        this.id = this._routeParams.get('id');
        this.token = this._routeParams.get('token');
    }

    reset(event, password, retype){

        event.preventDefault();

        if(password !== retype) {
          alert('Password mismatch');
        }
        else {
          this._userService.reset(this.id, this.token, password, retype)
                       .subscribe(
                         () => { alert('success'); },
                         error =>  this.errorMessage = <any>error
                        );
        }

    }


}