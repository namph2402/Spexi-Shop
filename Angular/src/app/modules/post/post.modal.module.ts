import {NgModule} from '@angular/core';
import {ModalModule, PaginationModule, PopoverModule, TabsModule} from 'ngx-bootstrap';
import {CommonModule} from '@angular/common';
import {FormsModule, ReactiveFormsModule} from '@angular/forms';
import {ConfirmationPopoverModule} from 'angular-confirmation-popover';
import {AngularMultiSelectModule} from 'angular2-multiselect-dropdown';
import {CKEditorModule} from 'ng2-ckeditor';
import {PostCreateComponent} from './post-create/post-create.component';
import {PostEditComponent} from './post-edit/post-edit.component';
import {NgSelectModule} from '@ng-select/ng-select';
import {UiSwitchModule} from 'ngx-toggle-switch';
import { ArticleCommentModule } from '../article-comment/article-comment.module';
import { PostTagModalModule } from '../post-tag/post-tag.modal.module';
import { PostRelatedModule } from '../post-related/post-related.module';

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
    ArticleCommentModule,
    PostTagModalModule,
    PostRelatedModule,
  ],
  declarations: [
    PostCreateComponent, PostEditComponent
  ],
  entryComponents: [
    PostCreateComponent, PostEditComponent
  ],
  exports: [
    PostCreateComponent, PostEditComponent
  ]
})
export class PostModalModule {
}
