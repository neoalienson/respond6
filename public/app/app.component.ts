import {Component} from 'angular2/core';
import {RouteConfig, ROUTER_DIRECTIVES, ROUTER_PROVIDERS} from 'angular2/router';
import {LoginComponent} from './login/login.component';
import {ForgotComponent} from './forgot/forgot.component';
import {ResetComponent} from './reset/reset.component';
import {CreateComponent} from './create/create.component';
import {PagesComponent} from './pages/pages.component';
import {FilesComponent} from './files/files.component';
import {UsersComponent} from './users/users.component';
import {MenusComponent} from './menus/menus.component';
import {FormsComponent} from './forms/forms.component';
import {SettingsComponent} from './settings/settings.component';

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
  },
  {
    path: '/files',
    name: 'Files',
    component: FilesComponent
  },
  {
    path: '/users',
    name: 'Users',
    component: UsersComponent
  },
  {
    path: '/menus',
    name: 'Menus',
    component: MenusComponent
  },
  {
    path: '/forms',
    name: 'Forms',
    component: FormsComponent
  },
  {
    path: '/settings',
    name: 'Settings',
    component: SettingsComponent
  }
])

export class AppComponent { }