module.exports = function(grunt) {
    //
    // Pathes
    //

    /**
     * with trailing slashed
     * with bower_components (mostly)
     * @type {string}
     */
    let path_bower = 'bower_components/';
    /**
     * with vendor when needed, as most hooks and therefor gruntfile is under
     * composer vendor dir: vendor mostly not needed with trailing slash
     * @type {string}
     */
    let path_composer = 'vendor/';

    /**
     * with trailing slash
     * @type {string}
     */
    let path_js_src_dir = 'tpl/asset/_dev/js/';
    /**
     * over here normally parent folder of js src folder
     * with trailing slash
     * @type {string}
     */
    let path_js_build_dir = 'tpl/asset/';

    /**
     * without extension
     * @type {string}
     */
    let path_sass_src_file = 'tpl/asset/_dev/main';
    /**
     * over here normally parent folder of css src folder
     * with trailing slash
     * @type {string}
     */
    let path_sass_build_dir = 'tpl/asset/';
    /**
     * without extension
     * @type {string}
     */
    let path_sass_build_file = 'style';

    /**
     * to the folder where original images are, with trailing slash
     * @type {string}
     */
    let path_img_src = 'tpl/asset/_dev/media/';
    /**
     * to the folder where public images are, those accessible from browser,
     * with trailing slash
     * @type {string}
     */
    let path_img_build = 'tpl/asset/media/';

    //
    // JS concat
    //

    let js_concat = [
        path_bower + 'jQuery/dist/jquery.min.js',
        path_js_src_dir + '**/*.js'
    ];

    //
    // Options
    //

    /**
     * imagemin level of optimization for png and dynamic (svg|gif)
     * @type {number}
     */
    let img_optimization_lvl = 3;
    /**
     * imagemin level of builded image quality for jpeg and dynamic (svg|gif)
     * @type {number}
     */
    let img_quality_lvl = 90;

    //
    // Watcher
    //

    /**
     * The more files must be scanned the longer it takes, keep the list clean!
     * @type {[*]}
     */
    let watch_css = [
        path_sass_build_dir + '**/*.scss',
        '!**/node_modules/**',
        '!**/*.min.css'
    ];
    /**
     * The more files must be scanned the longer it takes, keep the list clean!
     * @type {[*]}
     */
    let watch_js = [
        path_js_src_dir + '**/*.js',
        '!**/node_modules/**',
        '!**/*.min.js'
    ];
    /**
     * The more files must be scanned the longer it takes, keep the list clean!
     * @type {[*]}
     */
    let watch_img = [
        path_img_src + '**/*.{gif,svg,png,jpg}',
    ];

    require('time-grunt')(grunt);

    require('load-grunt-tasks')(grunt);

    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),

        // JS
        concat: {
            dist: {
                // warns when something was not found but was specified
                nonull: true,
                src: js_concat,
                dest: path_js_build_dir + 'js.js'
            }
        },
        uglify: {
            build: {
                src: path_js_build_dir + 'js.js',
                dest: path_js_build_dir + 'js.min.js',
            }
        },

        // CSS
        sass: {
            options: {
                sourceMap: true
            },
            dist: {
                files: {
                    [path_sass_build_dir + path_sass_build_file + '.css']: path_sass_src_file + '.scss'
                }
            }
        },
        cssmin: {
            target: {
                files: [{
                    expand: true,
                    cwd: path_sass_build_dir,
                    src: [path_sass_build_file + '.css', '!' + path_sass_build_file + '.css.map'],
                    dest: path_sass_build_dir,
                    ext: '.min.css'
                }]
            }
        },
        postcss: {
            options: {
                map: false,
                processors: [
                    require('pixrem')(), // add fallbacks for rem units
                    require('autoprefixer')({browsers: 'last 4 versions'})
                ]
            },
            dist: {
                src: path_sass_build_dir + path_sass_build_file + '.min.css'
            }
        },

        // Image
        imagemin: {
            png: {
                options: {
                    optimizationLevel: img_optimization_lvl
                },
                files: [{
                    expand: true,
                    cwd: path_img_src,
                    src: ['**/*.png'],
                    dest: path_img_build
                }]
            },
            jpg: {
                options: {
                    quality: img_quality_lvl,
                    progressive: true,
                    use: [require('imagemin-mozjpeg')()]
                },
                files: [{
                    expand: true,
                    cwd: path_img_src,
                    src: ['**/*.jpg'],
                    dest: path_img_build
                }]
            },
            dynamic: {
                options: {
                    optimizationLevel: img_optimization_lvl,
                    quality: img_quality_lvl,
                    svgoPlugins: [{removeViewBox: false}]
                },
                files: [{
                    expand: true,
                    cwd: path_img_src,
                    src: ['**/*.{gif,svg}'],
                    dest: path_img_build
                }]
            }
        },

        // Multi Tasking
        concurrent: {
            image: ['imagemin:png', 'imagemin:jpg', 'imagemin:dynamic'],
            build: [['js'], ['css'], 'concurrent:image']
        },

        // JS and CSS/Sass file watcher
        watch: {
            css: {
                files: watch_css,
                tasks: ['css']
            },
            js: {
                files: watch_js,
                tasks: ['js']
            },
            image: {
                files: watch_img,
                tasks: ['image']
            }
        }
    });

    // Multi-Thread Task Runner
    grunt.loadNpmTasks('grunt-concurrent');

    // JS
    grunt.registerTask('js', ['concat', 'uglify']);

    // SASS
    grunt.registerTask('css', ['sass', 'cssmin', 'postcss']);

    // Images
    grunt.registerTask('image', ['concurrent:image']);

    // Build All
    grunt.registerTask('build', ['concurrent:build']);
};