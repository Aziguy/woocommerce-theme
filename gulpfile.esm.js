import gulp from 'gulp';
import sass from 'gulp-sass';
import browserSync from 'browser-sync';
import postcss from 'gulp-postcss';
import autoprefixer from 'autoprefixer';
import cssnano from 'cssnano';

// Your plugins array
const plugins = [
    autoprefixer(),
    cssnano()
];



function style() {
    return gulp.src('./assets/scss/**/*.scss')
        .pipe(sass().on('error', sass.logError))
        .pipe(postcss(plugins))
        .pipe(gulp.dest('./assets/css'))
        .pipe(browserSync.stream());
}


function images() {
    // Use dynamic import() for 'gulp-imagemin'
    import('gulp-imagemin').then(imagemin => {
        return gulp.src('./assets/img/**/*')
            .pipe(imagemin.default()) // Use .default() to access the module
            .pipe(gulp.dest('./assets/img'));
    }).catch(error => {
        console.error('Error while loading gulp-imagemin:', error);
    });
}


function watch() {
    browserSync.init({
        proxy: "http://localhost:10034/", // Update to your local domain
        browser: "brave"
    });

    gulp.watch('./assets/scss/**/*.scss', style);
    gulp.watch('./assets/img/**/*', images);
    gulp.watch('./templates/**/*.twig').on('change', browserSync.reload);
}

export { style, images, watch };

