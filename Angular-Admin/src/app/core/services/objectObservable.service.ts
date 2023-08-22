import {Injectable} from '@angular/core';
import {BehaviorSubject, Observable} from 'rxjs';

export class Ob {
  title: string;
  value: string;
}

@Injectable()
export class ObjectObservableService {

  obSubject: BehaviorSubject<Ob> = new BehaviorSubject<Ob>({title: '', value: ''});
  obSubject$: Observable<Ob> = this.obSubject.asObservable().distinctUntilChanged();

  setOb(ob: Ob) {
    this.obSubject.next(ob);
  }

}
