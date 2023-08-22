import {Component, OnInit} from '@angular/core';
import {Router} from '@angular/router';
import {AuthService} from '../../modules/auth';
import {StorageUtil} from '../../core/utils';

@Component({
  selector: 'app-header',
  templateUrl: './header.component.html',
  styleUrls: ['./header.component.css'],
  providers: [AuthService]
})
export class HeaderComponent implements OnInit {

  name: string;
  avatar: string;

  constructor(private router: Router) {
  }

  ngOnInit() {
    this.name = StorageUtil.get('name');
    this.avatar = StorageUtil.get('avatar');
  }

  logout() {
    StorageUtil.clear();
    setTimeout(() => {
      this.router.navigateByUrl('login');
    }, 500);
  }
}
