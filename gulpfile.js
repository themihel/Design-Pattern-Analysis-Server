// Gulp: Dependencies
var gulp = require('gulp');
var del = require('del');
var size = require('gulp-size');
var uglify = require('gulp-uglify');
var uglifycss = require('gulp-uglifycss');

// Watch files
function watchFiles() {
    gulp.watch("./ressources/modifications/**/*.js", copyModificationScripts);
    gulp.watch("./ressources/modifications/**/*.css", copyModificationStyles);
}

// Clean
function clean() {
    return del(["./public/modifications"]);
}

// Modifications
function copyModificationScripts() {
    return gulp
        .src('ressources/modifications/**/*.js')
        .pipe(uglify({mangle: false}))
        .pipe(gulp.dest('public/modifications'))
        .pipe(size({title: 'modScripts'}));
}

function copyModificationStyles() {
    return gulp
        .src('ressources/modifications/**/*.css')
        .pipe(uglifycss())
        .pipe(gulp.dest('public/modifications'))
        .pipe(size({title: 'modStyles'}));
}

// Series tasks
const build = gulp.series(clean, gulp.parallel(copyModificationScripts, copyModificationStyles));
const watch = gulp.parallel(build, watchFiles);

// export tasks
exports.clean = clean;
exports.copyModificationScripts = copyModificationScripts;
exports.copyModificationStyles = copyModificationStyles;
exports.build = build;
exports.watch = watch;