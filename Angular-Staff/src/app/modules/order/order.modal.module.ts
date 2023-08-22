import {NgModule} from '@angular/core';
import {CollapseModule, ModalModule, PaginationModule, PopoverModule, TypeaheadModule} from 'ngx-bootstrap';
import {CommonModule} from '@angular/common';
import {FormsModule, ReactiveFormsModule} from '@angular/forms';
import {ConfirmationPopoverModule} from 'angular-confirmation-popover';
import {AngularMultiSelectModule} from 'angular2-multiselect-dropdown';
import {MomentModule} from 'ngx-moment';
import {OrderCreateComponent} from './order-create/order-create.component';
import {OrderEditComponent} from './order-edit/order-edit.component';
import { OrderNoteComponent } from './order-note/order-note.component';
import { OrderConfirmComponent } from './order-confirm/order-confirm.component';

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
    MomentModule,
    CollapseModule,
    TypeaheadModule.forRoot(),
  ],
  declarations: [
    OrderCreateComponent,
    OrderEditComponent,
    OrderNoteComponent,
    OrderConfirmComponent
  ],
  entryComponents: [
    OrderCreateComponent,
    OrderEditComponent,
    OrderNoteComponent,
    OrderConfirmComponent
  ],
  exports: []
})
export class OrderModalModule {
}
