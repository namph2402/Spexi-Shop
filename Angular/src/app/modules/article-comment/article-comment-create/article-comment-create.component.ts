import {Component} from '@angular/core';
import {AbstractModalComponent} from '../../../core/crud';
import {ArticleCommentMeta} from '../article-comment.meta';
import {FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {BsModalRef} from 'ngx-bootstrap';
import {ArticleCommentService} from '../article-comment.service';
import {FieldForm} from '../../../core/common';

@Component({
  selector: 'app-article-comment-create',
  templateUrl: './article-comment-create.component.html',
  styleUrls: ['./article-comment-create.component.css'],
  providers: [ArticleCommentService]
})
export class ArticleCommentCreateComponent extends AbstractModalComponent<ArticleCommentMeta> {

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
      article_id: new FormControl(null),
    });
  }

  initFieldForm(): FieldForm[] {
    return [
      FieldForm.createTextInput('Tên khách hàng', 'author', 'Nhập tên khách hàng'),
      FieldForm.createSelect('Đánh giá', 'rating', 'Chọn một', [
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
      FieldForm.createTextArea('Bình luận', 'content', 'Nhập kí tự', 5),
    ];
  }

  loaded(): void {
    this.formGroup.controls['article_id'].setValue(this.model.article_id);
  }


}
