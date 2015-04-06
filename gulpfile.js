'use strict';

var gulp = require('gulp');
var rename = require('gulp-rename');
var uglify = require('gulp-uglify');
var watch = require('gulp-watch');
var concat = require('gulp-concat');
var notify = require('gulp-notify');

var DEST = 'build/';

gulp.task('js', function() {

  gulp.src('./assets/js/app.js')
  .pipe(uglify())
  .pipe(rename({ extname: '.min.js' }))
  .pipe(gulp.dest(DEST));

  gulp.src('./assets/js/vendor/**/*.js')
  .pipe(uglify())
  .pipe(concat("vendor.js"))
  .pipe(gulp.dest('./assets/js'))
  .pipe( notify({ message: "Javascript pipe finished."}) );

});


gulp.task('default', ['js']);
