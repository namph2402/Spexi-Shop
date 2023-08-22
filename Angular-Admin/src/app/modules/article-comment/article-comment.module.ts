import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';
import {ArticleCommentListComponent} from './article-comment-list/article-comment-list.component';
import {FormsModule, ReactiveFormsModule} from '@angular/forms';
import {ModalModule, PaginationModule, PopoverModule} from 'ngx-bootstrap';
import {ConfirmationPopoverModule} from 'angular-confirmation-popover';
import {UiSwitchModule} from 'ngx-toggle-switch';
import {CKEditorModule} from 'ng2-ckeditor';
import {AngularMultiSelectModule} from 'angular2-multiselect-dropdown';
import {ArticleCommentModalModule} from './article-comment.modal.module';

@NgModule({
  imports: [
    CommonModule,
    FormsModule,
    PaginationModule.forRoot(),
    ReactiveFormsModule,
    PopoverModule.forRoot(),
    ConfirmationPopoverModule.forRoot(),
    ModalModule.forRoot(),
    UiSwitchModule,
    CKEditorModule,
    AngularMultiSelectModule,
    ArticleCommentModalModule
  ],
  declarations: [ArticleCommentListComponent],
  entryComponents: [ArticleCommentListComponent],
  exports: []
})
export class ArticleCommentModule {
}
