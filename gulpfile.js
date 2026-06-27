const gulp = require('gulp')
const {src, dest, watch} = require('gulp')
const sass = require('gulp-sass')(require('sass'))
const sourcemaps = require('gulp-sourcemaps')
const browserSync = require('browser-sync')
const uglify = require('gulp-uglify')
const cleanCSS = require('gulp-clean-css')
const rename = require("gulp-rename")
const gulpIf = require('gulp-if');
const plumber = require('gulp-plumber');
const webpack = require('webpack');
const webpackStream = require('webpack-stream');
const TerserPlugin = require('terser-webpack-plugin');
const glob = require('glob');
const path = require('path');

require('dotenv').config()

// Set NODE_ENV to development or production.
// Use NODE_ENV="development" in .env to enable development mode with sourcemaps.
const isDev = (process.env.NODE_ENV === 'development');

// BrowserSync server proxy.
// Set PROXY in .env to override the local development URL.
const proxy = process.env.PROXY || "localhost/themeblank";

const server = () => {
    browserSync.init({
        proxy: proxy,
        open: false,
        cors: true,
        ghostMode: false
    })
}

// function build scss pipeline
const buildScssPipeline = ({ input, output, includePaths = ['node_modules', 'src'] }) => {
    return src(input)
        .pipe(plumber({
            errorHandler: function (err) {
                console.error(err.message);
                this.emit('end');
            }
        }))
        .pipe(gulpIf(isDev, sourcemaps.init()))
        .pipe(sass({
            outputStyle: 'expanded',
            includePaths: includePaths
        }).on('error', sass.logError))
        .pipe(cleanCSS({ level: 2 }))
        .pipe(rename({ suffix: '.min' }))
        .pipe(gulpIf(isDev, sourcemaps.write()))
        .pipe(dest(output))
        .pipe(browserSync.stream());
};

// function buildJSPipeline
const buildJsPipeline = ({ input, output, label = 'JS Pipeline', base = undefined }) => {
    const options = { allowEmpty: true };

    if (base) {
        options.base = base;
    }

    return src(input, options)
        .pipe(plumber({
            errorHandler: function (err) {
                console.error(`Error in build JS in ${label}:`, err.message);
                this.emit('end');
            }
        }))
        .pipe(uglify())
        .pipe(rename({ suffix: '.min' }))
        .pipe(dest(output))
        .pipe(browserSync.stream());
}

const normalizePath = (filePath) => filePath.replace(/\\/g, '/');

const isScssPartial = (filePath) => path.basename(filePath).startsWith('_');

const getWebpackEntryPath = (filePath) => './' + normalizePath(path.relative(process.cwd(), path.resolve(filePath)));

const getRelativeOutputDir = ({ filePath, sourceRoot, outputRoot }) => {
    const relativePath = path.relative(path.resolve(sourceRoot), path.resolve(filePath));
    const relativeDir = path.dirname(relativePath);

    return normalizePath(path.join(outputRoot, relativeDir === '.' ? '' : relativeDir));
}

const buildScssEntryFile = ({ filePath, sourceRoot, outputRoot }) => {
    return buildScssPipeline({
        input: normalizePath(filePath),
        output: getRelativeOutputDir({ filePath, sourceRoot, outputRoot })
    });
}

const watchChangedEntry = (globs, task) => {
    return watch(globs)
        .on('change', task)
        .on('add', task);
}

// function buildWebpackPipeline
const buildWebpackPipeline = ({ input, output, filename, entries }) => {
    // Shared Webpack configuration.
    const webpackConfig = {
        mode: 'production',
        output: {
            // Use entry names when multiple entries are provided.
            filename: entries ? '[name].min.js' : filename,
        },
        entry: entries || input,
        module: {
            rules: [
                {
                    test: /\.m?js$/,
                    exclude: /node_modules/,
                    use: {
                        loader: 'babel-loader',
                        options: {
                            presets: ['@babel/preset-env']
                        }
                    }
                }
            ]
        },
        resolve: {
            extensions: ['.js']
        },
        optimization: {
            minimize: true,
            minimizer: [
                new TerserPlugin({
                    extractComments: false,
                    terserOptions: {
                        format: {
                            comments: false
                        },
                    },
                })
            ]
        }
    };

    return src(input, { allowEmpty: true })
        .pipe(plumber({
            errorHandler: function (err) {
                console.error('Error in build Webpack Pipeline:', err.message);
                this.emit('end');
            }
        }))
        .pipe(webpackStream(webpackConfig, webpack))
        .pipe(dest(output))
        .pipe(browserSync.stream());
}

/**
 * ---------------------------
 * Build Plugins
 * ---------------------------
 */

// function make plugin paths
const makePluginPaths = (slug) => {
    const root = `src/plugins/${slug}`;
    const dist = `plugins/${slug}/assets`;

    return {
        input: {
            scss: `${root}/scss/`,
            js: `${root}/js/`
        },
        output: {
            css: `${dist}/css/`,
            js: `${dist}/js/`
        }
    };
}

/** Plugin Extend Site paths */
const pathPluginES = makePluginPaths('extend-site');

/** Task build style custom login */
const pluginEsBuildStyleCustomLogin = () => {
    return buildScssPipeline({
        input: `${pathPluginES.input.scss}custom-login.scss`,
        output: `${pathPluginES.output.css}backend/`
    })
}

/** Task build js plugin extend site */
const pluginEsBuildJs = () => {
    return buildJsPipeline({
        input: `${pathPluginES.input.js}*/**.js`,
        output: `${pathPluginES.output.js}`,
        base: pathPluginES.input.js
    })
}

/** Task build changed js plugin extend site */
const pluginEsBuildJsFile = (filePath) => {
    return buildJsPipeline({
        input: normalizePath(path.resolve(filePath)),
        output: `${pathPluginES.output.js}`,
        label: 'Plugin Extend Site JS',
        base: path.resolve(pathPluginES.input.js)
    })
}

/** Watch all plugin extend site */
const pluginEsWatchAll = () => {
    // watch custom login scss
    watch([
        `${pathPluginES.input.scss}custom-login.scss`
    ], pluginEsBuildStyleCustomLogin)

    // watch js
    watchChangedEntry([
        `${pathPluginES.input.js}*/**.js`
    ], pluginEsBuildJsFile)
}

/** ---------------------------
 * Build vendors
 * ---------------------------
 */
const themeName = 'themeblank';

// function make vendor paths
const makeVendorPaths = (slug) => {
    const root = `./src/vendors/${slug}`;
    const dist = `themes/${themeName}/assets/vendors/${slug}`;

    return {
        input: `${root}/`,
        output: `${dist}/`
    };
}

const pathVendorBootstrap = makeVendorPaths('bootstrap');

/** task build style custom bootstrap */
const buildStyleCustomBootstrap = () => {
    return buildScssPipeline({
        input: `${pathVendorBootstrap.input}scss/*.scss`,
        output: `${pathVendorBootstrap.output}`
    })
}

/** task build js custom bootstrap */
const buildJSCustomBootstrap = () => {
    return buildWebpackPipeline({
        input: `${pathVendorBootstrap.input}js/custom-bootstrap.js`,
        output: `${pathVendorBootstrap.output}`,
        filename: 'custom-bootstrap.min.js'
    });
}

const vendorWatchAll = () => {
    watch([
        `${pathVendorBootstrap.input}scss/*.scss`
    ], buildStyleCustomBootstrap)

    watch([
        `${pathVendorBootstrap.input}js/*.js`
    ], buildJSCustomBootstrap)
}

/** ---------------------------
 * Build Theme
 * ---------------------------
 */

// function make theme paths
const makeThemePaths = () => {
    const root = `src/theme`;
    const dist = `themes/${themeName}/assets`;

    return {
        input: {
            scss: `${root}/scss/`,
            js: `${root}/js/`
        },
        output: {
            css: `${dist}/css/`,
            js: `${dist}/js/`
        }
    };
}

/** Theme paths */
const pathTheme = makeThemePaths();

/** Task build style theme */
const buildStyleTheme = () => {
    return buildScssPipeline({
        input: `${pathTheme.input.scss}main.scss`,
        output: `${pathTheme.output.css}`
    })
}

/** Task build style custom post type */
const buildStyleCustomPostType = () => {
    return buildScssPipeline({
        input: `${pathTheme.input.scss}post-type/**/*.scss`,
        output: `${pathTheme.output.css}post-type/`
    })
}

/** Task build style page template */
const buildStylePageTemplate = () => {
    return buildScssPipeline({
        input: `${pathTheme.input.scss}page-templates/*.scss`,
        output: `${pathTheme.output.css}page-templates/`
    })
}

/** Task build js theme */
const buildJSTheme = () => {
    // Build an entry map for multiple theme JS outputs.
    const entries = glob.sync(`${pathTheme.input.js}*.js`).reduce((result, file) => {
        const name = path.basename(file, '.js');
        result[name] = './' + file.replace(/\\/g, '/');
        return result;
    }, {});

    return buildWebpackPipeline({
        input: `${pathTheme.input.js}*.js`,
        output: `${pathTheme.output.js}`,
        entries: entries
    });
}

/** Task build changed js theme entry */
const buildJSThemeFile = (filePath) => {
    const name = path.basename(filePath, '.js');

    return buildWebpackPipeline({
        input: normalizePath(filePath),
        output: `${pathTheme.output.js}`,
        entries: {
            [name]: getWebpackEntryPath(filePath)
        }
    });
}
/** Task build changed custom post type style entry */
const buildStyleCustomPostTypeFile = (filePath) => {
    if (isScssPartial(filePath)) {
        return buildStyleCustomPostType();
    }

    return buildScssEntryFile({
        filePath,
        sourceRoot: `${pathTheme.input.scss}post-type/`,
        outputRoot: `${pathTheme.output.css}post-type/`
    });
}

/** Task build changed page template style entry */
const buildStylePageTemplateFile = (filePath) => {
    if (isScssPartial(filePath)) {
        return buildStylePageTemplate();
    }

    return buildScssEntryFile({
        filePath,
        sourceRoot: `${pathTheme.input.scss}page-templates/`,
        outputRoot: `${pathTheme.output.css}page-templates/`
    });
}
/** Watch Shared build style */
const buildWatchAbstracts = () => {
    watch([
        `${pathTheme.input.scss}abstracts/*.scss`
    ], gulp.parallel(
        buildStyleCustomBootstrap,
        buildStyleTheme,
        buildStyleCustomPostType,
        buildStylePageTemplate,
    ))
}

/** Watch all theme */
const themeWatchAll = () => {
    watch([
        `${pathTheme.input.scss}base/*.scss`,
        `${pathTheme.input.scss}utilities/*.scss`,
        `${pathTheme.input.scss}components/*.scss`,
        `${pathTheme.input.scss}layout/*.scss`,
        `${pathTheme.input.scss}main.scss`,
    ], buildStyleTheme)

    watchChangedEntry([
        `${pathTheme.input.scss}post-type/**/*.scss`
    ], buildStyleCustomPostTypeFile)

    watchChangedEntry([
        `${pathTheme.input.scss}page-templates/*.scss`
    ], buildStylePageTemplateFile)

    watchChangedEntry([`${pathTheme.input.js}*.js`], buildJSThemeFile)
}

/*
Task build project
* */
const buildProject = async () => {
    // Build plugin assets in parallel.
    await Promise.all([
        pluginEsBuildStyleCustomLogin(),
        pluginEsBuildJs(),
    ]);

    // Build vendor and theme assets in parallel.
    await Promise.all([
        buildStyleCustomBootstrap(),
        buildStyleTheme(),
        buildStyleCustomPostType(),
        buildStylePageTemplate(),
        buildJSCustomBootstrap(),
        buildJSTheme(),
    ]);

    console.log("Project build completed.");
}
exports.buildProject = buildProject

// Task watch
const watchTaskAll = () => {
    server()

    // watch plugins extend site
    pluginEsWatchAll()

    // watch vendors
    vendorWatchAll()

    // watch shared styles
    buildWatchAbstracts()

    // watch theme
    themeWatchAll()
}
exports.watchTask = watchTaskAll
