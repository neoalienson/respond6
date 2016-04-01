import {Injectable}     from 'angular2/core'
import {Http, Response} from 'angular2/http'
import {AuthHttp, AuthConfig} from 'angular2-jwt/angular2-jwt';
import {Headers, RequestOptions} from 'angular2/http'
import {Observable} from 'rxjs/Observable'

@Injectable()
export class MenuService {
  constructor (private http: Http, private authHttp: AuthHttp, private authConfig: AuthConfig) {}

  private _listUrl = 'api/menus/list';
  private _listItemsUrl = 'api/menus/list/items';
  private _addUrl = 'api/menus/add';
  private _removeUrl = 'api/menus/remove';

  /**
   * Lists menus
   *
   */
  list () {
    return this.authHttp.get(this._listUrl).map((res:Response) => res.json());
  }

  /**
   * Lists items
   *
   */
  listItems (menu) {

    var url = this._listItemsUrl + '/' + encodeURI(menu);

    return this.authHttp.get(url).map((res:Response) => res.json());
  }

  /**
   * Adds a menu
   *
   * @param {string} name
   * @return {Observable}
   */
  add (name: string) {

    let body = JSON.stringify({ name });
    let headers = new Headers({ 'Content-Type': 'application/json' });
    let options = new RequestOptions({ headers: headers });

    return this.authHttp.post(this._addUrl, body, options);

  }

  /**
   * Removes a menu
   *
   * @param {string} name
   * @return {Observable}
   */
  remove (name: string) {

    let body = JSON.stringify({ name });
    let headers = new Headers({ 'Content-Type': 'application/json' });
    let options = new RequestOptions({ headers: headers });

    return this.authHttp.post(this._removeUrl, body, options);

  }

}