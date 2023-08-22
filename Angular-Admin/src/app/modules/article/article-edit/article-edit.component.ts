import {Component} from '@angular/core';
import {AbstractModalComponent} from '../../../core/crud';
import {FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {BsModalRef} from 'ngx-bootstrap';
import {ArticleService} from '../article.service';
import {ArticleMeta} from '../article.meta';
import {FieldForm} from '../../../core/common';

@Component({
  selector: 'app-article-edit',
  templateUrl: './article-edit.component.html',
  styleUrls: ['./article-edit.component.css'],
  providers: [ArticleService]
})
export class ArticleEditComponent extends AbstractModalComponent<ArticleMeta> {

  constructor(
    service: ArticleService,
    modal: BsModalRef,
    builder: FormBuilder
  ) {
    super(service, modal, builder);
  }

  onInit(): void {
  }

  onDestroy(): void {
  }

  buildForm(): FormGroup {
    return this.formBuilder.group({
      content: new FormControl(null, Validators.required),
      articleable_type: new FormControl(null, Validators.required),
      articleable_id: new FormControl(null, Validators.required),
    });
  }

  initFieldForm(): FieldForm[] {
    return [
      FieldForm.createHtmlInput('Nội dung', 'content', 'Nhập ký tự'),
    ];
  }

  loaded(): void {
  }

}
