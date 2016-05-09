import {bootstrap} from '@angular/platform-browser-dynamic';
import {provide, enableProdMode} from '@angular/core';
import {HTTP_PROVIDERS, Http } from '@angular/http';
import {AppComponent} from './app.component';
import {AuthHttp, AuthConfig, tokenNotExpired, JwtHelper} from 'angular2-jwt/angular2-jwt';
import 'rxjs/add/operator/map';

// enableProdMode();

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
]);