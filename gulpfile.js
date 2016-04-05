var gulp = require('gulp');
var concat = require('gulp-concat');

gulp.task('default', function () {
  return gulp.src('bower_components/bootstrap/dist/css/bootstrap.css')
    .pipe(concat('style.css'))
    .pipe(gulp.dest('public/static'));
});