
var browserSync  = require('browser-sync');
var reload       = browserSync.reload;
var gulp = require("gulp");
var concat       = require('gulp-concat');
var uglify       = require('gulp-uglify');
var sass = require("gulp-sass");
var plumber = require("gulp-plumber");
var watch        = require('gulp-watch');
var pleeease = require('gulp-pleeease');
var ejs = require("gulp-ejs");


gulp.task("ejs", function() {
  gulp.src(
    ["src/**/*.ejs",'!' + "src/**/_*.ejs"]
    )
  .pipe(ejs())
  .pipe(gulp.dest('htdocs/'))
});

gulp.task('js', function(){
	gulp.src('src/js/custom/*.js')
	.pipe(plumber())
	.pipe(concat('function.js'))
	.pipe(gulp.dest('htdocs/js'));
});


gulp.task('sass', function() {

  gulp.src('src/sass/**/*.scss')
  .pipe(plumber())
  .pipe(sass({
    style: 'expanded'
  }))
  .pipe(pleeease({
    autoprefixer: {
      browsers: ['last 2 versions', 'ie 8', 'ie 9']
    },
    minifier: false
  }))
  .pipe(gulp.dest('htdocs/css/'));
});

gulp.task('browser-sync', function(){
	browserSync({
		server: {
			baseDir: 'htdocs/',
			directory: true,
		},
	});
});

gulp.task( 'watch', function() {
  gulp.watch( [ 'src/**/*.ejs' ], [ 'ejs' ] );
	gulp.watch( [ 'src/js/custom/*.js' ], [ 'js' ] );
	gulp.watch( [ 'src/sass/**/*.scss' ], [ 'sass' ] );
	gulp.watch( [ 'htdocs/**/*.html','htdocs/css/*.css' ], reload );
} );

gulp.task('default', ['ejs', 'js', 'sass', 'watch', 'browser-sync']);


