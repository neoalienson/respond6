System.register(['angular2/core', 'angular2-jwt/angular2-jwt', 'angular2/router', '/app/shared/services/menu.service', '/app/shared/components/add-menu/add-menu.component', '/app/shared/components/remove-menu/remove-menu.component', '/app/shared/components/drawer/drawer.component'], function(exports_1, context_1) {
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
    var core_1, angular2_jwt_1, router_1, menu_service_1, add_menu_component_1, remove_menu_component_1, drawer_component_1;
    var MenusComponent;
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
            function (menu_service_1_1) {
                menu_service_1 = menu_service_1_1;
            },
            function (add_menu_component_1_1) {
                add_menu_component_1 = add_menu_component_1_1;
            },
            function (remove_menu_component_1_1) {
                remove_menu_component_1 = remove_menu_component_1_1;
            },
            function (drawer_component_1_1) {
                drawer_component_1 = drawer_component_1_1;
            }],
        execute: function() {
            MenusComponent = (function () {
                function MenusComponent(_menuService) {
                    this._menuService = _menuService;
                }
                /**
                 * Init
                 *
                 */
                MenusComponent.prototype.ngOnInit = function () {
                    this.id = localStorage.getItem('respond.siteId');
                    this.addVisible = false;
                    this.removeVisible = false;
                    this.drawerVisible = false;
                    this.menus = {};
                    this.items = {};
                    this.list();
                };
                /**
                 * Updates the list
                 */
                MenusComponent.prototype.list = function () {
                    var _this = this;
                    this.reset();
                    this._menuService.list()
                        .subscribe(function (data) { _this.menus = data; _this.success(); }, function (error) { return _this.errorMessage = error; });
                };
                /**
                 * handles the list successfully updated
                 */
                MenusComponent.prototype.success = function () {
                    // default to the first menu returned
                    if (this.selectedMenu === null && this.menus.length > 0) {
                        this.selectedMenu = this.menus[0];
                    }
                    // update items
                    if (this.selectedMenu !== null) {
                        this.listItems();
                    }
                };
                /**
                 * list items in the menu
                 */
                MenusComponent.prototype.listItems = function () {
                    console.log('listItems for ' + this.selectedMenu.name);
                    /*
                    this._menuService.listItems(this.selectedMenu.name)
                                     .subscribe(
                                       data => { this.menus = data; this.success(); },
                                       error =>  this.errorMessage = <any>error
                                      );*/
                };
                /**
                 * Resets screen
                 */
                MenusComponent.prototype.reset = function () {
                    this.removeVisible = false;
                    this.addVisible = false;
                    this.drawerVisible = false;
                    this.menu = {};
                };
                /**
                 * Sets the menu to active
                 *
                 * @param {Menu} menu
                 */
                MenusComponent.prototype.setActive = function (menu) {
                    this.selectedMenu = menu;
                };
                /**
                 * Sets the list item to active
                 *
                 * @param {MenuItem} item
                 */
                MenusComponent.prototype.setItemActive = function (item) {
                    this.selectedItem = item;
                };
                /**
                 * Shows the drawer
                 */
                MenusComponent.prototype.toggleDrawer = function () {
                    this.drawerVisible = !this.drawerVisible;
                };
                /**
                 * Shows the add dialog
                 */
                MenusComponent.prototype.showAdd = function () {
                    this.addVisible = true;
                };
                /**
                 * Shows the remove dialog
                 *
                 * @param {menu} menu
                 */
                MenusComponent.prototype.showRemove = function (menu) {
                    this.removeVisible = true;
                    this.menu = menu;
                };
                MenusComponent = __decorate([
                    core_1.Component({
                        selector: 'respond-menus',
                        templateUrl: './app/menus/menus.component.html',
                        providers: [menu_service_1.MenuService],
                        directives: [add_menu_component_1.AddMenuComponent, remove_menu_component_1.RemoveMenuComponent, drawer_component_1.DrawerComponent]
                    }),
                    router_1.CanActivate(function () { return angular2_jwt_1.tokenNotExpired(); }), 
                    __metadata('design:paramtypes', [(typeof (_a = typeof menu_service_1.MenuService !== 'undefined' && menu_service_1.MenuService) === 'function' && _a) || Object])
                ], MenusComponent);
                return MenusComponent;
                var _a;
            }());
            exports_1("MenusComponent", MenusComponent);
        }
    }
});
//# sourceMappingURL=menus.component.js.map