const gulp = require("gulp");
const browserSync = require("browser-sync").create();
const postcss = require("gulp-postcss");
const autoprefixer = require("autoprefixer");
const cssnano = require("cssnano");
const terser = require("gulp-terser");
const imagemin = require("gulp-imagemin");
const imageminJpegtran = require("imagemin-jpegtran");
const sass = require("gulp-sass")(require("sass"));

const plugins = [autoprefixer(), cssnano()];

function style() {
  return gulp
    .src("./assets/styles/**/*.scss")
    .pipe(sass().on("error", sass.logError))
    .pipe(postcss(plugins))
    .pipe(gulp.dest("./assets/dist/css"))
    .pipe(browserSync.stream());
}

function minifyCss() {
  return gulp
    .src("./assets/styles/**/*.css")
    .pipe(postcss([cssnano()]))
    .pipe(gulp.dest("./assets/dist/css"))
    .pipe(browserSync.stream());
}

function scripts() {
  return gulp
    .src("./assets/scripts/**/*.js")
    .pipe(terser())
    .pipe(gulp.dest("./assets/dist/js"))
    .pipe(browserSync.stream());
}

function optimizeImages() {
  return gulp
    .src("./assets/images/*.{jpg,png}")
    .pipe(imagemin([imageminJpegtran()]))
    .pipe(gulp.dest("./assets/dist/images"));
}

function watch() {
  browserSync.init({
    proxy: "http://localhost:10028/",
    open: false,
    browser: "brave",
  });

  gulp.watch("./assets/styles/**/*.scss", style);
  gulp.watch("./assets/styles/**/*.css", minifyCss);
  gulp.watch("./assets/scripts/**/*.js", scripts);
  gulp.watch("./views/**/*.twig").on("change", browserSync.reload);
  gulp.watch("./assets/images/**/*", optimizeImages);
}

exports.style = style;
exports.minifyCss = minifyCss;
exports.scripts = scripts;
exports.optimizeImages = optimizeImages;
exports.watch = watch;
