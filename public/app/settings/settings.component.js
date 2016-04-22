System.register(['angular2/core', 'angular2-jwt/angular2-jwt', 'angular2/router', '/app/shared/services/setting.service', '/app/shared/components/drawer/drawer.component'], function(exports_1, context_1) {
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
    var core_1, angular2_jwt_1, router_1, setting_service_1, drawer_component_1;
    var SettingsComponent;
    return {
        setters:[
            function (core_1_1) {
                core_1 = core_1_1;
            },
            function (angular2_jwt_1_1) {
                angular2_jwt_1 = angular2_jwt_1_1;
            },
            function (router_1_1) {
                router_1 = router_1_1;
            },
            function (setting_service_1_1) {
                setting_service_1 = setting_service_1_1;
            },
            function (drawer_component_1_1) {
                drawer_component_1 = drawer_component_1_1;
            }],
        execute: function() {
            SettingsComponent = (function () {
                function SettingsComponent(_settingService) {
                    this._settingService = _settingService;
                }
                /**
                 * Init
                 *
                 */
                SettingsComponent.prototype.ngOnInit = function () {
                    this.id = localStorage.getItem('respond.siteId');
                    this.drawerVisible = false;
                    this.settings;
                    this.list();
                };
                /**
                 * Updates the list
                 */
                SettingsComponent.prototype.list = function () {
                    var _this = this;
                    this.reset();
                    this._settingService.list()
                        .subscribe(function (data) { _this.settings = data; }, function (error) { return _this.errorMessage = error; });
                };
                /**
                 * Handles the form submission
                 */
                SettingsComponent.prototype.submit = function () {
                    var _this = this;
                    this._settingService.edit(this.settings)
                        .subscribe(function (data) { _this.success(); }, function (error) { return _this.errorMessage = error; });
                };
                /**
                 * Handles success
                 */
                SettingsComponent.prototype.success = function () {
                    toast.show('success');
                };
                /**
                 * Resets screen
                 */
                SettingsComponent.prototype.reset = function () {
                    this.drawerVisible = false;
                };
                /**
                 * Sets the setting to active
                 *
                 * @param {Setting} setting
                 */
                SettingsComponent.prototype.setActive = function (setting) {
                    this.selectedSetting = setting;
                    this.listItems();
                };
                /**
                 * Shows the drawer
                 */
                SettingsComponent.prototype.toggleDrawer = function () {
                    this.drawerVisible = !this.drawerVisible;
                };
                SettingsComponent = __decorate([
                    core_1.Component({
                        selector: 'respond-settings',
                        templateUrl: './app/settings/settings.component.html',
                        providers: [setting_service_1.SettingService],
                        directives: [drawer_component_1.DrawerComponent]
                    }),
                    router_1.CanActivate(function () { return angular2_jwt_1.tokenNotExpired(); }), 
                    __metadata('design:paramtypes', [(typeof (_a = typeof setting_service_1.SettingService !== 'undefined' && setting_service_1.SettingService) === 'function' && _a) || Object])
                ], SettingsComponent);
                return SettingsComponent;
                var _a;
            }());
            exports_1("SettingsComponent", SettingsComponent);
        }
    }
});
//# sourceMappingURL=settings.component.js.map