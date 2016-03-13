System.register(['angular2/core', 'angular2/router', 'angular2-jwt/angular2-jwt'], function(exports_1, context_1) {
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
    var core_1, router_1, angular2_jwt_1;
    var DrawerComponent;
    return {
        setters:[
            function (core_1_1) {
                core_1 = core_1_1;
            },
            function (router_1_1) {
                router_1 = router_1_1;
            },
            function (angular2_jwt_1_1) {
                angular2_jwt_1 = angular2_jwt_1_1;
            }],
        execute: function() {
            DrawerComponent = (function () {
                function DrawerComponent() {
                    this._visible = false;
                    this.onHide = new core_1.EventEmitter();
                }
                Object.defineProperty(DrawerComponent.prototype, "visible", {
                    get: function () { return this._visible; },
                    set: function (visible) {
                        this._visible = visible;
                    },
                    enumerable: true,
                    configurable: true
                });
                /**
                 * Init pages
                 */
                DrawerComponent.prototype.ngOnInit = function () { };
                /**
                 * Hides the add page modal
                 */
                DrawerComponent.prototype.hide = function () {
                    this._visible = false;
                    this.onHide.emit(null);
                };
                __decorate([
                    core_1.Input(), 
                    __metadata('design:type', Boolean), 
                    __metadata('design:paramtypes', [Boolean])
                ], DrawerComponent.prototype, "visible", null);
                __decorate([
                    core_1.Output(), 
                    __metadata('design:type', Object)
                ], DrawerComponent.prototype, "onHide", void 0);
                DrawerComponent = __decorate([
                    core_1.Component({
                        selector: 'respond-drawer',
                        templateUrl: './app/shared/components/drawer/drawer.component.html',
                        directives: [router_1.ROUTER_DIRECTIVES]
                    }),
                    router_1.CanActivate(function () { return angular2_jwt_1.tokenNotExpired(); }), 
                    __metadata('design:paramtypes', [])
                ], DrawerComponent);
                return DrawerComponent;
            }());
            exports_1("DrawerComponent", DrawerComponent);
        }
    }
});
//# sourceMappingURL=drawer.component.js.map