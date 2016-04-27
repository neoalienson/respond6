import {Component, EventEmitter, Input, Output} from 'angular2/core';
import {CanActivate} from 'angular2/router'
import {tokenNotExpired} from 'angular2-jwt/angular2-jwt'
import {SubmissionService} from '/app/shared/services/submission.service'

@Component({
    selector: 'respond-view-submission',
    templateUrl: './app/shared/components/submissions/view-submission/view-submission.component.html',
    providers: [SubmissionService]
})

@CanActivate(() => tokenNotExpired())

export class ViewSubmissionComponent {

  routes;
  errorMessage;

  // model to store
  model: {
    id: '',
    name: '',
    formId: ''
  };

  _visible: boolean = false;

  @Input()
  set visible(visible: boolean){

    // set visible
    this._visible = visible;

  }

  @Input()
  set submission(submission){

    // set visible
    this.model = submission;

  }

  get visible() { return this._visible; }

  @Output() onCancel = new EventEmitter<any>();
  @Output() onUpdate = new EventEmitter<any>();

  constructor (private _submissionService: SubmissionService) {}

  /**
   * Init
   */
  ngOnInit() {

  }

  /**
   * Hides the modal
   */
  hide() {
    this._visible = false;
    this.onCancel.emit(null);
  }


}