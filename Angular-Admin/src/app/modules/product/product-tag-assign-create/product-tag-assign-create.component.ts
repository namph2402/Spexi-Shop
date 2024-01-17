import {Component} from '@angular/core';
import {AbstractModalComponent} from '../../../core/crud';
import {FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {BsModalRef} from 'ngx-bootstrap';
import {FieldForm} from '../../../core/common';
import {ProductService} from '../../product/product.service';
import {ProductMeta} from '../../product/product.meta';
import {ObjectUtil} from '../../../core';
import {ProductTagService} from '../../product-tag/product-tag.service';

@Component({
  selector: 'app-product-tag-assign-create',
  templateUrl: './product-tag-assign-create.component.html',
  styleUrls: ['./product-tag-assign-create.component.css'],
  providers: [ProductService, ProductTagService]
})
export class ProductTagAssignCreateComponent extends AbstractModalComponent<ProductMeta> {

  constructor(
    service: ProductService,
    modal: BsModalRef,
    builder: FormBuilder,
    private tagService: ProductTagService
  ) {
    super(service, modal, builder);
  }

  onInit(): void {
  }

  onDestroy(): void {
  }

  loadAllTags() {
    return this.tagService.loadByParams({product_id_add: this.model.id});
  }

  buildForm(): FormGroup {
    return this.formBuilder.group({
      product_id: new FormControl(null),
      ids: new FormControl(null, Validators.required),
      tags: new FormControl(null, Validators.required)
    });
  }

  initFieldForm(): FieldForm[] {
    return [
      FieldForm.createMultiSelect2('Thẻ sản phẩm', 'tags', 'Chọn nhiều', 'loadAllTags')
    ];
  }

  loaded(): void {
    this.formGroup.controls['product_id'].setValue(this.model.id);
  }

  onFormChanged(): void {
    super.onFormChanged();
    this.formGroup.controls['tags'].valueChanges.subscribe(value => {
      if (value && value.length > 0) {
        this.formGroup.controls['ids'].setValue(value.map(v => v.id));
      } else {
        this.formGroup.controls['ids'].setValue(null);
      }
    });
  }

  assign() {
    let ids: number[] = this.formGroup.controls['ids'].value;
    (<ProductService>this.service).attachTags(this.model.id, ids).subscribe((res: ProductMeta) => {
      this.service.toastSuccessfully('Thêm tag');
      this.close(ObjectUtil.mergeValue(this.model, res));
    }, () => this.service.toastFailed('Thêm tag'));
  }
}
