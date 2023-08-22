import {Component} from '@angular/core';
import {AbstractModalComponent} from '../../../core/crud';
import {VariantMeta} from '../variant.meta';
import {FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {BsModalRef} from 'ngx-bootstrap';
import {VariantService} from '../variant.service';
import {FieldForm} from '../../../core/common';
import {ObjectUtil} from '../../../core';
import {ProductSizeService} from '../../product-size/product-size.service';
import {ProductColorService} from '../../product-color/product-color.service';

@Component({
  selector: 'app-variant-create',
  templateUrl: './variant-create.component.html',
  styleUrls: ['./variant-create.component.css'],
  providers: [VariantService, ProductSizeService, ProductColorService]
})
export class VariantCreateComponent extends AbstractModalComponent<VariantMeta> {

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

  loadAllCategories() {
    return this.service.loadByParams({parent: 0});
  }

  buildForm(): FormGroup {
    return this.formBuilder.group({
      type: new FormControl(null, Validators.required),
      name: new FormControl(null, [Validators.required, Validators.maxLength(255), Validators.pattern('[^ ].*$')]),
    });
  }

  initFieldForm(): FieldForm[] {
    return [
      FieldForm.createSelect('Loại', 'type', 'Chọn một', [
        {
          id: 0,
          name: 'Size',
        },
        {
          id: 1,
          name: 'Color',
        },
      ]),
      FieldForm.createTextInput('Tên', 'name', 'Nhập tên'),
    ];
  }

  loaded(): void {
  }

  create() {
    if (this.formGroup.value.type == 0) {
      let item: any = ObjectUtil.combineValue(this.model, this.formGroup.value);
      this.sizeService.store(item).subscribe(res => {
        this.sizeService.toastSuccessfullyCreated();
        this.close(res);
      }, () => this.sizeService.toastFailedCreated());
    } else {
      let item: any = ObjectUtil.combineValue(this.model, this.formGroup.value);
      this.colorService.store(item).subscribe(res => {
        this.sizeService.toastSuccessfullyCreated();
        this.close(res);
      }, () => this.sizeService.toastFailedCreated());
    }
  }

}
