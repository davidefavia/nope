var gulp = require('gulp');
var livereload = require('gulp-livereload');
var minifycss = require('gulp-minify-css');
var rename = require('gulp-rename');
var sass = require('gulp-sass');

var adminFolder = 'nope/lib/';
var themeFolder = 'nope/theme/default/';

gulp.task('sass', function() {
  gulp.src(adminFolder + 'assets/scss/app.scss')
    .pipe(sass())
    .pipe(minifycss())
    .pipe(rename('app.min.css'))
    .pipe(gulp.dest(adminFolder + 'assets/css'))
    .pipe(livereload());
});

gulp.task('else', function(cb) {
  livereload.reload('index.php');
  cb();
});

gulp.task('watch', function() {
  livereload.listen();
  gulp.watch([
    adminFolder + 'assets/scss/*.scss',
    adminFolder + 'assets/scss/**/*.scss'
  ], ['sass']);
  gulp.watch([
    'index.php',
    'nope/*.*',
    'nope/**/*.*',
    '!nope/**/*.scss',
    '!nope/**/*.css',
    '!nope/lib/vendor/**',
    '!nope/storage/**/*.*'
  ], ['else']);
});
