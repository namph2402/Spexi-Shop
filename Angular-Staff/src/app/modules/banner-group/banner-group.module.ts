import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';
import {BannerGroupListComponent} from './banner-group-list/banner-group-list.component';
import {FormsModule, ReactiveFormsModule} from '@angular/forms';
import {ModalModule, PaginationModule, PopoverModule} from 'ngx-bootstrap';
import {ConfirmationPopoverModule} from 'angular-confirmation-popover';
import {RouterModule, Routes} from '@angular/router';
import {NgSelectModule} from '@ng-select/ng-select';
import {BannerModule} from '../banner/banner.module';
import {AngularMultiSelectModule} from 'angular2-multiselect-dropdown';
import {CKEditorModule} from 'ng2-ckeditor';
import {UiSwitchModule} from 'ngx-toggle-switch';

const routing: Routes = [
  {
    path: '',
    component: BannerGroupListComponent
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
    NgSelectModule,
    CKEditorModule,
    UiSwitchModule,
    BannerModule,
    AngularMultiSelectModule,
  ],
  declarations: [BannerGroupListComponent],
  entryComponents: []
})
export class BannerGroupModule {
}
