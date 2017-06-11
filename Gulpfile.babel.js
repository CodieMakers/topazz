/**
 * @author Lukáš
 * @version 1.0.0
 * @package Topazz
 */

import gulp from "gulp";
import sass from "gulp-sass";
import autoprefixer from "gulp-autoprefixer";
import cssnano from "gulp-cssnano";

gulp.task('css', () => {
    gulp.src('public/sass/*.scss')
        .pipe(sass())
        .pipe(autoprefixer())
        .pipe(cssnano())
        .pipe(gulp.dest('public/css'));
});

gulp.task('watch', ['css'], () => {
    gulp.watch('public/sass/**', ['css']);
});