import {Injectable}     from 'angular2/core'
import {Http, Response} from 'angular2/http'
import {AuthHttp, AuthConfig} from 'angular2-jwt/angular2-jwt';
import {Headers, RequestOptions} from 'angular2/http'
import {Observable} from 'rxjs/Observable'

@Injectable()
export class PageService {
  constructor (private http: Http, private authHttp: AuthHttp, private authConfig: AuthConfig) {}

  private _listUrl = 'api/pages/list';
  private _addUrl = 'api/pages/add';

  /**
   * Lists pages
   *
   */
  list () {
    return this.authHttp.get(this._listUrl).map((res:Response) => res.json());
  }

  /**
   * Adds a page
   *
   * @param {string} id The site id
   * @param {string} email The user's login email
   * @return {Observable}
   */
  add (url: string, title: string, description: string) {

    let body = JSON.stringify({ url, title, description });
    let headers = new Headers({ 'Content-Type': 'application/json' });
    let options = new RequestOptions({ headers: headers });

    return this.authHttp.post(this._addUrl, body, options);

  }

}