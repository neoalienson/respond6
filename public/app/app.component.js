System.register(['angular2/core', 'angular2/router', './login/login.component', './forgot/forgot.component', './reset/reset.component', './create/create.component', './pages/pages.component', './files/files.component', './users/users.component', './menus/menus.component', './forms/forms.component', './settings/settings.component', './submissions/submissions.component'], function(exports_1, context_1) {
    "use strict";
    var __moduleName = context_1 && context_1.id;
    var __decorate = (this && this.__decorate) || function (decorators, target, key, desc) {
        var c = arguments.length, r = c < 3 ? target : desc === null ? desc = Object.getOwnPropertyDescriptor(target, key) : desc, d;
        if (typeof Reflect === "object" && typeof Reflect.decorate === "function") r = Reflect.decorate(decorators, target, key, desc);
        else for (var i = decorators.length - 1; i >= 0; i--) if (d = decorators[i]) r = (c < 3 ? d(r) : c > 3 ? d(target, key, r) : d(target, key)) || r;
        return c > 3 && r && Object.defineProperty(target, key, r), r;
    };
    var __metadata = (this && this.__metadata) || function (k, v) {
        if (typeof Reflect === "object" && typeof Reflect.metadata === "function") return Reflect.metadata(k, v);
    };
    var core_1, router_1, login_component_1, forgot_component_1, reset_component_1, create_component_1, pages_component_1, files_component_1, users_component_1, menus_component_1, forms_component_1, settings_component_1, submissions_component_1;
    var AppComponent;
    return {
        setters:[
            function (core_1_1) {
                core_1 = core_1_1;
            },
            function (router_1_1) {
                router_1 = router_1_1;
            },
            function (login_component_1_1) {
                login_component_1 = login_component_1_1;
            },
            function (forgot_component_1_1) {
                forgot_component_1 = forgot_component_1_1;
            },
            function (reset_component_1_1) {
                reset_component_1 = reset_component_1_1;
            },
            function (create_component_1_1) {
                create_component_1 = create_component_1_1;
            },
            function (pages_component_1_1) {
                pages_component_1 = pages_component_1_1;
            },
            function (files_component_1_1) {
                files_component_1 = files_component_1_1;
            },
            function (users_component_1_1) {
                users_component_1 = users_component_1_1;
            },
            function (menus_component_1_1) {
                menus_component_1 = menus_component_1_1;
            },
            function (forms_component_1_1) {
                forms_component_1 = forms_component_1_1;
            },
            function (settings_component_1_1) {
                settings_component_1 = settings_component_1_1;
            },
            function (submissions_component_1_1) {
                submissions_component_1 = submissions_component_1_1;
            }],
        execute: function() {
            AppComponent = (function () {
                function AppComponent() {
                }
                AppComponent = __decorate([
                    core_1.Component({
                        selector: 'respond-app',
                        directives: [router_1.ROUTER_DIRECTIVES],
                        providers: [
                            router_1.ROUTER_PROVIDERS
                        ],
                        templateUrl: './app/app.component.html'
                    }),
                    router_1.RouteConfig([
                        {
                            path: '/create',
                            name: 'Create',
                            component: create_component_1.CreateComponent,
                            useAsDefault: true
                        },
                        {
                            path: '/login/:id',
                            name: 'Login',
                            component: login_component_1.LoginComponent
                        },
                        {
                            path: '/forgot/:id',
                            name: 'Forgot',
                            component: forgot_component_1.ForgotComponent
                        },
                        {
                            path: '/reset/:id/:token',
                            name: 'Reset',
                            component: reset_component_1.ResetComponent
                        },
                        {
                            path: '/pages',
                            name: 'Pages',
                            component: pages_component_1.PagesComponent
                        },
                        {
                            path: '/files',
                            name: 'Files',
                            component: files_component_1.FilesComponent
                        },
                        {
                            path: '/users',
                            name: 'Users',
                            component: users_component_1.UsersComponent
                        },
                        {
                            path: '/menus',
                            name: 'Menus',
                            component: menus_component_1.MenusComponent
                        },
                        {
                            path: '/forms',
                            name: 'Forms',
                            component: forms_component_1.FormsComponent
                        },
                        {
                            path: '/settings',
                            name: 'Settings',
                            component: settings_component_1.SettingsComponent
                        },
                        {
                            path: '/submissions',
                            name: 'Submissions',
                            component: submissions_component_1.SubmissionsComponent
                        }
                    ]), 
                    __metadata('design:paramtypes', [])
                ], AppComponent);
                return AppComponent;
            }());
            exports_1("AppComponent", AppComponent);
        }
    }
});
//# sourceMappingURL=app.component.js.map