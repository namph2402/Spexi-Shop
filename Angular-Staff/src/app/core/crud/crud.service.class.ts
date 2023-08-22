import {Observable} from 'rxjs/Rx';
import {HttpClient, HttpErrorResponse, HttpHeaders, HttpParams} from '@angular/common/http';
import 'rxjs/add/operator/map';
import {ToasterService} from 'angular2-toaster';
import {TitleService} from '../services';
import {catchError, map} from 'rxjs/operators';
import {DataResponse, PaginationOutput} from '../common';
import {getBaseHref, pathJoin, StorageUtil} from '../utils';

export class AbstractCRUDService<T> {

  constructor(public http: HttpClient, public title: TitleService, public toast: ToasterService, public titlePopup: string, public urlRestAPI: string) {
  }

  setApiUrl(newPrefixUrl: string) {
    if (newPrefixUrl) {
      this.urlRestAPI = pathJoin([newPrefixUrl, this.urlRestAPI]);
    }
  }

  setURLRestAPI(newURL: string): void {
    if (newURL) {
      this.urlRestAPI = newURL;
    }
  }

  toPipe(thread: Observable<DataResponse<any>>) {
    return thread.pipe(catchError(this.handleErrorRequest.bind(this)), map(res => res['data']));
  }

  loadAll(): Observable<T[]> {
    return this.http.get<DataResponse<T[]>>(this.urlRestAPI)
      .pipe(catchError(this.handleErrorRequest.bind(this)), map(res => res['data']));
  }

  loadByPage(params: any): Observable<PaginationOutput<T>> {
    let parameters: HttpParams = new HttpParams({
      fromObject: params
    });
    return this.http.get<DataResponse<PaginationOutput<T>>>(this.urlRestAPI, {params: parameters})
      .pipe(catchError(this.handleErrorRequest.bind(this)), map(res => res['data']));
  }

  loadByID(id: number): Observable<T> {
    return this.http.get<DataResponse<T>>(`${this.urlRestAPI}/${id}`)
      .pipe(catchError(this.handleErrorRequest.bind(this)), map(res => res['data']));
  }

  loadByParams(params: any): Observable<T[]> {
    let parameters: HttpParams = new HttpParams({
      fromObject: params
    });
    return this.http.get<DataResponse<T[]>>(this.urlRestAPI, {params: parameters})
      .pipe(catchError(this.handleErrorRequest.bind(this)), map(res => res['data']));
  }

  findByParams(params: any): Observable<T> {
    let parameters: HttpParams = new HttpParams({
      fromObject: params
    });
    return this.http.get<DataResponse<T>>(this.urlRestAPI, {params: parameters})
      .pipe(catchError(this.handleErrorRequest.bind(this)), map(res => res['data']));
  }

  store(object: T): Observable<T> {
    return this.http.post<DataResponse<T>>(this.urlRestAPI, object)
      .pipe(catchError(this.handleErrorRequest.bind(this)), map(res => res['data']));
  }

  update(object: T): Observable<T> {
    return this.http.put<DataResponse<T>>(`${this.urlRestAPI}/${object['id']}`, object)
      .pipe(catchError(this.handleErrorRequest.bind(this)), map(res => res['data']));
  }

  storeWithImage(item: T): Observable<any> {
    const formData = new FormData();
    Object.keys(item).forEach(key => formData.append(key, item[key]));
    return this.http.post<DataResponse<T>>(this.urlRestAPI, formData, {headers: new HttpHeaders({uploadFile: 'true'})})
      .pipe(catchError(this.handleErrorRequest.bind(this)), map(res => res['data']));
  }

  updateWithImage(item: T): Observable<any> {
    const formData = new FormData();
    Object.keys(item).forEach(key => formData.append(key, item[key]));
    return this.http.post<DataResponse<T>>(`${this.urlRestAPI}/${item['id']}`, formData, {headers: new HttpHeaders({uploadFile: 'true'})})
      .pipe(catchError(this.handleErrorRequest.bind(this)), map(res => res['data']));
  }

  uploadWithFile(enpoint: string, params: any, file: any): Observable<any> {
    const formData = new FormData();
    Object.keys(params).forEach(key => formData.append(key, params[key]));
    formData.append('file', file);
    return this.http.post<DataResponse<any>>(`${this.urlRestAPI}/${enpoint}`, formData, {headers: new HttpHeaders({uploadFile: 'true'})})
      .pipe(catchError(this.handleErrorRequest.bind(this)), map(res => res['data']));
  }

  destroy(id: number): Observable<T> {
    return this.http.delete<DataResponse<T>>(`${this.urlRestAPI}/${id}`)
      .pipe(catchError(this.handleErrorRequest.bind(this)), map(res => res['data']));
  }

  destroyWithParams(id: number, params: any): Observable<T> {
    return this.http.delete<DataResponse<T>>(`${this.urlRestAPI}/${id}`, {params: params})
      .pipe(catchError(this.handleErrorRequest.bind(this)), map(res => res['data']));
  }

  enable(id: number): Observable<T> {
    return this.http.post<DataResponse<T>>(`${this.urlRestAPI}/${id}/enable`, {})
      .pipe(catchError(this.handleErrorRequest.bind(this)), map(res => res['data']));
  }

  disable(id: number): Observable<T> {
    return this.http.post<DataResponse<T>>(`${this.urlRestAPI}/${id}/disable`, {})
      .pipe(catchError(this.handleErrorRequest.bind(this)), map(res => res['data']));
  }

  up(id: number): Observable<T> {
    return this.http.post<DataResponse<T>>(`${this.urlRestAPI}/${id}/up`, {})
      .pipe(catchError(this.handleErrorRequest.bind(this)), map(res => res['data']));
  }

  down(id: number): Observable<T> {
    return this.http.post<DataResponse<T>>(`${this.urlRestAPI}/${id}/down`, {})
      .pipe(catchError(this.handleErrorRequest.bind(this)), map(res => res['data']));
  }

  approve(id: number): Observable<T> {
    return this.http.post<DataResponse<T>>(`${this.urlRestAPI}/${id}/approve`, {})
      .pipe(catchError(this.handleErrorRequest.bind(this)), map(res => res['data']));
  }

  disapprove(id: number): Observable<T> {
    return this.http.post<DataResponse<T>>(`${this.urlRestAPI}/${id}/disapprove`, {})
      .pipe(catchError(this.handleErrorRequest.bind(this)), map(res => res['data']));
  }

  active(id: number) {
    return this.http.post<DataResponse<T>>(`${this.urlRestAPI}/${id}/active`, {})
      .pipe(catchError(this.handleErrorRequest.bind(this)), map(res => res['data']));
  }

  inactive(id: number) {
    return this.http.post<DataResponse<T>>(`${this.urlRestAPI}/${id}/inactive`, {})
      .pipe(catchError(this.handleErrorRequest.bind(this)), map(res => res['data']));
  }

  callByAction(id: number, action: string) {
    return this.http.post<DataResponse<T>>(`${this.urlRestAPI}/${id}/${action}`, {})
      .pipe(catchError(this.handleErrorRequest.bind(this)), map(res => res['data']));
  }

  toastSuccessfullyCreated() {
    this.toast.pop('success', `Thêm ${this.titlePopup}`, 'Thành công');
  }

  toastSuccessfullyEdited() {
    this.toast.pop('success', `Sửa ${this.titlePopup}`, 'Thành công');
  }

  toastSuccessfullyDeleted() {
    this.toast.pop('success', `Xóa ${this.titlePopup}`, 'Thành công');
  }


  toastSuccessfully(action: string, content?: string) {
    this.toast.pop('success', `${action} ${this.titlePopup}`, content ? content : 'Thành công');
  }

  toastFailedCreated() {
    this.toast.pop('error', `Thêm ${this.titlePopup}`, 'Thất bại');
  }

  toastFailedEdited() {
    this.toast.pop('error', `Sửa ${this.titlePopup}`, 'Thất bại');
  }

  toastFailedDeleted() {
    this.toast.pop('error', `Xóa ${this.titlePopup}`, 'Thất bại');
  }

  toastFailed(action: string, content?: string) {
    this.toast.pop('error', `${action} ${this.titlePopup}`, content ? content : 'Thất bại');
  }

  setTitle(title: string) {
    this.title.setTitle(title);
  }

  toastError(title: string, content?: string) {
    this.toast.pop('error', `${title}`, content ? content : 'Thất bại');
  }

  toastSuccess(title: string, content?: string) {
    this.toast.pop('success', `${title}`, content ? content : 'Thành công');
  }

  toastWarning(action: string, content?: string) {
    this.toast.pop('warning', `${action}`, content ? content : 'Cảnh báo');
  }

  handleErrorRequest(err: HttpErrorResponse) {
    if (err.error instanceof Error) {
      this.toastError('Client phản hồi', 'Lỗi xảy ra');
    } else {
      if (err.status == 401) {
        StorageUtil.clear();
        window.location.href = getBaseHref() + 'login';
      } else {
        if (err.status >= 400 && err.status < 500) {
          this.toastError(`${err.error['message']}`);
        } else {
          if (err.status >= 500) {
            this.toastError(`Lỗi xảy ra trên hệ thống`);
          } else {
            this.toastError(`Không xác định lỗi`);
          }
        }
      }
    }
    return Observable.empty<any>();
  }

  import(file: any, body: any) {
    const formData = new FormData();
    Object.keys(body).forEach(key => formData.append(key, body[key]));
    formData.append('file', file);
    return this.http.post(`${this.urlRestAPI}/import`, formData, {
      headers: new HttpHeaders({uploadFile: 'true'}),
      responseType: 'blob',
      observe: 'response',
    }).pipe(
      catchError(err => this.handleErrorRequest(err))
    );
  }

  export(body: any) {
    return this.http.get(`${this.urlRestAPI}/export`, {
      responseType: 'blob',
      observe: 'response',
      params: body
    })
      .pipe(
        catchError(err => this.handleErrorRequest(err))
      );
  }


}
