var gulp = require('gulp');
var less = require('gulp-less');
var livereload = require('gulp-livereload');
var minifycss = require('gulp-minify-css');
var rename = require('gulp-rename');

var adminFolder = 'nope/admin/';

gulp.task('less', function() {
  gulp.src(adminFolder + 'assets/less/app.less')
    .pipe(less())
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
    adminFolder + 'assets/less/*.less',
    adminFolder + 'assets/less/**/*.less'
  ], ['less']);
  gulp.watch([
    'nope/*.*',
    'nope/**/*.*',
    'nope/*.*',
    '!nope/**/*.less',
    '!nope/**/*.css',
    '!nope/vendor/**'
  ], ['else']);
});
