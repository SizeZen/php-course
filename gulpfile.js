var gulp = require('gulp');
var concat = require('gulp-concat');

gulp.task('default', ['concatCss', 'replaceJs', 'replaceFonts']);

gulp.task('concatCss', function () {
  return gulp.src('bower_components/bootstrap/dist/css/bootstrap.css')
    .pipe(concat('style.css'))
    .pipe(gulp.dest('public/static'));
});

gulp.task('replaceJs', function () {
  return gulp.src(['bower_components/bootstrap/dist/js/bootstrap.js', 'bower_components/jquery/dist/jquery.js'])
    .pipe(gulp.dest('public/static/js'));
});
gulp.task('replaceFonts', function () {
  return gulp.src('bower_components/bootstrap/dist/fonts/*')
    .pipe(gulp.dest('public/fonts'));
});