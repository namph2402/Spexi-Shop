import {Component} from '@angular/core';
import {AbstractModalComponent} from '../../../core/crud';
import {FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {BsModalRef} from 'ngx-bootstrap';
import {PromotionProductMeta} from '../promotion-product.meta';
import {PromotionProductService} from '../promotion-product.service';
import {FieldForm} from '../../../core/common';
import {ObjectUtil} from '../../../core/utils';
import {ProductService} from '../../product/product.service';

@Component({
  selector: 'app-promotion-product-edit',
  templateUrl: './promotion-product-edit.component.html',
  styleUrls: ['./promotion-product-edit.component.css'],
  providers: [ProductService, PromotionProductService]
})
export class PromotionProductEditComponent extends AbstractModalComponent<PromotionProductMeta> {

  constructor(
    service: PromotionProductService,
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
      sale_price_value: new FormControl(null, [Validators.required, Validators.min(1), Validators.max(99999999), Validators.pattern('^(?=.*[0-9]+)[0-9]*$')]),
      discount_percent: new FormControl(null, [Validators.required, Validators.min(1), Validators.max(99), Validators.pattern('^(?=.*[0-9]+)[0-9]*$')]),
    });
  }

  initFieldForm(): FieldForm[] {
    return [
      FieldForm.createNumberInput('Giá sale', 'sale_price_value', 'Nhập giá sale'),
      FieldForm.createNumberInput('% giảm', 'discount_percent', 'Nhập % giảm'),
    ];
  }

  loaded(): void {
  }

  setFormValue() {
    let item: any = ObjectUtil.clone(this.model);
    let precent = Math.round(100 - (item.sale_price / item.price * 100));
    this.formGroup.get('sale_price_value').setValue(item.sale_price);
    this.formGroup.get('discount_percent').setValue(precent);
  }

  onFormChanged(): void {
    super.onFormChanged();
    this.formGroup.controls['sale_price_value'].valueChanges.subscribe((value: any) => {
      if (value && value.length > 0) {
        let discountPercent = Math.round((this.model['price'] - value) / this.model['price'] * 100);
        this.formGroup.get('discount_percent').setValue(discountPercent);
      }
    });
    this.formGroup.controls['discount_percent'].valueChanges.subscribe((value: any) => {
      if (value && value.length > 0) {
        let discountValue = Math.round(this.model['price'] - (this.model['price'] * value / 100));
        this.formGroup.get('sale_price_value').setValue(discountValue);
      }
    });
  }

  editSale() {
    let item: any = ObjectUtil.combineValue(this.model, this.formGroup.value);
    (<PromotionProductService>this.service).updateSalePrice(item.promotions, item).subscribe(res => {
      this.service.toastSuccess('Sửa giá sale thành công');
      this.close(res);
    }, () => this.service.toastFailed('Sửa giá sale thất bại'));
  }
}
