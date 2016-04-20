import {Injectable}     from 'angular2/core'
import {Http, Response} from 'angular2/http'
import {AuthHttp, AuthConfig} from 'angular2-jwt/angular2-jwt';
import {Headers, RequestOptions} from 'angular2/http'
import {Observable} from 'rxjs/Observable'

@Injectable()
export class BrandingService {
  constructor (private http: Http, private authHttp: AuthHttp, private authConfig: AuthConfig) {}

  private _listUrl = 'api/branding/list';
  private _editUrl = 'api/branding/edit';

  /**
   * Lists branding items
   *
   */
  list (id) {

    var url = this._listUrl;

    return this.authHttp.get(url).map((res:Response) => res.json());
  }

  /**
   * Edits a branding item
   *
   * @param {array} settings
   * @return {Observable}
   */
  edit (settings) {

    let body = JSON.stringify({ settings });
    let headers = new Headers({ 'Content-Type': 'application/json' });
    let options = new RequestOptions({ headers: headers });

    return this.authHttp.post(this._editUrl, body, options);

  }


}