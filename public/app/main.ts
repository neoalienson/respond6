import {bootstrap} from 'angular2/platform/browser'
import {provide} from 'angular2/core';
import {HTTP_PROVIDERS, Http } from 'angular2/http';
import {AppComponent} from './app.component'
import {AuthHttp, AuthConfig, tokenNotExpired, JwtHelper} from 'angular2-jwt/angular2-jwt'
import 'rxjs/add/operator/map';

bootstrap(AppComponent, [
  HTTP_PROVIDERS,
  provide(AuthConfig, {useValue: new AuthConfig({
    headerName: 'X-AUTH'
  })}),
  provide(AuthHttp, {
    useFactory: (http) => {
      return new AuthHttp(new AuthConfig({
        headerName: 'X-AUTH'
      }), http);
    },
    deps: [Http]
  }),
  AuthHttp
])