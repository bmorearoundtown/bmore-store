'use strict';

var gulp = require('gulp');
var uglify = require('gulp-uglify');
var minifycss = require('gulp-minify-css');
var concat = require('gulp-concat');
var notify = require('gulp-notify');
var rename = require('gulp-rename');
var watch = require('gulp-watch');
var imagemin = require('gulp-imagemin');
var pngcrush = require('imagemin-pngcrush');
var del = require('del');
var DEST = 'target/';

var version = "?v=1"

// Clean up
gulp.task('clean', function(cb) {
  del(['target/'], cb)
});

// CSS Minification and concatenation
gulp.task('css', function(){

  gulp.src('./assets/css/*.css')
  .pipe(minifycss({keepBreaks:true}))
  .pipe(concat("bmorearoundtown.min.css" + version))
  .pipe(gulp.dest(DEST))
  .pipe(notify({message: "CSS pipe finished and placed in " + DEST}));

});

// Image Compression
// TODO: Debug child process errors
gulp.task('images', function() {

  var formats = ['./assets/images/**/*.png', './assets/images/**/*.jpg', './assets/images/**/*.svg'];
  var options = {
    progressive: true,
    svgoPlugins: [{removeViewBox: false}],
    use: [pngcrush()],
    optimizationLevel: 7
  };

  gulp.src( formats )
  .pipe(imagemin( options ))
  .pipe(gulp.dest(DEST + '/images/'))
  .pipe(notify({message: 'Images pipe finished and placed in ' + DEST + '/images/'}));

});

// JS Uglification and concatenation of vendor files
gulp.task('js', function() {

  gulp.src('./assets/js/bmorearoundtown.js')
  .pipe(uglify())
  .pipe(rename({ extname: '.min.js' + version }))
  .pipe(gulp.dest(DEST));

  gulp.src('./assets/js/vendor/**/*.js')
  .pipe(uglify())
  .pipe(concat("vendor.min.js" + version))
  .pipe(gulp.dest(DEST))
  .pipe(notify({message: "Javascript pipe finished and placed in " + DEST}));

});

gulp.task('default', ['clean'], function() {

  gulp.start('css', 'js');

});
