import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';
import {FormsModule, ReactiveFormsModule} from '@angular/forms';
import {ModalModule} from 'ngx-bootstrap';
import {ArticleCreateComponent} from './article-create/article-create.component';
import {ArticleEditComponent} from './article-edit/article-edit.component';
import {CKEditorModule} from 'ng2-ckeditor';

@NgModule({
  imports: [
    CommonModule,
    FormsModule,
    ReactiveFormsModule,
    ModalModule.forRoot(),
    CKEditorModule,
  ],
  declarations: [ArticleCreateComponent, ArticleEditComponent],
  entryComponents: [ArticleCreateComponent, ArticleEditComponent],
  exports: [ArticleCreateComponent, ArticleEditComponent]
})
export class ArticleModule {
}
