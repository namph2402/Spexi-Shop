import {Injectable} from '@angular/core';
import {HttpEvent, HttpHandler, HttpHeaders, HttpInterceptor, HttpRequest} from '@angular/common/http';
import {Observable} from 'rxjs';
import {StorageUtil} from '../utils';
import {environment} from '../../../environments/environment';

@Injectable()
export class InterceptorService implements HttpInterceptor {
  constructor() {
  }

  intercept(req: HttpRequest<any>, next: HttpHandler): Observable<HttpEvent<any>> {
    let headers: HttpHeaders = req.headers;
    if (!headers.has('Content-Type')) {
      headers = req.headers.set('Content-Type', 'application/json');
    }
    if (headers.has('uploadFile')) {
      headers = headers.delete('Content-Type').delete('uploadFile');
    }
    let token = StorageUtil.get('token');
    if (token) {
      headers = headers.set('Authorization', 'Bearer ' + token);
    }

    let url = req.url;

    if (!url.startsWith('http')) {
      url = `${environment.api_url}/${req.url}`;
    }

    const authReq = req.clone({headers: headers, url: url});
    return next.handle(authReq);
  }

}
