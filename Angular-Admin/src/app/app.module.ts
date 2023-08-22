import {BrowserModule} from '@angular/platform-browser';
import {NgModule} from '@angular/core';
import {AppComponent} from './app.component';
import {RouterModule} from '@angular/router';
import {ToasterModule} from 'angular2-toaster';
import {HttpClientModule} from '@angular/common/http';
import {NgHttpLoaderModule} from 'ng-http-loader/ng-http-loader.module';
import {BrowserAnimationsModule} from '@angular/platform-browser/animations';
import {ServicesModule} from './services';
import {GuardService} from './core/services';
import * as moment from 'moment';
import 'moment/locale/vi';

const routes = [
  {
    path: '',
    redirectTo: 'home',
    pathMatch: 'full'
  },
  {
    path: 'login',
    loadChildren: 'app/pages/login/login.module#LoginModule'
  },
  {
    path: '',
    loadChildren: 'app/pages/home/home.module#HomeModule',
    canActivate: [GuardService],
    canActivateChild: [GuardService]
  },
];

@NgModule({
  declarations: [
    AppComponent,
  ],
  imports: [
    BrowserModule,
    BrowserAnimationsModule,
    ServicesModule,
    ToasterModule.forRoot(),
    RouterModule.forRoot(routes),
    HttpClientModule,
    NgHttpLoaderModule,
  ],
  providers: [],
  bootstrap: [
    AppComponent
  ]
})
export class AppModule {

  constructor() {
    moment().locale('vi');
  }
}
