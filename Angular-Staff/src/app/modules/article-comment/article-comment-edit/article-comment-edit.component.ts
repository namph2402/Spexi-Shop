import {Component} from '@angular/core';
import {AbstractModalComponent} from '../../../core/crud';
import {FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {BsModalRef} from 'ngx-bootstrap';
import {ArticleCommentService} from '../article-comment.service';
import {ArticleCommentMeta} from '../article-comment.meta';
import {FieldForm} from '../../../core/common';

@Component({
  selector: 'app-article-comment-edit',
  templateUrl: './article-comment-edit.component.html',
  styleUrls: ['./article-comment-edit.component.css'],
  providers: [ArticleCommentService]
})
export class ArticleCommentEditComponent extends AbstractModalComponent<ArticleCommentMeta> {

  constructor(
    service: ArticleCommentService,
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
      author: new FormControl(null, [Validators.required, Validators.maxLength(255), Validators.pattern('[^ ].*$')]),
      content: new FormControl(null, [Validators.required, Validators.maxLength(255), Validators.pattern('[^ ].*$')]),
      rating: new FormControl(null, Validators.required),
    });
  }

  initFieldForm(): FieldForm[] {
    return [
      FieldForm.createTextInput('Tên khách hàng', 'author', 'Nhập tên khách hàng'),
      FieldForm.createSelect('Đánh giá', 'rating', 'Chộn một', [
        {
          name: 1,
          value: 1
        },
        {
          name: 2,
          value: 2
        },
        {
          name: 3,
          value: 3
        },
        {
          name: 4,
          value: 4
        },
        {
          name: 5,
          value: 5
        }
      ]),
      FieldForm.createTextArea('Bình luận', 'content', 'Nhập nội dung', 5),
    ];
  }

  loaded(): void {
  }

}
