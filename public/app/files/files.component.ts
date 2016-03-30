import {Component} from 'angular2/core'
import {tokenNotExpired} from 'angular2-jwt/angular2-jwt'
import {ROUTER_DIRECTIVES, ROUTER_PROVIDERS, CanActivate} from 'angular2/router'
import {FileService} from '/app/shared/services/file.service'
import {RemoveFileComponent} from '/app/shared/components/remove-file/remove-file.component';
import {DrawerComponent} from '/app/shared/components/drawer/drawer.component';

@Component({
    selector: 'respond-files',
    templateUrl: './app/files/files.component.html',
    providers: [FileService],
    directives: [RemoveFileComponent, DrawerComponent],
})

@CanActivate(() => tokenNotExpired())

export class FilesComponent {

  file;
  files;
  errorMessage;
  selectedFile;
  removeFileVisible: boolean;

  constructor (private _fileService: FileService) {}

  /**
   * Init files
   *
   */
  ngOnInit() {

    this.id = localStorage.getItem('respond.siteId');
    this.removeFileVisible = false;
    this.drawerVisible = false;
    this.file = {};

    this.list();

  }

  /**
   * Updates the list
   */
  list() {

    this.reset();
    this._fileService.list()
                     .subscribe(
                       data => { this.files = data; },
                       error =>  this.errorMessage = <any>error
                      );
  }

  /**
   * Resets an modal booleans
   */
  reset() {
    this.removeFileVisible = false;
    this.drawerVisible = false;
    this.file = {};
  }

  /**
   * Sets the list item to active
   *
   * @param {File} file
   */
  setActive(file) {
    this.selectedFile = file;
  }

  /**
   * Shows the drawer
   */
  toggleDrawer() {
    this.drawerVisible = !this.drawerVisible;
  }

  /**
   * Shows the remove dialog
   *
   * @param {File} file
   */
  showRemove(file) {
    this.removeFileVisible = true;
    this.file = file;
  }

}