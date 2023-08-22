import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';
import {ArticleCommentCreateComponent} from './article-comment-create/article-comment-create.component';
import {ArticleCommentEditComponent} from './article-comment-edit/article-comment-edit.component';
import {FormsModule, ReactiveFormsModule} from '@angular/forms';
import {ModalModule, PaginationModule, PopoverModule} from 'ngx-bootstrap';
import {ConfirmationPopoverModule} from 'angular-confirmation-popover';
import {UiSwitchModule} from 'ngx-toggle-switch';
import {CKEditorModule} from 'ng2-ckeditor';
import {AngularMultiSelectModule} from 'angular2-multiselect-dropdown';

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
  ],
  declarations: [ArticleCommentCreateComponent, ArticleCommentEditComponent],
  entryComponents: [ArticleCommentCreateComponent, ArticleCommentEditComponent],
  exports: [ArticleCommentCreateComponent, ArticleCommentEditComponent]
})
export class ArticleCommentModalModule {
}
