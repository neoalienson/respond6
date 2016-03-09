import {Injectable}     from 'angular2/core'
import {Http, Response} from 'angular2/http'
import {Headers, RequestOptions} from 'angular2/http'
import {Observable} from 'rxjs/Observable'

@Injectable()
export class UserService {
  constructor (private http: Http) {}

  private _loginUrl = 'api/users/login';
  private _forgotUrl = 'api/users/forgot';
  private _resetUrl = 'api/users/reset';

  /**
   * Login to the application
   *
   * @param {string} id The site id
   * @param {string} email The user's login email
   * @param {string} password The user's login password
   * @return {Observable}
   */
  login (id: string, email: string, password: string) {

    let body = JSON.stringify({ id, email, password });
    let headers = new Headers({ 'Content-Type': 'application/json' });
    let options = new RequestOptions({ headers: headers });

    return this.http.post(this._loginUrl, body, options)
                    .map((res:Response) => res.json());

  }

  /**
   * Requests the user's password to be reset
   *
   * @param {string} id The site id
   * @param {string} email The user's login email
   * @return {Observable}
   */
  forgot (id: string, email: string) {

    let body = JSON.stringify({ id, email });
    let headers = new Headers({ 'Content-Type': 'application/json' });
    let options = new RequestOptions({ headers: headers });

    return this.http.post(this._forgotUrl, body, options);

  }

  /**
   * Resets the password
   *
   * @param {string} id The site id
   * @param {string} token The token needed to reset the password
   * @param {string} password The new password
   * @return {Observable}
   */
  reset (id: string, token: string, password: string) {

    let body = JSON.stringify({ id, token, password });
    let headers = new Headers({ 'Content-Type': 'application/json' });
    let options = new RequestOptions({ headers: headers });

    return this.http.post(this._resetUrl, body, options);

  }

}