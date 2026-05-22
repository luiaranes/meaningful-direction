/*** Import packages
 **********************************************/
const imagemin = require('gulp-imagemin');
const rev = require('gulp-rev');
const del = require('del');
var concat = require('gulp-concat');
var gulp = require('gulp');
const browserSync = require('browser-sync').create();
var jshint = require('gulp-jshint');
var cssnano = require('gulp-cssnano');
var plumber = require('gulp-plumber');
var revDel = require('rev-del');
var sass = require('sass');
var sourcemaps = require('gulp-sourcemaps');
var uglify = require('gulp-uglify-es').default;
var rename = require('gulp-rename');
var minify = require('gulp-minify');
var file = require('gulp-file');

var sass = require('gulp-sass')(require('sass')); // the line has an issue in your original code which is fixed now.

/*** URL
 **********************************************/
var url = {
    local: 'https://meaningful-direction.lndo.site',
    staging: 'assets/images/',
    production: 'assets/fonts/',
};

/*** Source location
 **********************************************/
var source = {
    src: 'assets/',
    images: 'assets/images/',
    fonts: 'assets/fonts/',
    styles: 'assets/styles/',
    scripts: {
        src: 'assets/js/',
        scripts: 'assets/js/app.js',
        googlemaps: 'assets/js/lib/googlemaps.js',
        slick: 'assets/js/lib/slick.min.js',
    },
};

/*** Distributive location
 **********************************************/
var dist = {
    src: 'dist/',
    fonts: 'dist/fonts/',
    images: 'dist/images/',
    styles: 'dist/css/',
    scripts: 'dist/js/',
};

function handleError(err) {
    console.log(err.toString());
    process.exit(-1);
}

/*** Clean destination folder of all the files
 **********************************************/
function clean() {
    gulp.src('manifest.json')
        .pipe(file('manifest.json', ''))
        .pipe(gulp.dest('.'));
    return del([
        dist.styles + '*',
        dist.scripts + '*',
        dist.fonts + '*',
        dist.images + '*',
    ]);
}
exports.clean = clean;

/*** Process SASS files and generate CSS
 **********************************************/
const css = function css() {
    // disable this for the meantime because its not yet using sass
    return gulp
        .src(source.styles + 'app.scss')
        .pipe(plumber())
        .pipe(sourcemaps.init())
        .pipe(sass().on('error', sass.logError))
        .pipe(concat('app.css'))
        .pipe(
            cssnano({
                discardComments: { removeAll: true },
            })
        )
        .pipe(rename('app.min.css'))
        .pipe(sourcemaps.write('/'))
        .pipe(gulp.dest(dist.styles));
};
exports.css = css;

/*** Process Fonts
 **********************************************/
function fonts() {
    return gulp
        .src(source.fonts + '*')
        .pipe(plumber())
        .pipe(gulp.dest(dist.fonts));
}
exports.fonts = fonts;

/*** Process Images
 **********************************************/
function images() {
    return gulp
        .src([source.images + '*', source.images + '*/*.{gif,jpg,png,svg,ico}'])
        .pipe(plumber())
        .pipe(
            imagemin([
                imagemin.jpegtran({ progressive: true }),
                imagemin.gifsicle({ interlaced: true }),
                imagemin.svgo({
                    plugins: [
                        { removeUnknownsAndDefaults: false },
                        { cleanupIDs: false },
                    ],
                }),
            ])
        )
        .pipe(gulp.dest(dist.images));
}
exports.images = images;

/*** Process Vendor JavaScript
 **********************************************/
function vendor() {
    return gulp
        .src(source.scripts.jquery)
        .pipe(plumber())
        .pipe(
            minify({
                ext: {
                    min: '.min.js',
                },
                noSource: 'true',
            })
        )
        .pipe(gulp.dest(dist.scripts));
}
exports.vendor = vendor;

/*** Process App JavaScript
 **********************************************/
const js = function js() {
    return gulp
        .src([
            source.scripts.slick,
            source.scripts.googlemaps,
            source.scripts.scripts,
        ])
        .pipe(plumber())
        .pipe(jshint('.jshintrc'))
        .pipe(jshint.reporter('jshint-stylish'))
        .pipe(jshint.reporter('fail'))
        .on('error', handleError)
        .pipe(concat('app.js'))
        .pipe(uglify())
        .pipe(rename('app.min.js'))
        .pipe(gulp.dest(dist.scripts));
};
exports.js = js;

/*** Watch and Process
 **********************************************/
function watch(done) {
    browserSync.init({
        proxy: url.local,
    });

    gulp.watch('./**/*.php').on('change', browserSync.reload);
    gulp.watch(source.scripts.src + '**', gulp.series('js')).on(
        'change',
        browserSync.reload
    );
    gulp.watch(source.styles + '**', gulp.series('css')).on(
        'change',
        browserSync.reload
    );

    done();
}
exports.watch = watch;

/*** Process And Complete Build
 **********************************************/
const build = gulp.series(
    clean,
    gulp.parallel(fonts, images),
    gulp.parallel(css, js)
);
exports.build = build;
exports.default = build;

/*** Process And Complete Build
 **********************************************/
const buildRev = gulp.series(
    clean,
    gulp.parallel(fonts, images),
    gulp.parallel(css, js)
);
exports.build = buildRev;

/*** Process a released version of css file
 **********************************************/
function releaseCss() {
    return gulp
        .src([dist.styles + '/*.min.css'])
        .pipe(rev())
        .pipe(gulp.dest(dist.styles))
        .pipe(rev.manifest({ path: 'manifest.json', merge: true }))
        .pipe(revDel({ dest: dist.styles }))
        .pipe(gulp.dest('.'));
}
exports.releaseCss = releaseCss;

/*** Process a released version of js files
 **********************************************/
function releaseJs() {
    return gulp
        .src([dist.scripts + '/*.min.js'])
        .pipe(rev())
        .pipe(gulp.dest(dist.scripts))
        .pipe(rev.manifest({ path: 'manifest.json', merge: true }))
        .pipe(revDel({ dest: dist.scripts }))
        .pipe(gulp.dest('.'));
}
exports.releaseJs = releaseJs;

/*** Clean manifest.json
 **********************************************/
function cleanCachebust() {
    return gulp
        .src('manifest.json')
        .pipe(file('manifest.json', ''))
        .pipe(gulp.dest('.'));
}
exports.cleanCachebust = cleanCachebust;

/*** Process a released version of css and js files
 **********************************************/
const release = gulp.series(
    cleanCachebust,
    releaseCss, 
    releaseJs
);
exports.release = release;
