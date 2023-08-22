import {Component} from '@angular/core';
import {AbstractModalComponent} from '../../../core/crud';
import {FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {BsModalRef} from 'ngx-bootstrap';
import {NotificationMeta} from '../notification.meta';
import {NotificationService} from '../notification.service';
import {FieldForm} from '../../../core/common';

@Component({
  selector: 'app-notification-edit',
  templateUrl: './notification-edit.component.html',
  styleUrls: ['./notification-edit.component.css'],
  providers: [NotificationService]
})
export class NotificationEditComponent extends AbstractModalComponent<NotificationMeta> {

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
