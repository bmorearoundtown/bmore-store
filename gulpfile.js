'use strict';

var gulp = require('gulp');
var rename = require('gulp-rename');
var uglify = require('gulp-uglify');
var watch = require('gulp-watch');
var concat = require('gulp-concat');
var notify = require('gulp-notify');

var DEST = 'target/';

gulp.task('js', function() {

  gulp.src('./assets/js/app.js')
  .pipe(uglify())
  .pipe(rename({ extname: '.min.js' }))
  .pipe(gulp.dest(DEST));

  gulp.src('./assets/js/vendor/**/*.js')
  .pipe(uglify())
  .pipe(concat("vendor.min.js"))
  .pipe(gulp.dest(DEST))
  .pipe( notify({ message: "Javascript pipe finished."}) );

});


gulp.task('default', ['js']);
