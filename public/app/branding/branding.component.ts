import {Component} from 'angular2/core'
import {tokenNotExpired} from 'angular2-jwt/angular2-jwt'
import {ROUTER_DIRECTIVES, ROUTER_PROVIDERS, CanActivate} from 'angular2/router'
import {BrandingService} from '/app/shared/services/branding.service'
import {DrawerComponent} from '/app/shared/components/drawer/drawer.component';

@Component({
    selector: 'respond-branding',
    templateUrl: './app/branding/branding.component.html',
    providers: [BrandingService],
    directives: [DrawerComponent]
})

@CanActivate(() => tokenNotExpired())

export class BrandingComponent {

  id;
  settings;
  errorMessage;
  selectedSetting;
  drawerVisible: boolean;

  constructor (private _brandingService: BrandingService) {}

  /**
   * Init
   *
   */
  ngOnInit() {

    this.id = localStorage.getItem('respond.siteId');
    this.drawerVisible = false;
    this.settings;

    this.list();

  }

  /**
   * Updates the list
   */
  list() {

    this.reset();

    this._brandingService.list()
                     .subscribe(
                       data => { this.settings = data; },
                       error =>  this.errorMessage = <any>error
                      );

  }

  /**
   * Handles the form submission
   */
  submit() {

    this._brandingService.edit(this.settings)
                     .subscribe(
                       data => { this.success(); },
                       error =>  this.errorMessage = <any>error
                      );

  }

  /**
   * Handles success
   */
  success() {
    toast.show('success');
  }

  /**
   * Resets screen
   */
  reset() {
    this.drawerVisible = false;
  }

  /**
   * Sets the setting to active
   *
   * @param {Setting} setting
   */
  setActive(setting) {
    this.selectedSetting = setting;

    this.listItems();
  }

  /**
   * Shows the drawer
   */
  toggleDrawer() {
    this.drawerVisible = !this.drawerVisible;
  }

}