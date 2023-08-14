import {NgModule} from '@angular/core';
import {GuardService, InterceptorService, ObjectObservableService, TitleService} from '../core/services';
import {HTTP_INTERCEPTORS} from '@angular/common/http';

@NgModule({
  imports: [],
  declarations: [],
  providers: [
    TitleService,
    ObjectObservableService,
    GuardService,
    {
      provide: HTTP_INTERCEPTORS,
      useClass: InterceptorService,
      multi: true,
    },
  ],
  exports: []
})
export class ServicesModule {
}
