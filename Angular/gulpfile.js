var gulp = require('gulp');
var rename = require('gulp-rename');
var replace = require('gulp-replace');
var argv = require('yargs').argv;
var fs = require('fs');
var path = require('path');

function toTitleCase(str) {
  return str.toLowerCase().replace(/(?:^|[\s-/])\w/g, function (match) {
    return match.toUpperCase();
  }).replace(/[\s-/]+/g, '');
}

var basePath = argv.basePath;
if (!basePath) {
  basePath = './src/app/modules';
}

var destPath = argv.destPath;
if (!destPath) {
  destPath = '/';
}

var sourcePath = argv.sourcePath;
if (!sourcePath) {
  if (destPath && destPath !== '/') {
    sourcePath = 'template';
  } else {
    sourcePath = '/';
  }
}

var sourceModule = 'user';

var destinationModule = argv.destinationModule;
if (!destinationModule) {
  console.log('Error: destinationModule is require');
  return;
}

var sourceModel = 'User';

var destinationModel = argv.destinationModel;
if (!destinationModel) {
  destinationModel = toTitleCase(destinationModule);
}

var sourceFolderPath = path.join(__dirname, basePath, sourcePath, sourceModule);
console.log(sourceFolderPath);
var destinationFolderPath = path.join(__dirname, basePath, destPath, destinationModule);
console.log(destinationFolderPath);


gulp.task('copyModule', function () {

  return gulp.src(path.join(sourceFolderPath, '/**/*'))
    .pipe(rename(function (file) {
      if (file.basename.startsWith(sourceModule)) {
        file.basename = file.basename.replace(sourceModule, destinationModule)
      }
      if (file.dirname.startsWith(sourceModule)) {
        file.dirname = file.dirname.replace(sourceModule, destinationModule)
      }
    }))
    .pipe(replace(sourceModule, destinationModule))
    .pipe(replace(sourceModel, destinationModel))
    .pipe(gulp.dest(path.join(destinationFolderPath)));
});


// run command to copy module
// gulp copyModule --basePath=./src/app/layout/modules --sourcePath=admin --sourceModule=user --sourceModel=User --destinationPath=admin --destinationModule=job-type --destinationModel=JobType

