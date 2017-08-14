# Home Inventory Catalog

This site was built to allow users to catalog and keep track of their possessions and personal property. They can add and manage item details like purchase date, value, warranty info, etc.

## Technical Overview

The repo has been configured and optimized for use with WP Engine's hosting environment. Only the custom theme is version-controlled. WordPress core files, plugins, and uploads are all ignored.

The catalog theme is built on the FoundationPress starter using Foundation 6 by Zurb.

The is also built with some level of dependency on the following plugins:
 * Advanced Custom Fields PRO by Elliot Condon
 * Nav Menu Roles by Kathy Darling

## Requirements

**This project requires [Node.js](http://nodejs.org) v4.x.x to v6.11.x to be installed on your machine.** Please be aware that you might encounter problems with the installation if you are using the most current Node version (bleeding edge) with all the latest features.

FoundationPress uses [Sass](http://Sass-lang.com/) (CSS with superpowers). In short, Sass is a CSS pre-processor that allows you to write styles more effectively and tidy.

The Sass is compiled using libsass, which requires the GCC to be installed on your machine. Windows users can install it through [MinGW](http://www.mingw.org/), and Mac users can install it through the [Xcode Command-line Tools](http://osxdaily.com/2014/02/12/install-command-line-tools-mac-os-x/).

If you have not worked with a Sass-based workflow before, I would recommend reading [FoundationPress for beginners](https://foundationpress.olefredrik.com/posts/tutorials/foundationpress-for-beginners), a short blog post that explains what you need to know.

## Quickstart

### 1. Clone the repository and install with npm
```bash
$ cd my-wordpress-folder/wp-content/themes/catalog
$ npm install
```

### 2. Get started

For WordPress development on localhost, I recommend using [MAMP](https://www.mamp.info/en/) for Mac, [WAMP](http://www.wampserver.com/en/download-wampserver-64bits/) for Windows or [LAMP](https://www.linux.com/learn/easy-lamp-server-installation) for Linux.

If you want to take advantage of browser-sync (automatic browser refresh when a file is saved), simply open your gulpfile.babel.js and put your local dev-server address and port (e.g http://localhost:8888) in the `URL` variable.

Then, simply run

```bash
$ npm start
```

### 3. Compile assets for production

When building for production, the CSS and JS will be minified. To minify the assets in your `/dist` folder, run

```bash
$ npm run build
```

### Project structure

In the `/src` folder you will the working files for all your assets. Every time you make a change to a file that is watched by Gulp, the output will be saved to the `/dist` folder. The contents of this folder is the compiled code that you should not touch (unless you have a good reason for it).

The `/page-templates` folder contains templates that can be selected in the Pages section of the WordPress admin panel. To create a new page-template, simply create a new file in this folder and make sure to give it a template name.

I guess the rest is quite self explanatory. Feel free to ask if you feel stuck.

### Styles and Sass Compilation

 * `style.css`: Do not worry about this file. (For some reason) it's required by WordPress. All styling are handled in the Sass files described below

 * `src/assets/scss/app.scss`: Make imports for all your styles here
 * `src/assets/scss/global/*.scss`: Global settings
 * `src/assets/scss/components/*.scss`: Buttons etc.
 * `src/assets/scss/modules/*.scss`: Topbar, footer etc.
 * `src/assets/scss/templates/*.scss`: Page template styling

 * `dist/assets/css/app.css`: This file is loaded in the `<head>` section of your document, and contains the compiled styles for your project.

If you're new to Sass, please note that you need to have Gulp running in the background (``npm start``), for any changes in your scss files to be compiled to `app.css`.

### JavaScript Compilation

All JavaScript files in the `src/assets/js` folder, along ith Foundation and its dependencies, are bundled into one file called `app.js`. The files are imported using module dependency with [webpack](https://webpack.js.org/) as the module bundler.

If you're unfamiliar with modules and module bundling, check out [this resource for node style require/exports](http://openmymind.net/2012/2/3/Node-Require-and-Exports/) and [this resource to understand ES6 modules](http://exploringjs.com/es6/ch_modules.html).

Foundation modules are loaded in the `src/assets/js/app.js` file. By default all components are loaded. You can also pick and choose which modules to include. Just follow the instructions in the file.