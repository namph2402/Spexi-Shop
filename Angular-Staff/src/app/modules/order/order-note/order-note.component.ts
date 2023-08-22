import {Component} from '@angular/core';
import {FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {BsModalRef} from 'ngx-bootstrap';
import {OrderMeta} from '../order.meta';
import {OrderService} from '../order.service';
import {AbstractModalComponent, FieldForm, ObjectUtil} from '../../../core';
import {VoucherService} from '../../voucher/voucher.service';
import {ProductService} from '../../product/product.service';
import {ProductMeta} from '../../product/product.meta';
import {CartItemMeta} from '../cart-item.meta';
import {PromotionService} from '../../promotion/promotion.service';
import {ProvinceService} from '../../province/province.service';

@Component({
  selector: 'app-order-note',
  templateUrl: './order-note.component.html',
  styleUrls: ['./order-note.component.css'],
  providers: [PromotionService, OrderService, VoucherService, ProductService, ProvinceService]
})
export class OrderNoteComponent extends AbstractModalComponent<OrderMeta> {

  arrProduct: CartItemMeta[] = [];

  productList: ProductMeta[];

  constructor(
    service: OrderService,
    modal: BsModalRef,
    builder: FormBuilder,
    private productService: ProductService,
    private voucherService: VoucherService,
    private provinceService: ProvinceService
  ) {
    super(service, modal, builder);
  }

  onInit(): void {
  }

  onDestroy(): void {
  }

  loadVouchers() {
    return this.voucherService.loadAll();
  }

  loadAllProvinces() {
    return this.provinceService.loadAll();
  }

  loadAllProducts() {
    return this.productService.loadAll();
  }

  buildForm(): FormGroup {
    return this.formBuilder.group({
      note: new FormControl(null),
    });
  }

  initFieldForm(): FieldForm[] {
    return [
      FieldForm.createTextArea('Lý do hủy đơn', 'note', 'Nhập kí tự'),
    ];
  }

  loaded(): void {
  }

  cancel() {
    let item: any = ObjectUtil.combineValue(this.model, this.formGroup.value);
    (<OrderService>this.service).cancel(item).subscribe(res => {
      this.service.toastSuccessfully('Hủy');
      this.close(res);
    }, () => this.service.toastFailed('Hủy'));
  }
}
