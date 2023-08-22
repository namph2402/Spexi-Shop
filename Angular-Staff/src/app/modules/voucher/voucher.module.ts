import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';
import {VoucherListComponent} from './voucher-list/voucher-list.component';
import {FormsModule, ReactiveFormsModule} from '@angular/forms';
import {CollapseModule, ModalModule, PaginationModule, PopoverModule} from 'ngx-bootstrap';
import {ConfirmationPopoverModule} from 'angular-confirmation-popover';
import {RouterModule, Routes} from '@angular/router';
import {NgSelectModule} from '@ng-select/ng-select';
import {CKEditorModule} from 'ng2-ckeditor';
import {UiSwitchModule} from 'ngx-toggle-switch';
import {AngularMultiSelectModule} from 'angular2-multiselect-dropdown';
import {VoucherModalModule} from './voucher.modal.module';

const routing: Routes = [
  {
    path: '',
    component: VoucherListComponent
  }
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
    NgSelectModule,
    CKEditorModule,
    UiSwitchModule,
    CollapseModule,
    VoucherModalModule,
  ],
  declarations: [
    VoucherListComponent,
  ],
  entryComponents: [],
  exports: []
})
export class VoucherModule {
}
