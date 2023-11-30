import {Component, OnDestroy, OnInit} from '@angular/core';
import {TitleService} from '../../core/services';
import {AuthService} from '../../modules/auth';

declare var $: any;

@Component({
  selector: 'app-home',
  templateUrl: './home.component.html',
  styleUrls: ['./home.component.css'],
  providers: [AuthService]
})
export class HomeComponent implements OnInit, OnDestroy {

  bodyClasses: string[] = ['skin-yellow-light', 'sidebar-mini', 'sidebar-collapse'];
  body: HTMLBodyElement = document.getElementsByTagName('body')[0];
  title: string;
  sub: any;

  constructor(private titleService: TitleService) {
    this.title = 'Welcome to our system';

  }

  ngOnInit() {
    this.bodyClasses.forEach((value: string) => this.body.classList.add(value));
    this.sub = this.titleService.titleSubject$.subscribe((newTitle: string) => this.title = newTitle);
  }

  ngOnDestroy() {
    this.bodyClasses.forEach((value: string) => this.body.classList.remove(value));
    this.sub.unsubscribe();
  }

}
