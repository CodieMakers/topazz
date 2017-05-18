/**
 * @author Lukáš
 * @version 1.0.0
 * @package Topazz
 */

let gulp = require("gulp"),
    sass = require("gulp-sass");

gulp.task('css', function () {
    gulp.src('public/sass/**')
        .pipe(sass())
        .pipe(gulp.dest('public/css'));
});

gulp.task('watch', function () {
    gulp.watch('public/sass/**', ['css']);
});