import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';
import {BannerGroupCreateComponent} from './banner-group-create/banner-group-create.component';
import {BannerGroupEditComponent} from './banner-group-edit/banner-group-edit.component';
import {FormsModule, ReactiveFormsModule} from '@angular/forms';
import {ModalModule, PaginationModule, PopoverModule} from 'ngx-bootstrap';
import {ConfirmationPopoverModule} from 'angular-confirmation-popover';
import {NgSelectModule} from '@ng-select/ng-select';
import {BannerModule} from '../banner/banner.module';
import {AngularMultiSelectModule} from 'angular2-multiselect-dropdown';
import {CKEditorModule} from 'ng2-ckeditor';

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
    BannerModule,
    AngularMultiSelectModule,
    CKEditorModule
  ],
  declarations: [BannerGroupCreateComponent, BannerGroupEditComponent],
  entryComponents: [BannerGroupCreateComponent, BannerGroupEditComponent],
  exports: [BannerGroupCreateComponent, BannerGroupEditComponent],
})
export class BannerGroupModalModule {
}
