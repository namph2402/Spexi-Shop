import {Component} from '@angular/core';
import {AbstractCRUDComponent, AbstractCRUDModalComponent, AbstractModalComponent} from '../../../core/crud';
import {BsModalService, ModalOptions} from 'ngx-bootstrap';
import {FormBuilder, FormControl, FormGroup} from '@angular/forms';
import {ProductMeta} from '../product.meta';
import {ProductService} from '../product.service';
import {ProductCreateComponent} from '../product-create/product-create.component';
import {ProductEditComponent} from '../product-edit/product-edit.component';
import {FieldForm, ModalResult} from '../../../core/common';
import {ObjectUtil} from '../../../core/utils';
import {ArticleCreateComponent} from '../../article/article-create/article-create.component';
import {ArticleMeta} from '../../article/article.meta';
import {ArticleEditComponent} from '../../article/article-edit/article-edit.component';
import {ProductImageMeta} from '../../product-image/product-image.meta';
import {ProductImageListComponent} from '../../product-image/product-image-list/product-image-list.component';
import {ArticleCommentListComponent} from '../../article-comment/article-comment-list/article-comment-list.component';
import {ArticleCommentMeta} from '../../article-comment/article-comment.meta';
import {ProductCategoryService} from '../../product-category/product-category.service';
import {ProductTagService} from '../../product-tag/product-tag.service';
import {ProductTagAssignListComponent} from '../../product-tag/product-tag-assign-list/product-tag-assign-list.component';
import {ProductVariantListComponent} from '../../product-variant/product-variant-list/product-variant-list.component';
import {ProductVariantMeta} from '../../product-variant/product-variant.meta';
import {ProductImportComponent} from '../product-import/product-import.component';
import {ProductRelatedListComponent} from '../../product-related/product-related-list/product-related-list.component';
import {ProductRelatedMeta} from '../../product-related/product-related.meta';

@Component({
  selector: 'app-product',
  templateUrl: './product-list.component.html',
  styleUrls: ['./product-list.component.css'],
  providers: [ProductService, ProductCategoryService, ProductTagService]
})
export class ProductListComponent extends AbstractCRUDComponent<ProductMeta> {

  constructor(
    service: ProductService,
    modal: BsModalService,
    builder: FormBuilder,
    private categoryService: ProductCategoryService,
    private tagService: ProductTagService,
  ) {
    super(service, modal, builder);
  }

  onInit(): void {
    this.load();
  }

  onDestroy(): void {
  }

  getTitle(): string {
    return 'Quản lý sản phẩm';
  }

  getCreateModalComponent(): any {
    return ProductCreateComponent;
  }

  getEditModalComponent(): any {
    return ProductEditComponent;
  }

  getCreateModalComponentOptions(): ModalOptions {
    return {'class': 'modal-lg', ignoreBackdropClick: true};
  }

  getEditModalComponentOptions(): ModalOptions {
    return {'class': 'modal-lg', ignoreBackdropClick: true};
  }

  loadAllCategory() {
    return this.categoryService.loadAll();
  }

  loadAllTag() {
    return this.tagService.loadAll();
  }

  buildSearchForm(): FormGroup {
    return this.formBuilder.group({
      search: new FormControl(null),
      category_id: new FormControl(null),
      status: new FormControl(null),
      tag_id: new FormControl(null),
    });
  }

  initSearchForm(): FieldForm[] {
    return [
      FieldForm.createTextInput('Tìm kiếm theo tên', 'search', 'Nhập từ khóa', 'col-md-6'),
      FieldForm.createSelect('Tìm kiếm danh mục', 'category_id', 'Chọn một', 'loadAllCategory', 'col-md-6'),
      FieldForm.createSelect('Tìm kiếm trạng thái', 'status', 'Chọn một', [
        {
          id: '0',
          name: 'Tất cả',
          value: 'all'
        },
        {
          id: 1,
          name: 'Hoạt động',
          value: '1'
        },
        {
          id: 2,
          name: 'Không hoạt động',
          value: '0'
        },
      ], 'col-md-6', 'name', 'value'),
      FieldForm.createSelect('Tìm kiếm thẻ', 'tag_id', 'Chọn một', 'loadAllTag', 'col-md-6'),
    ];
  }

  initNewModel(): ProductMeta {
    return new ProductMeta();
  }

  onStatusChange(item: ProductMeta, index: number, enable: boolean) {
    let methodAsync = null;
    let titleMsg: string = 'Phát hành';
    if (enable) {
      methodAsync = this.service.enable(item.id);
    } else {
      methodAsync = this.service.disable(item.id);
      titleMsg = 'Lưu kho';
    }
    methodAsync.subscribe((res: ProductMeta) => {
      this.service.toastSuccessfully(titleMsg);
    }, () => this.service.toastFailed(titleMsg));
    this.load();
  }

  createProduct() {
    let modalOptions = Object.assign(this.defaultModalOptions(), this.getCreateModalComponentOptions());
    const config = ObjectUtil.combineValue({ignoreBackdropClick: true}, modalOptions);
    const modalRef = this.modalService.show(this.getCreateModalComponent(), config);
    let modal: AbstractModalComponent<ProductMeta> = <AbstractModalComponent<ProductMeta>>modalRef.content;
    modal.setModel(this.initNewModel());
    modal.onHidden.subscribe((result: ModalResult<ProductMeta>) => {
      if (result.success) {
        this.load();
      }
    });
  }

  editProduct(item) {
    let modalOptions = Object.assign(this.defaultModalOptions(), this.getEditModalComponentOptions());
    const config = ObjectUtil.combineValue({ignoreBackdropClick: true}, modalOptions);
    const modalRef = this.modalService.show(this.getEditModalComponent(), config);
    let modal: AbstractModalComponent<ProductMeta> = <AbstractModalComponent<ProductMeta>>modalRef.content;
    modal.setModel(item);
    modal.onHidden.subscribe((result: ModalResult<ProductMeta>) => {
      if (result.success) {
        this.load();
      }
    });
  }

  upOrder(item: ProductMeta) {
    (<ProductService>this.service).up(item.id).subscribe(res => {
      this.service.toastSuccessfully('Tăng thứ tự');
      this.load();
    }, () => this.service.toastFailed('Tăng thứ tự'));
  }

  downOrder(item: ProductMeta) {
    (<ProductService>this.service).down(item.id).subscribe(res => {
      this.service.toastSuccessfully('Giảm thứ tự');
      this.load();
    }, () => this.service.toastFailed('Giảm thứ tự'));
  }

  createArticle(item: ProductMeta) {
    const modalRef = this.modalService.show(ArticleCreateComponent, {ignoreBackdropClick: true, 'class': 'modal-lg'});
    const modal: AbstractModalComponent<ArticleMeta> = <AbstractModalComponent<ArticleMeta>>modalRef.content;
    const model = new ArticleMeta();
    model.articleable_type = 'products';
    model.articleable_id = item.id;
    modal.setModel(model);
    let sub = modal.onHidden.subscribe((result: ModalResult<ArticleMeta>) => {
      if (result.success) {
        this.load();
      }
      sub.unsubscribe();
    });
  }

  editArticle(item: ProductMeta) {
    const modalRef = this.modalService.show(ArticleEditComponent, {ignoreBackdropClick: true, 'class': 'modal-lg'});
    const modal: AbstractModalComponent<ArticleMeta> = <AbstractModalComponent<ArticleMeta>>modalRef.content;
    modal.setModel(item.article);
    const sub = modal.onHidden.subscribe((result: ModalResult<ArticleMeta>) => {
      if (result.success) {
        this.load();
      }
      sub.unsubscribe();
    });
  }

  showTags(item: ProductMeta) {
    const modalRef = this.modalService.show(ProductTagAssignListComponent, {
      ignoreBackdropClick: true,
      'class': 'modal-lg'
    });
    let modal: AbstractCRUDModalComponent<ProductMeta> = <AbstractCRUDModalComponent<ProductMeta>>modalRef.content;
    modal.setRelatedModel(item);
    (item);
    let sub = modal.onHidden.subscribe((result: ModalResult<ProductMeta>) => {
      if (result.success) {
        this.load();
      }
      sub.unsubscribe();
    });
  }

  showImage(item: ProductMeta) {
    let modalRef = this.modalService.show(ProductImageListComponent, {ignoreBackdropClick: true, class: 'modal-lg'});
    let modal: AbstractCRUDModalComponent<ProductImageMeta> = <AbstractCRUDModalComponent<ProductImageMeta>>modalRef.content;
    modal.setRelatedModel(item);
    let sub = modal.onHidden.subscribe((result: ModalResult<ProductImageMeta[]>) => {
      if (result.success) {
        this.load();
      }
      sub.unsubscribe();
    });
  }

  showComment(item: ProductMeta) {
    let modalRef = this.modalService.show(ArticleCommentListComponent, {ignoreBackdropClick: true, class: 'modal-lg'});
    let modal: AbstractCRUDModalComponent<ArticleCommentMeta> = <AbstractCRUDModalComponent<ArticleCommentMeta>>modalRef.content;
    modal.setRelatedModel(item.article);
    let sub = modal.onHidden.subscribe((result: ModalResult<ArticleCommentMeta[]>) => {
      if (result.success) {
        this.load();
      }
      sub.unsubscribe();
    });
  }

  showVariant(item: ProductMeta) {
    let modalRef = this.modalService.show(ProductVariantListComponent, {ignoreBackdropClick: true, class: 'modal-lg'});
    let modal: AbstractCRUDModalComponent<ProductVariantMeta> = <AbstractCRUDModalComponent<ProductVariantMeta>>modalRef.content;
    modal.setRelatedModel(item);
    let sub = modal.onHidden.subscribe((result: ModalResult<ProductVariantMeta[]>) => {
      if (result.success) {
        this.load();
      }
      sub.unsubscribe();
    });
  }

  showRelated(item: ProductMeta) {
    let modalRef = this.modalService.show(ProductRelatedListComponent, {ignoreBackdropClick: true, class: 'modal-lg'});
    let modal: AbstractCRUDModalComponent<ProductRelatedMeta> = <AbstractCRUDModalComponent<ProductRelatedMeta>>modalRef.content;
    modal.setRelatedModel(item);
    let sub = modal.onHidden.subscribe((result: ModalResult<ProductRelatedMeta[]>) => {
      if (result.success) {
        this.load();
      }
      sub.unsubscribe();
    });
  }

  import() {
    const config = {ignoreBackdropClick: true};
    const modalRef = this.modalService.show(ProductImportComponent, config);
    let modal: AbstractModalComponent<any> = <AbstractModalComponent<any>>modalRef.content;
    let sub = modal.onHidden.subscribe((result: ModalResult<any>) => {
      if (result.success) {
        this.load();
      }
      sub.unsubscribe();
    });
  }
}


