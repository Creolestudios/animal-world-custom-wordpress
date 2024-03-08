var gulp = require('gulp');
const less = require('gulp-sass')(require('sass'));
var uglify = require('gulp-uglify');
var rename = require('gulp-rename');
var cleanCSS = require('gulp-clean-css');

var paths = {
  styles: {
      src: './assets/scss/*.scss',
      dest: './dist/assets/css'
  },
};

/*
 * Define our tasks using plain functions
 */
function styles() {
  return gulp.src(paths.styles.src)
  
    .pipe(less())
    .pipe(cleanCSS())
    // pass in options to the stream
    .pipe(rename({
      basename: 'app',
      suffix: '.min'
    }))
    .pipe(gulp.dest(paths.styles.dest));
}

function watch() {
  // gulp.watch("www/js/js**/*.js", scripts);
  gulp.watch("./assets/scss/*.scss", styles);
}

exports.styles = styles;
exports.watch = watch;

var build = gulp.series(gulp.parallel(styles));

gulp.task('build', build);

gulp.task('default', build);