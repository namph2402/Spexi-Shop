import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';
import {PromotionListComponent} from './promotion-list/promotion-list.component';
import {FormsModule, ReactiveFormsModule} from '@angular/forms';
import {CollapseModule, ModalModule, PaginationModule, PopoverModule} from 'ngx-bootstrap';
import {ConfirmationPopoverModule} from 'angular-confirmation-popover';
import {RouterModule, Routes} from '@angular/router';
import {NgSelectModule} from '@ng-select/ng-select';
import {CKEditorModule} from 'ng2-ckeditor';
import {ArticleModule} from '../article/article.module';
import {UiSwitchModule} from 'ngx-toggle-switch';
import {ArticleCommentModule} from '../article-comment/article-comment.module';
import {AngularMultiSelectModule} from 'angular2-multiselect-dropdown';
import {PromotionModalModule} from './promotion.modal.module';
import {PromotionProductModalModule} from '../promotion-product/promotion-product.modal.module';

const routing: Routes = [
  {
    path: '',
    component: PromotionListComponent
  },
];

@NgModule({
  imports: [
    CommonModule,
    FormsModule,
    PaginationModule.forRoot(),
    ReactiveFormsModule,
    PopoverModule.forRoot(),
    ConfirmationPopoverModule.forRoot(),
    RouterModule.forChild(routing),
    ModalModule.forRoot(),
    AngularMultiSelectModule,
    ArticleModule,
    ArticleCommentModule,
    NgSelectModule,
    CKEditorModule,
    UiSwitchModule,
    CollapseModule,
    PromotionModalModule,
    PromotionProductModalModule,
  ],
  declarations: [PromotionListComponent],
  entryComponents: [],
  exports: []
})
export class PromotionModule {
}
