import {NgModule} from '@angular/core';
import {ModalModule, PaginationModule, PopoverModule, TabsModule} from 'ngx-bootstrap';
import {CommonModule} from '@angular/common';
import {FormsModule, ReactiveFormsModule} from '@angular/forms';
import {ConfirmationPopoverModule} from 'angular-confirmation-popover';
import {AngularMultiSelectModule} from 'angular2-multiselect-dropdown';
import {CKEditorModule} from 'ng2-ckeditor';
import {ProductCreateComponent} from './product-create/product-create.component';
import {ProductEditComponent} from './product-edit/product-edit.component';
import {NgSelectModule} from '@ng-select/ng-select';
import {UiSwitchModule} from 'ngx-toggle-switch';
import {ArticleModule} from '../article/article.module';
import {ProductImageModule} from '../product-image/product-image.module';
import {ProductTagModalModule} from '../product-tag/product-tag.modal.module';
import {ArticleCommentModule} from '../article-comment/article-comment.module';
import {ProductWarehouseModule} from '../product-warehouse/product-warehouse.module';
import {ProductImportComponent} from './product-import/product-import.component';
import {ProductRelatedModule} from '../product-related/product-related.module';
import {ProductTagAssignListComponent} from './product-tag-assign-list/product-tag-assign-list.component';
import {ProductTagAssignCreateComponent} from './product-tag-assign-create/product-tag-assign-create.component';

@NgModule({
  imports: [
    CommonModule,
    FormsModule,
    PaginationModule.forRoot(),
    ReactiveFormsModule,
    PopoverModule.forRoot(),
    ConfirmationPopoverModule.forRoot(),
    ModalModule.forRoot(),
    NgSelectModule,
    CKEditorModule,
    UiSwitchModule,
    TabsModule.forRoot(),
    AngularMultiSelectModule,
    ArticleModule,
    ArticleCommentModule,
    ProductImageModule,
    ProductTagModalModule,
    ProductWarehouseModule,
    ProductRelatedModule,
  ],
  declarations: [
    ProductCreateComponent, ProductEditComponent, ProductImportComponent, ProductTagAssignListComponent, ProductTagAssignCreateComponent
  ],
  entryComponents: [
    ProductCreateComponent, ProductEditComponent, ProductImportComponent, ProductTagAssignListComponent, ProductTagAssignCreateComponent
  ],
  exports: [
    ProductCreateComponent, ProductEditComponent, ProductImportComponent, ProductTagAssignListComponent, ProductTagAssignCreateComponent
  ]
})
export class ProductModalModule {
}
