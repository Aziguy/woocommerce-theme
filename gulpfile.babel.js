import gulp from "gulp";
//import sass from "gulp-sass";
import browserSync from "browser-sync";
import postcss from "gulp-postcss";
import autoprefixer from "autoprefixer";
import cssnano from "cssnano";
import terser from "gulp-terser";
const imageminJpegtran = require("imagemin-jpegtran");

// Use `sass` as the Sass compiler (requires 'sass' package)
//sass.compiler = require("sass");
const sass = require("gulp-sass")(require("sass"));

// Our plugins array
const plugins = [autoprefixer(), cssnano()];

function style() {
  return gulp
    .src("./app/scss/**/*.scss")
    .pipe(sass().on("error", sass.logError))
    .pipe(postcss(plugins))
    .pipe(gulp.dest("./app/dist"))
    .pipe(browserSync.stream());
}

function scripts() {
  return (
    gulp
      .src("./assets/js/**/*.js")
      // Process JavaScript files if needed
      .pipe(gulp.dest("./assets/dist/js"))
      .pipe(browserSync.stream())
  );
}

async function optimizeImages() {
  const imagemin = (await import("imagemin")).default;
  const files = await imagemin(["./assets/Img/*.{jpg,png}"], {
    destination: "./assets/img",
    plugins: [imageminJpegtran()],
  });
}

function watch() {
  browserSync.init({
    proxy: "http://localhost:10028/",
    open: false,
    browser: "brave",
  });

  gulp.watch("./assets/scss/**/*.scss", style);
  gulp.watch("./assets/js/**/*.js", scripts);
  gulp.watch("./templates/**/*.twig").on("change", browserSync.reload);
  gulp.watch("./assets/Img/**/*", optimizeImages);
}

export { style, scripts, optimizeImages, watch };
