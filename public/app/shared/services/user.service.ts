import {Injectable}     from 'angular2/core'
import {Http, Response} from 'angular2/http'
import {AuthHttp, AuthConfig} from 'angular2-jwt/angular2-jwt';
import {Headers, RequestOptions} from 'angular2/http'
import {Observable} from 'rxjs/Observable'

@Injectable()
export class UserService {
  constructor (private http: Http, private authHttp: AuthHttp, private authConfig: AuthConfig) {}

  private _listUrl = 'api/users/list';
  private _loginUrl = 'api/users/login';
  private _forgotUrl = 'api/users/forgot';
  private _resetUrl = 'api/users/reset';
  private _addUrl = 'api/users/add';
  private _editUrl = 'api/users/edit';
  private _removeUrl = 'api/users/remove';

  /**
   * Lists users
   *
   */
  list () {
    return this.authHttp.get(this._listUrl).map((res:Response) => res.json());
  }

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

  /**
   * Adds the user
   *
   * @param {string} email
   * @param {string} firstName
   * @param {string} lastName
   * @param {string} role
   * @param {string} password
   * @param {string} language
   * @return {Observable}
   */
  add (email: string, firstName: string, lastName: string, role: string, password: string, language: string) {

    let body = JSON.stringify({ email, firstName, lastName, role, password, language });
    let headers = new Headers({ 'Content-Type': 'application/json' });
    let options = new RequestOptions({ headers: headers });

    return this.authHttp.post(this._addUrl, body, options);

  }

  /**
   * Edits the user
   *
   * @param {string} email
   * @param {string} firstName
   * @param {string} lastName
   * @param {string} role
   * @param {string} password
   * @param {string} language
   * @return {Observable}
   */
  edit (email: string, firstName: string, lastName: string, role: string, password: string, language: string) {

    let body = JSON.stringify({ email, firstName, lastName, role, password, language });
    let headers = new Headers({ 'Content-Type': 'application/json' });
    let options = new RequestOptions({ headers: headers });

    return this.authHttp.post(this._editUrl, body, options);

  }

  /**
   * Removes the user
   *
   * @param {string} email
   * @return {Observable}
   */
  remove (email: string) {

    let body = JSON.stringify({ email });
    let headers = new Headers({ 'Content-Type': 'application/json' });
    let options = new RequestOptions({ headers: headers });

    return this.authHttp.post(this._removeUrl, body, options);

  }

}