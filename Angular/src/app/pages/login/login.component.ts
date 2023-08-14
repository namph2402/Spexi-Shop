import {Component, OnDestroy, OnInit} from '@angular/core';
import {ToasterService} from 'angular2-toaster';
import {ActivatedRoute, Router} from '@angular/router';
import {FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {StorageUtil} from '../../core/utils';
import {AuthMeta, AuthService} from '../../modules/auth';

declare var $: any;

@Component({
  selector: 'app-login',
  templateUrl: './login.component.html',
  styleUrls: ['./login.component.css'],
  providers: [AuthService]
})
export class LoginComponent implements OnInit, OnDestroy {

  bodyClasses: string[] = ['login-page'];
  body: HTMLBodyElement = document.getElementsByTagName('body')[0];

  formGroup: FormGroup;

  constructor(private route: ActivatedRoute, private router: Router,
              private authService: AuthService, formBuilder: FormBuilder,
              private toast: ToasterService) {
    this.formGroup = formBuilder.group({
      username: new FormControl(null, Validators.required),
      password: new FormControl(null, Validators.required),
    });
    this.check_login();
  }

  ngOnInit() {
    this.bodyClasses.forEach((value: string) => this.body.classList.add(value));
    $.getScript('assets/js/login.js');
  }

  ngOnDestroy() {
    this.bodyClasses.forEach((value: string) => this.body.classList.remove(value));
  }

  login() {
    let user = this.formGroup.value;
    this.authService.store(user).subscribe((res: AuthMeta) => {
      StorageUtil.set('name', res.name);
      StorageUtil.set('avatar', res.avatar);
      StorageUtil.set('token', res.token);
      this.router.navigateByUrl('');
      this.toast.pop('success', 'Đăng nhập', 'Thành công');
    }, () => this.toast.pop('error', 'Đăng nhập', 'Thất bại'));
  }

  check_login() {
    let token = StorageUtil.get('token');
    if (!!token) {
      this.router.navigateByUrl('');
    }
  }

}
