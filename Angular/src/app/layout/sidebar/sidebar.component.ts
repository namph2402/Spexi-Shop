import {Component, OnInit} from '@angular/core';
import {StorageUtil} from '../../core/utils';
import {APP_FEATURES} from '../../app.features';
import {Router} from '@angular/router';

@Component({
  selector: 'app-sidebar',
  templateUrl: './sidebar.component.html',
  styleUrls: ['./sidebar.component.css'],
  providers: []
})
export class SidebarComponent implements OnInit {

  features: any[];
  name: string = '';
  photoUrl: string;

  constructor(private router: Router) {
    this.features = APP_FEATURES;
  }

  ngOnInit() {
    this.name = StorageUtil.get('name');
    this.photoUrl = StorageUtil.get('photoUrl');
  }

}
