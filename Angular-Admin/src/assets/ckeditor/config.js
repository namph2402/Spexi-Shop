/**
 * @license Copyright (c) 2003-2020, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see https://ckeditor.com/legal/ckeditor-oss-license
 */

CKEDITOR.editorConfig = function (config) {
  // Define changes to default configuration here. For example:
  // config.language = 'fr';
  // config.uiColor = '#AADC6E';

  config.skin = 'moono-lisa';

  config.language = 'vi';
  config.htmlEncodeOutput = false;
  config.entities_latin = false;
  config.height = 500;

  config.removePlugins = 'easyimage,image,cloudservices';
  config.extraPlugins = "toc,base64image,image2,codeTag";

  config.removeButtons = 'Image';

  config.contentsCss = [
    CKEDITOR.getUrl('contents.css'),
    'https://fonts.googleapis.com/css?family=Roboto:100,200,400,300,500,600,700'
  ];

  config.allowedContent = true;
  config.removeFormatAttributes = '';
  config.fillEmptyBlocks = false;
  config.basicEntities = false;
};
