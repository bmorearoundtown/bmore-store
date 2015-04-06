'use strict';

var gulp = require('gulp');
var uglify = require('gulp-uglify');
var minifycss = require('gulp-minify-css');
var concat = require('gulp-concat');
var notify = require('gulp-notify');
var rename = require('gulp-rename');
var watch = require('gulp-watch');

var DEST = 'target/';

// CSS Minification and concatenation
gulp.task('css', function(){

  gulp.src('./assets/css/*.css')
  .pipe(minifycss())
  .pipe(concat("bmorearoundtown.min.css"))
  .pipe(gulp.dest(DEST))
  .pipe(notify({message: "CSS pipe finished and placed in " + DEST}));

});

// JS Uglification and concatenation of vendor files
gulp.task('js', function() {

  gulp.src('./assets/js/bmorearoundtown.js')
  .pipe(uglify())
  .pipe(rename({ extname: '.min.js' }))
  .pipe(gulp.dest(DEST));

  gulp.src('./assets/js/vendor/**/*.js')
  .pipe(uglify())
  .pipe(concat("vendor.min.js"))
  .pipe(gulp.dest(DEST))
  .pipe(notify({message: "Javascript pipe finished and placed in " + DEST}));

});

gulp.task('default', ['css', 'js']);
