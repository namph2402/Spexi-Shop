import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';
import {FormsModule, ReactiveFormsModule} from '@angular/forms';
import {ModalModule, PaginationModule, PopoverModule} from 'ngx-bootstrap';
import {ConfirmationPopoverModule} from 'angular-confirmation-popover';
import {UiSwitchModule} from 'ngx-toggle-switch';
import {AngularMultiSelectModule} from 'angular2-multiselect-dropdown';
import {CKEditorModule} from 'ng2-ckeditor';
import {PostRelatedModalModule} from './post-related.modal.module';
import {PostRelatedListComponent} from './post-related-list/post-related-list.component';

@NgModule({
  imports: [
    CommonModule,
    FormsModule,
    ReactiveFormsModule,
    PaginationModule.forRoot(),
    PopoverModule.forRoot(),
    ModalModule.forRoot(),
    ConfirmationPopoverModule.forRoot(),
    UiSwitchModule,
    AngularMultiSelectModule,
    CKEditorModule,
    PostRelatedModalModule
  ],
  declarations: [PostRelatedListComponent],
  entryComponents: [PostRelatedListComponent],
  exports: [PostRelatedListComponent]
})
export class PostRelatedModule {
}
