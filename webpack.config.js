var Encore = require('@symfony/webpack-encore');

Encore
    // directory where compiled assets will be stored
    .setOutputPath('public/build/')
    // public path used by the web server to access the output path
    .setPublicPath('/build')
    // only needed for CDN's or sub-directory deploy
    //.setManifestKeyPrefix('build/')

    /*
     * ENTRY CONFIG
     *
     * Add 1 entry for each "page" of your app
     * (including one that's included on every page - e.g. "app")
     *
     * Each entry will result in one JavaScript file (e.g. app.js)
     * and one CSS file (e.g. app.css) if you JavaScript imports CSS.
     */
    .enableVueLoader()
    .addStyleEntry('css/admin', ['./assets/scss/admin.scss'])
    .addStyleEntry('css/vuetify', ['./assets/css/vuetify.css'])
    .addStyleEntry('css/home', ['./assets/scss/home.scss'])
    .addStyleEntry('css/services', ['./assets/scss/services.scss'])
    .addStyleEntry('css/faq', ['./assets/scss/faq.scss'])
    .addStyleEntry('css/tarif', ['./assets/scss/tarif.scss'])
    .addStyleEntry('css/contact', ['./assets/scss/contact.scss'])
    .addStyleEntry('css/reservation', ['./assets/scss/reservation.scss'])
    .addEntry('js/admin', './assets/admin/main.js')
    .addEntry('js/app', './assets/js/app')
    .addEntry('js/base', './assets/js/base')
    .addEntry('js/home', './assets/js/home')
    .addEntry('js/faq', './assets/js/faq')
    .addEntry('js/contact', './assets/js/contact')
    .addEntry('js/reservation', './assets/js/reservation')

    //.addEntry('page1', './assets/js/page1.js')
    //.addEntry('page2', './assets/js/page2.js')

    // will require an extra script tag for runtime.js
    // but, you probably want this, unless you're building a single-page app
    .enableSingleRuntimeChunk()

    /*
     * FEATURE CONFIG
     *
     * Enable & configure other features below. For a full
     * list of features, see:
     * https://symfony.com/doc/current/frontend.html#adding-more-features
     */
    .cleanupOutputBeforeBuild()
    .enableBuildNotifications()
    .enableSourceMaps(!Encore.isProduction())
    // enables hashed filenames (e.g. app.abc123.css)
    .enableVersioning(Encore.isProduction())

    // enables Sass/SCSS support
    .enableSassLoader()

    // uncomment if you use TypeScript
    //.enableTypeScriptLoader()

    // uncomment if you're having problems with a jQuery plugin
    // .autoProvidejQuery()

    // uncomment if you use API Platform Admin (composer req api-admin)
    //.enableReactPreset()
    //.addEntry('admin', './assets/js/admin.js')
;

module.exports = Encore.getWebpackConfig();
