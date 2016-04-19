import {Injectable}     from 'angular2/core';
import {Http, Response} from 'angular2/http';
import {AuthHttp, AuthConfig} from 'angular2-jwt/angular2-jwt';
import {Headers, RequestOptions} from 'angular2/http';
import {Observable} from 'rxjs/Observable';

@Injectable()
export class FormService {
  constructor (private http: Http, private authHttp: AuthHttp, private authConfig: AuthConfig) {}

  private _listUrl = 'api/forms/list';
  private _addUrl = 'api/forms/add';
  private _editUrl = 'api/forms/edit';
  private _removeUrl = 'api/forms/remove';

  /**
   * Lists forms
   *
   */
  list () {
    return this.authHttp.get(this._listUrl).map((res:Response) => res.json());
  }

  /**
   * Adds a form
   *
   * @param {string} name
   * @param {string} cssClass
   * @return {Observable}
   */
  add (name: string, cssClass: string) {

    let body = JSON.stringify({ name, cssClass });
    let headers = new Headers({ 'Content-Type': 'application/json' });
    let options = new RequestOptions({ headers: headers });

    return this.authHttp.post(this._addUrl, body, options);

  }

  /**
   * Edits a form
   *
   * @param {string} name
   * @return {Observable}
   */
  edit (id: string, name: string, cssClass: string) {

    let body = JSON.stringify({ id, name, cssClass });
    let headers = new Headers({ 'Content-Type': 'application/json' });
    let options = new RequestOptions({ headers: headers });

    return this.authHttp.post(this._editUrl, body, options);

  }

  /**
   * Removes a form
   *
   * @param {string} id
   * @return {Observable}
   */
  remove (id: string) {

    let body = JSON.stringify({ id });
    let headers = new Headers({ 'Content-Type': 'application/json' });
    let options = new RequestOptions({ headers: headers });

    return this.authHttp.post(this._removeUrl, body, options);

  }

}