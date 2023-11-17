import {Component} from '@angular/core';
import {FormBuilder, FormControl, FormGroup} from '@angular/forms';
import {BsModalRef, BsModalService, ModalOptions} from 'ngx-bootstrap';
import {AbstractCRUDModalComponent, AbstractModalComponent} from '../../../core/crud';
import {PromotionProductCreateComponent} from '../promotion-product-create/promotion-product-create.component';
import {PromotionProductEditComponent} from '../promotion-product-edit/promotion-product-edit.component';
import {PromotionProductService} from '../promotion-product.service';
import {PromotionProductMeta} from '../promotion-product.meta';
import {ObjectUtil} from '../../../core/utils';
import {AppPagination, FieldForm, ModalResult} from '../../../core/common';
import {ProductCategoryService} from '../../product-category/product-category.service';
import {ProductMeta} from '../../product/product.meta';

@Component({
  selector: 'app-promotion-product-list',
  templateUrl: './promotion-product-list.component.html',
  styleUrls: ['./promotion-product-list.component.css'],
  providers: [PromotionProductService, ProductCategoryService]
})
export class PromotionProductListComponent extends AbstractCRUDModalComponent<PromotionProductMeta> {

  promotion: any;

  constructor(
    service: PromotionProductService,
    modal: BsModalRef,
    modalService: BsModalService,
    builder: FormBuilder,
    private categoryService: ProductCategoryService,
  ) {
    super(service, modal, modalService, builder);
    this.loadAllCategory();
  }

  onInit(): void {
  }

  onDestroy(): void {
  }

  getTitle(): string {
    return 'Quản lý sản phẩm khuyến mãi';
  }

  getCreateModalComponent(): any {
    return PromotionProductCreateComponent;
  }

  getEditModalComponent(): any {
    return PromotionProductEditComponent;
  }

  getCreateModalComponentOptions(): ModalOptions {
    return {'class': 'modal-lg'};
  }

  getEditModalComponentOptions(): ModalOptions {
    return {'class': 'modal-lg'};
  }

  buildSearchForm(): FormGroup {
    return this.formBuilder.group({
      search: new FormControl(null),
      category_id: new FormControl(null)
    });
  }

  initSearchForm(): FieldForm[] {
    return [
      FieldForm.createTextInput('Tìm kiếm theo tên', 'search', 'Nhập từ khóa'),
      FieldForm.createSelect('Tìm kiếm danh mục', 'category_id', 'Chọn một', []),
    ];
  }

  initNewModel(): PromotionProductMeta {
    let promotion: PromotionProductMeta = new PromotionProductMeta();
    promotion.promotion_id = this.relatedModel.id;
    promotion.existsProducts = this.list;
    return promotion;
  }

  loaded(): void {
    this.load();
  }

  load(): void {
    this.promotion = this.relatedModel.type;
    let param: any = ObjectUtil.combineValue({
      limit: this.pagination.itemsPerPage,
      page: this.pagination.currentPage,
    }, this.searchForm.value, true);
    (<PromotionProductService>this.service).loadProduct(this.relatedModel.id, param).subscribe((res: any) => {
      this.nextPage = this.pagination.currentPage;
      this.list = res.data;
      this.pagination.set(res);
    }, () => {
      this.list = [];
      this.pagination = new AppPagination();
      this.nextPage = this.pagination.currentPage;
    });
  }

  createPromotionProduct() {
    let modalOptions = Object.assign(this.defaultModalOptions(), this.getCreateModalComponentOptions());
    const config = ObjectUtil.combineValue({ignoreBackdropClick: true}, modalOptions);
    const modalRef = this.modalService.show(this.getCreateModalComponent(), config);
    let modal: AbstractModalComponent<PromotionProductMeta> = <AbstractModalComponent<PromotionProductMeta>>modalRef.content;
    modal.setModel(this.initNewModel());
    modal.onHidden.subscribe((result: ModalResult<PromotionProductMeta>) => {
      this.load();
    });
  }

  editPromotionProduct(item) {
    let modalOptions = Object.assign(this.defaultModalOptions(), this.getEditModalComponentOptions());
    const config = ObjectUtil.combineValue({ignoreBackdropClick: true}, modalOptions);
    const modalRef = this.modalService.show(this.getEditModalComponent(), config);
    let modal: AbstractModalComponent<ProductMeta> = <AbstractModalComponent<ProductMeta>>modalRef.content;
    let model = new ProductMeta();
    model.id = item.id;
    model.sale_price = item.sale_price;
    model.price = item.price;
    model.promotions = this.relatedModel.id;
    modal.setModel(model);
    modal.onHidden.subscribe((result: ModalResult<PromotionProductMeta>) => {
      if (result.success) {
        this.load();
      }
    });
  }

  removeProduct(item) {
    (<PromotionProductService>this.service).detachProduct(this.relatedModel.id, {product_id: item.id}).subscribe(res => {
      this.service.toastSuccessfullyDeleted();
      this.load();
    }, () => this.service.toastFailedDeleted());
  }

  loadAllCategory() {
    return this.categoryService.loadByParams({child: 0}).subscribe(value => {
      this.searchControls[1].data = value;
    });
  }

}
