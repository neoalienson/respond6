import {Injectable}     from 'angular2/core'
import {Http, Response} from 'angular2/http'
import {AuthHttp, AuthConfig} from 'angular2-jwt/angular2-jwt';
import {Headers, RequestOptions} from 'angular2/http'
import {Observable} from 'rxjs/Observable'

@Injectable()
export class FileService {
  constructor (private http: Http, private authHttp: AuthHttp, private authConfig: AuthConfig) {}

  private _listUrl = 'api/files/list';
  private _removeFileUrl = 'api/files/remove';

  /**
   * Lists files in the application
   *
   */
  list () {

    return this.authHttp.get(this._listUrl).map((res:Response) => res.json());

  }

  /**
   * Removes the file
   *
   * @param {string} url
   * @return {Observable}
   */
  remove (name: string) {

    let body = JSON.stringify({ name });
    let headers = new Headers({ 'Content-Type': 'application/json' });
    let options = new RequestOptions({ headers: headers });

    return this.authHttp.post(this._removeFileUrl, body, options);

  }

}