import {Component} from 'angular2/core';
import {RouteConfig, ROUTER_DIRECTIVES, ROUTER_PROVIDERS} from 'angular2/router';
import {LoginComponent} from './login/login.component';
import {ForgotComponent} from './forgot/forgot.component';
import {ResetComponent} from './reset/reset.component';
import {CreateComponent} from './create/create.component';
import {PagesComponent} from './pages/pages.component';

@Component({
    selector: 'respond-app',
    directives: [ROUTER_DIRECTIVES],
    providers: [
      ROUTER_PROVIDERS
    ],
    templateUrl: './app/app.component.html'
})

@RouteConfig([
  {
    path: '/create',
    name: 'Create',
    component: CreateComponent,
    useAsDefault: true
  },
  {
    path: '/login/:id',
    name: 'Login',
    component: LoginComponent
  },
  {
    path: '/forgot/:id',
    name: 'Forgot',
    component: ForgotComponent
  },
  {
    path: '/reset/:id/:token',
    name: 'Reset',
    component: ResetComponent
  },
  {
    path: '/pages',
    name: 'Pages',
    component: PagesComponent
  }
])

export class AppComponent { }