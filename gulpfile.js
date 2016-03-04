var gulp = require('gulp');

/* Copy the node_modules folder from /node_modules to /public/node_modules */
gulp.task('copy-nm', function() {

    var src, dest;

    src = 'node_modules/**/*';
    dest = 'public/';

    gulp.src(src, {base:"."})
        .pipe(gulp.dest(dest));
});

gulp.task('default', ['copy-nm']);

