import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';
import {HeaderComponent} from '../../layout/header/header.component';
import {SidebarComponent} from '../../layout/sidebar/sidebar.component';
import {FooterComponent} from '../../layout/footer/footer.component';
import {RouterModule, Routes} from '@angular/router';
import {HomeComponent} from './home.component';
import {MODULES_ROUTING} from '../../app.routing';
import {FormsModule} from '@angular/forms';
import {AngularMultiSelectModule} from 'angular2-multiselect-dropdown';

const routes: Routes = [
  {
    path: '',
    component: HomeComponent,
    children: MODULES_ROUTING
  }
];

@NgModule({
  imports: [
    CommonModule,
    RouterModule.forChild(routes),
    FormsModule,
    AngularMultiSelectModule,
  ],
  declarations: [
    HeaderComponent,
    FooterComponent,
    SidebarComponent,
    HomeComponent
  ]
})
export class HomeModule {
}
