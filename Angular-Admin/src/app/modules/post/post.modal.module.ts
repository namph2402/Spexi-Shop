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
import { PostRelatedModule } from '../post-related/post-related.module';
import { PostTagAssignListComponent } from './post-tag-assign-list/post-tag-assign-list.component';
import { PostTagAssignCreateComponent } from './post-tag-assign-create/post-tag-assign-create.component';

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
    PostRelatedModule,
  ],
  declarations: [
    PostCreateComponent, PostEditComponent, PostTagAssignListComponent, PostTagAssignCreateComponent
  ],
  entryComponents: [
    PostCreateComponent, PostEditComponent, PostTagAssignListComponent, PostTagAssignCreateComponent
  ],
  exports: [
    PostCreateComponent, PostEditComponent, PostTagAssignListComponent, PostTagAssignCreateComponent
  ]
})
export class PostModalModule {
}
