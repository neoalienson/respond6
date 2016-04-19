import {Injectable}     from 'angular2/core';
import {Http, Response} from 'angular2/http';
import {AuthHttp, AuthConfig} from 'angular2-jwt/angular2-jwt';
import {Headers, RequestOptions} from 'angular2/http';
import {Observable} from 'rxjs/Observable';

@Injectable()
export class FormFieldService {
  constructor (private http: Http, private authHttp: AuthHttp, private authConfig: AuthConfig) {}

  private _listUrl = 'api/forms/fields/list';
  private _addUrl = 'api/forms/fields/add';
  private _editUrl = 'api/forms/fields/edit';
  private _removeUrl = 'api/forms/fields/remove';
  private _updateOrderUrl = 'api/forms/fields/order';

  /**
   * Lists fields
   *
   */
  list (id) {

    var url = this._listUrl + '/' + encodeURI(id);

    return this.authHttp.get(url).map((res:Response) => res.json());
  }

  /**
   * Adds a form filed
   *
   * @param {string} label
   * @param {string} type
   * @param {boolean} required
   * @param {string} options
   * @param {string} helperText
   * @param {string} placeholder
   * @param {string} cssClass
   * @return {Observable}
   */
  add (id: string, label: string, type: string, required: boolean, options: string, helperText: string, placeholder: string, cssClass: string) {

    let body = JSON.stringify({ id, label, type, required, options, helperText, placeholder, cssClass });
    let headers = new Headers({ 'Content-Type': 'application/json' });
    let _options = new RequestOptions({ headers: headers });

    return this.authHttp.post(this._addUrl, body, _options);

  }

  /**
   * Edits a form field
   *
   * @param {string} id
   * @param {number} index
   * @param {string} label
   * @param {string} type
   * @param {boolean} required
   * @param {string} options
   * @param {string} helperText
   * @param {string} placeholder
   * @param {string} cssClass
   * @return {Observable}
   */
  edit (id: string, index: number, label: string, type: string, required: boolean, options: string, helperText: string, placeholder: string, cssClass: string) {

    let body = JSON.stringify({ id, index, label, type, required, options, helperText, placeholder, cssClass });
    let headers = new Headers({ 'Content-Type': 'application/json' });
    let _options = new RequestOptions({ headers: headers });

    return this.authHttp.post(this._editUrl, body, _options);

  }

  /**
   * Removes a form field
   *
   * @param {string} name
   * @param {string} index
   * @return {Observable}
   */
  remove (id: string, index: number) {

    let body = JSON.stringify({ id, index });
    let headers = new Headers({ 'Content-Type': 'application/json' });
    let options = new RequestOptions({ headers: headers });

    return this.authHttp.post(this._removeUrl, body, options);

  }

  /**
   * Updates the order of fields
   *
   * @param {string} name
   * @param {string} priority
   * @return {Observable}
   */
  updateOrder (id, fields) {

    let body = JSON.stringify({ id, fields });
    let headers = new Headers({ 'Content-Type': 'application/json' });
    let options = new RequestOptions({ headers: headers });

    return this.authHttp.post(this._updateOrderUrl, body, options);

  }

}