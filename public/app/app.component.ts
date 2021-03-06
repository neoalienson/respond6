import {Component} from '@angular/core';
import {RouteConfig, ROUTER_DIRECTIVES, ROUTER_PROVIDERS} from '@angular/router-deprecated';
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
import {SubmissionsComponent} from './submissions/submissions.component';
import {GalleriesComponent} from './galleries/galleries.component';

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
  },
  {
    path: '/submissions',
    name: 'Submissions',
    component: SubmissionsComponent
  },
  {
    path: '/galleries',
    name: 'Galleries',
    component: GalleriesComponent
  }
])

export class AppComponent { }