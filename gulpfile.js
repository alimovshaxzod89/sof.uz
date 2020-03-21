/*
 * @link      http://www.activemedia.uz/
 * @copyright Copyright (c) 2017. ActiveMedia Solutions LLC
 * @author    Rustam Mamadaminov <rmamdaminov@gmail.com>
 */
var gulp = require('gulp');
var concat = require('gulp-concat');
var uglify = require('gulp-uglify');
var rename = require('gulp-rename');
var minifyCSS = require('gulp-minify-css');

gulp.task('js', function () {
    return gulp.src([
        './frontend/assets/app/js/imagesloaded.min.js',
        './frontend/assets/app/js/masonry.min.js',
        './frontend/assets/app/js/theia-sticky-sidebar.min.js',
        './frontend/assets/app/js/magsy.min.js',
        './frontend/assets/app/js/scripts.js',
    ])
        .pipe(concat('app.js'))
        .pipe(gulp.dest('./frontend/assets/app/js'))
        .pipe(uglify())
        .pipe(rename('app.min.js'))
        .pipe(gulp.dest('./frontend/assets/app/js'));
});

gulp.task('css', function () {
    return gulp.src([
        './frontend/assets/app/css/block-library.css',
        './frontend/assets/app/css/main.css',
        './frontend/assets/app/css/style.css'
    ])
        .pipe(concat('app.css'))
        .pipe(gulp.dest('./frontend/assets/app/css'))
        .pipe(minifyCSS())
        .pipe(rename('app.min.css'))
        .pipe(gulp.dest('./frontend/assets/app/css'));
});

gulp.task('build', ['js', 'css']);

gulp.task('watch', function () {
    gulp.watch('./frontend/assets/**/**/*.css', ['css']);
    gulp.watch('./frontend/assets/**/**/*.js', ['js']);
});

gulp.task('default', ['watch']);
