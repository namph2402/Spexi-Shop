import {Component} from '@angular/core';
import {AbstractModalComponent} from '../../../core/crud';
import {FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {BsModalRef} from 'ngx-bootstrap';
import {FieldForm} from '../../../core/common';
import {VariantService} from '../variant.service';
import {VariantMeta} from '../variant.meta';
import {ObjectUtil} from '../../../core';
import {ProductSizeService} from '../../product-size/product-size.service';
import {ProductColorService} from '../../product-color/product-color.service';

@Component({
  selector: 'app-variant-edit',
  templateUrl: './variant-edit.component.html',
  styleUrls: ['./variant-edit.component.css'],
  providers: [VariantService, ProductSizeService, ProductColorService]
})
export class VariantEditComponent extends AbstractModalComponent<VariantMeta> {

  constructor(
    service: VariantService,
    modal: BsModalRef,
    builder: FormBuilder,
    protected sizeService: ProductSizeService,
    protected colorService: ProductColorService,
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
    });
  }

  initFieldForm(): FieldForm[] {
    return [
      FieldForm.createTextInput('Tên', 'name', 'Nhập tên')
    ];
  }

  loaded(): void {
  }

  edit() {
    if (this.model.type == 0) {
      let item: any = ObjectUtil.combineValue(this.model, this.formGroup.value);
      this.sizeService.update(item).subscribe(res => {
        this.sizeService.toastSuccessfullyEdited();
        this.close(res);
      }, () => this.sizeService.toastFailedEdited());
    } else {
      let item: any = ObjectUtil.combineValue(this.model, this.formGroup.value);
      this.colorService.update(item).subscribe(res => {
        this.colorService.toastSuccessfullyEdited();
        this.close(res);
      }, () => this.colorService.toastFailedEdited());
    }
  }

}
