import {Component} from '@angular/core';
import {AbstractModalComponent} from '../../../core/crud';
import {FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {BsModalRef} from 'ngx-bootstrap';
import {MenuService} from '../menu.service';
import {MenuMeta} from '../menu.meta';
import {FieldForm} from '../../../core/common';

@Component({
  selector: 'app-menu-create',
  templateUrl: './menu-create.component.html',
  styleUrls: ['./menu-create.component.css'],
  providers: [MenuService]
})
export class MenuCreateComponent extends AbstractModalComponent<MenuMeta> {

  constructor(
    service: MenuService,
    modal: BsModalRef,
    builder: FormBuilder,
  ) {
    super(service, modal, builder);
  }

  onInit(): void {
  }

  onDestroy(): void {
  }

  loadAllMenu() {
    return this.service.loadByParams({group_id: this.model.group_id});
  }

  buildForm(): FormGroup {
    return this.formBuilder.group({
      parent: new FormControl(null),
      parent_id: new FormControl(0),
      name: new FormControl(null, [Validators.required, Validators.maxLength(255), Validators.pattern('[^ ].*$')]),
      url: new FormControl(null, [Validators.required, Validators.maxLength(255), Validators.pattern('[^ ].*$')]),
      group_id: new FormControl()
    });
  }

  initFieldForm(): FieldForm[] {
    return [
      FieldForm.createSingleSelect2('Menu cha', 'parent', 'Chọn menu cha', 'loadAllMenu'),
      FieldForm.createTextInput('Tên', 'name', 'Nhập tên'),
      FieldForm.createTextInput('Đường dẫn', 'url', 'Nhập đường dẫn'),
    ];
  }

  loaded(): void {
    this.formGroup.controls['group_id'].setValue(this.model.group_id);
  }

  onFormChanged() {
    this.formGroup.controls['parent'].valueChanges.subscribe((value => {
      if (value && value.length > 0) {
        this.formGroup.controls['parent_id'].setValue(value[0].id);
      } else {
        this.formGroup.controls['parent_id'].setValue(0);
      }
    }));
  }
}

