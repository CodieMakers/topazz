/**
 * @author Lukáš
 * @version 1.0.0
 * @package Topazz
 */

var gulp = require('gulp'),
    sass = require('gulp-sass'),
    autoprefixer = require('gulp-autoprefixer'),
    cssnano = require('gulp-cssnano');

gulp.task('css', function() {
    gulp.src('public/sass/*.scss')
        .pipe(sass())
        .pipe(autoprefixer())
        .pipe(cssnano())
        .pipe(gulp.dest('public/css'));
});

gulp.task('watch', ['css'], function() {
    gulp.watch('public/sass/**', ['css']);
});