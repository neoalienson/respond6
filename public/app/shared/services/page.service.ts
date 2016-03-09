import {Injectable}     from 'angular2/core'
import {Http, Response} from 'angular2/http'
import {AuthHttp, AuthConfig} from 'angular2-jwt/angular2-jwt';
import {Headers, RequestOptions} from 'angular2/http'
import {Observable} from 'rxjs/Observable'

@Injectable()
export class PageService {
  constructor (private http: Http, private authHttp: AuthHttp, private authConfig: AuthConfig) {}

  private _listUrl = 'api/pages/list';

  /**
   * Lists pages in the application
   *
   */
  list () {

    // LOOK AT BUG HERE: https://github.com/auth0/angular2-jwt/issues/28

    return this.authHttp.get(this._listUrl).map((res:Response) => res.json());

  }

}