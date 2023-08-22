import {Component} from '@angular/core';
import {AbstractModalComponent} from '../../../core/crud';
import {FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {BsModalRef} from 'ngx-bootstrap';
import {NotificationService} from '../notification.service';
import {NotificationMeta} from '../notification.meta';
import {FieldForm} from '../../../core/common';

@Component({
  selector: 'app-notification-create',
  templateUrl: './notification-create.component.html',
  styleUrls: ['./notification-create.component.css'],
  providers: [NotificationService]
})
export class NotificationCreateComponent extends AbstractModalComponent<NotificationMeta> {

  constructor(
    service: NotificationService,
    modal: BsModalRef,
    builder: FormBuilder
  ) {
    super(service, modal, builder);
  }

  onInit(): void {
  }

  onDestroy(): void {
  }

  buildForm(): FormGroup {
    return this.formBuilder.group({
      name: new FormControl(null, [Validators.required, Validators.maxLength(255), Validators.pattern('[^ ].*$')]),
      content: new FormControl(null, [Validators.required]),
    });
  }

  initFieldForm(): FieldForm[] {
    return [
      FieldForm.createTextInput('Tên', 'name', 'Nhập tên'),
      FieldForm.createHtmlInput('Nội dung', 'content', {height: '300px'}),
    ];
  }

  loaded(): void {
  }

}
