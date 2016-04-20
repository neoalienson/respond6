System.register(['angular2/core', 'angular2-jwt/angular2-jwt', 'angular2/router', '/app/shared/services/branding.service', '/app/shared/components/drawer/drawer.component'], function(exports_1, context_1) {
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
    var core_1, angular2_jwt_1, router_1, branding_service_1, drawer_component_1;
    var BrandingComponent;
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
            function (branding_service_1_1) {
                branding_service_1 = branding_service_1_1;
            },
            function (drawer_component_1_1) {
                drawer_component_1 = drawer_component_1_1;
            }],
        execute: function() {
            BrandingComponent = (function () {
                function BrandingComponent(_brandingService) {
                    this._brandingService = _brandingService;
                }
                /**
                 * Init
                 *
                 */
                BrandingComponent.prototype.ngOnInit = function () {
                    this.id = localStorage.getItem('respond.siteId');
                    this.drawerVisible = false;
                    this.settings;
                    this.list();
                };
                /**
                 * Updates the list
                 */
                BrandingComponent.prototype.list = function () {
                    var _this = this;
                    this.reset();
                    this._brandingService.list()
                        .subscribe(function (data) { _this.settings = data; }, function (error) { return _this.errorMessage = error; });
                };
                /**
                 * Handles the form submission
                 */
                BrandingComponent.prototype.submit = function () {
                    var _this = this;
                    this._brandingService.edit(this.settings)
                        .subscribe(function (data) { _this.success(); }, function (error) { return _this.errorMessage = error; });
                };
                /**
                 * Handles success
                 */
                BrandingComponent.prototype.success = function () {
                    toast.show('success');
                };
                /**
                 * Resets screen
                 */
                BrandingComponent.prototype.reset = function () {
                    this.drawerVisible = false;
                };
                /**
                 * Sets the setting to active
                 *
                 * @param {Setting} setting
                 */
                BrandingComponent.prototype.setActive = function (setting) {
                    this.selectedSetting = setting;
                    this.listItems();
                };
                /**
                 * Shows the drawer
                 */
                BrandingComponent.prototype.toggleDrawer = function () {
                    this.drawerVisible = !this.drawerVisible;
                };
                BrandingComponent = __decorate([
                    core_1.Component({
                        selector: 'respond-branding',
                        templateUrl: './app/branding/branding.component.html',
                        providers: [branding_service_1.BrandingService],
                        directives: [drawer_component_1.DrawerComponent]
                    }),
                    router_1.CanActivate(function () { return angular2_jwt_1.tokenNotExpired(); }), 
                    __metadata('design:paramtypes', [(typeof (_a = typeof branding_service_1.BrandingService !== 'undefined' && branding_service_1.BrandingService) === 'function' && _a) || Object])
                ], BrandingComponent);
                return BrandingComponent;
                var _a;
            }());
            exports_1("BrandingComponent", BrandingComponent);
        }
    }
});
//# sourceMappingURL=branding.component.js.map