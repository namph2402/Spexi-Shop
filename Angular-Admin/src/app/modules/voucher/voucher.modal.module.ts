import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';
import {VoucherCreateComponent} from './voucher-create/voucher-create.component';
import {VoucherEditComponent} from './voucher-edit/voucher-edit.component';
import {FormsModule, ReactiveFormsModule} from '@angular/forms';
import {CollapseModule, ModalModule, PaginationModule, PopoverModule} from 'ngx-bootstrap';
import {ConfirmationPopoverModule} from 'angular-confirmation-popover';
import {NgSelectModule} from '@ng-select/ng-select';
import {CKEditorModule} from 'ng2-ckeditor';
import {UiSwitchModule} from 'ngx-toggle-switch';
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
    AngularMultiSelectModule,
    NgSelectModule,
    CKEditorModule,
    UiSwitchModule,
    CollapseModule,
  ],
  declarations: [
    VoucherCreateComponent,
    VoucherEditComponent,
  ],
  entryComponents: [
    VoucherCreateComponent,
    VoucherEditComponent,
  ],
  exports: [
    VoucherCreateComponent,
    VoucherEditComponent,
  ]
})
export class VoucherModalModule {
}
