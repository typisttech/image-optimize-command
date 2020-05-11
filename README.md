<div align="center">

# Image Optimize Command

</div>

<div align="center">


[![Packagist](https://img.shields.io/packagist/v/typisttech/image-optimize-command.svg?style=flat-square)](https://packagist.org/packages/typisttech/image-optimize-command)
[![Packagist](https://img.shields.io/packagist/dt/typisttech/image-optimize-command.svg?style=flat-square)](https://packagist.org/packages/typisttech/image-optimize-command)
![PHP from Packagist](https://img.shields.io/packagist/php-v/TypistTech/image-optimize-command?style=flat-square)
[![CircleCI](https://img.shields.io/circleci/build/gh/TypistTech/image-optimize-command?style=flat-square)](https://circleci.com/gh/TypistTech/image-optimize-command)
[![Codecov](https://img.shields.io/codecov/c/gh/TypistTech/image-optimize-command?style=flat-square)](https://codecov.io/gh/TypistTech/image-optimize-command)
[![license](https://img.shields.io/github/license/TypistTech/image-optimize-command.svg?style=flat-square)](https://github.com/TypistTech/image-optimize-command/blob/master/LICENSE)
[![Twitter Follow @TangRufus](https://img.shields.io/twitter/follow/TangRufus?style=flat-square&color=1da1f2&logo=twitter)](https://twitter.com/tangrufus)
[![Hire Typist Tech](https://img.shields.io/badge/Hire-Typist%20Tech-ff69b4.svg?style=flat-square)](https://www.typist.tech/contact/)

</div>

<p align="center">
  <strong>Easily optimize images using WP CLI</strong>
  <br />
  <br />
  Built with ♥ by <a href="https://www.typist.tech/">Typist Tech</a>
</p>

---

**Image Optimize Command** is an open source project and completely free to use.

However, the amount of effort needed to maintain and develop new features is not sustainable without proper financial backing. If you have the capability, please consider donating using the links below:

<div align="center">

[![GitHub via Sponsor](https://img.shields.io/badge/Sponsor-GitHub-ea4aaa?style=flat-square&logo=github)](https://github.com/sponsors/TangRufus)
[![Sponsor via PayPal](https://img.shields.io/badge/Sponsor-PayPal-blue.svg?style=flat-square&logo=paypal)](https://typist.tech/go/paypal-donate/)
[![More Sponsorship Information](https://img.shields.io/badge/Sponsor-More%20Details-ff69b4?style=flat-square)](https://typist.tech/donate/image-optimize-command/)

</div>

---

**Image Optimize Command** is a WP CLI wrapper for [spatie/image-optimizer](https://github.com/spatie/image-optimizer) which **optimize `gif`, `jpeg`, `jpg`, `png`, `svg`, `webp` images by running them through a chain of various image <a href="https://github.com/spatie/image-optimizer#optimization-tools">optimization tools</a>.** Read [the introductory blog post](https://typist.tech/articles/easily-optimize-wordpress-images-using-wp-cli-and-some-binaries/) about why I built it.


## Usage

```sh-session
# optimize specific attachments
$ wp image-optimize attachment 123 223 323

# optimize certain number of attachments
$ wp image-optimize batch --limit=20

# restore the full sized images of specific attachments
$ wp image-optimize restore 123 223 323
$ wp media regenerate 123 223 323

# restore all full sized images and drop all meta flags
$ wp image-optimize reset
$ wp media regenerate

# Find and optimize images under a given directory **without backup**
$ wp image-optimize find /path/to/my/directory --extensions=gif,jpeg,jpg,png,svg,webp

# shortcusts for `wp image-optimize find` **without backup**
$ wp image-optimize mu-plugins
$ wp image-optimize plugins
$ wp image-optimize themes
$ wp image-optimize wp-admin
$ wp image-optimize wp-includes

# learn more
$ wp help image-optimize
$ wp help image-optimize <subcommand>
```

---

<p align="center">
  <strong>Typist Tech is ready to build your next awesome WordPress site. <a href="https://typist.tech/contact/">Hire us!</a></strong>
</p>

---

## Requirements

- PHP v7.2 or later
- [WP-CLI](https://wp-cli.org/) v2.3.0 or greater

Since [`wp-cli/wp-cli-bundle` bundles an older version of `symfony/process`](https://github.com/wp-cli/wp-cli-bundle/blob/7e3d89c415db7922e923703d5f06613f0a60b8a9/composer.lock#L1204-L1205) which incompatible with [`spatie/image-optimizer`](https://github.com/spatie/image-optimizer/blob/48f71b968f2764eca8f4885a8a5401d61e920ba8/composer.json#L22), WP-CLI must be [installed via composer](https://make.wordpress.org/cli/handbook/installing/#installing-via-composer).

## Installation

```sh-session
$ wp package install typisttech/image-optimize-command:@stable
```

### Optimization tools

Under the hood, `image-optimize-command` invokes [`spatie/image-optimizer`](https://github.com/spatie/image-optimizer) which requires these binaries installed:

- [JpegOptim](http://freecode.com/projects/jpegoptim)
- [Optipng](http://optipng.sourceforge.net/)
- [Pngquant 2](https://pngquant.org/)
- [SVGO](https://github.com/svg/svgo)
- [Gifsicle](https://www.lcdf.org/gifsicle/)
- [cwebp](https://developers.google.com/speed/webp/)

Check `spatie/image-optimizer`'s readme for [install instructions](https://github.com/spatie/image-optimizer#optimization-tools).

Note that `spatie/image-optimizer` only supports Pngquant 2.5 and lower. See: `spatie/image-optimizer` [#97](https://github.com/spatie/image-optimizer/issues/97) and [#99](https://github.com/spatie/image-optimizer/issues/99).

#### SVGO and cwebp are optional

Note that [WordPress doesn't support svg files](https://core.trac.wordpress.org/ticket/24251) out of the box. You can omit [SVGO](https://github.com/svg/svgo).
However, if you have [enabled WordPress svg support](https://kinsta.com/blog/wordpress-svg/?kaid=CGCHYHJJJMMF) and uploaded svg files to WordPress media library, you must install SVGO. Otherwise, the command will fail.

Same goes for cwebp.

## Use Cases

### First use

This command optimize both the full sized image(the one you uploaded) and the thumbnails(WordPress auto-resize these images for you).

Chances are the thumbnails are missing or never generated:

- theme switched after upload
- plugins activated after upload
- deleted the images from disk but not updated WordPress' database

Simplest solution is to regenerate thumbnails then optimize:

```sh-session
$ wp media regenerate
$ wp image-optimize batch --limit=9999999

$ wp image-optimize mu-plugins
$ wp image-optimize plugins
$ wp image-optimize themes
$ wp image-optimize wp-admin
$ wp image-optimize wp-includes
```

### After upgrading WordPress core / plugins / themes

```sh-session
$ wp image-optimize mu-plugins
$ wp image-optimize plugins
$ wp image-optimize themes
$ wp image-optimize wp-admin
$ wp image-optimize wp-includes
```

### Restoring the originals

This command backs up the full sized images before optimizing attachments. If you want to restore them:

```sh-session
# optimize
$ wp image-optimize attachment 123

# restore the full sized image
$ wp image-optimize restore 123
# regenerate the thumbnails from the original full sized image
$ wp media regenerate 123
```

### Migrating from image-optimize-command v0.1.x

Starting from v0.2, this command backs up the full sized images before optimizing attachments. To migrate from image-optimize-command v0.1.x:

```sh-session
$ wp image-optimize reset
$ wp media regenerate
$ wp image-optimize batch --limit=9999999
```

## FAQs

### What kind of optimization it does?

Mostly applying compression, removing metadata and reducing the number of colors to `gif`, `jpeg`, `jpg`, `png`, `svg`, `webp` images. The package is smart enough pick the right tool for the right image.

Check Freek Van der Herten's [article](https://murze.be/easily-optimize-images-using-php-and-some-binaries) explaining `spatie/image-optimizer`'s [*sane default configuration*](https://github.com/spatie/image-optimizer/blob/124da0d/src/OptimizerChainFactory.php).

### How to customize the optimization?

```php
use Spatie\ImageOptimizer\OptimizerChain;

add_filter('TypistTech/ImageOptimizeCommand/OptimizerChain', function (OptimizerChain $optimizerChain): OptimizerChain {
    // Option A: Send messages to $optimizerChain.
    $optimizerChain->setTimeout($xxx);
    $optimizerChain->useLogger($yyy);
    $optimizerChain->addOptimizer($zzz);

    // Option B: Make a new $optimizerChain.
    // See: https://github.com/spatie/image-optimizer/blob/master/src/OptimizerChainFactory.php
    $optimizerChain = new OptimizerChain();
    $optimizerChain->addOptimizer($zzz);

    // Finally
    return $optimizerChain;
});
```

### How to skip backups / restorations?

:warning: The optimzation is [lossy](https://github.com/TypistTech/image-optimize-command#will-the-images-look-different-after-optimization). **If you skip backing up the original files, there is no way to recover the original files.** Proceed with caution.

```php

use TypistTech\ImageOptimizeCommand\Operations\AttachmentImages\Backup;
use TypistTech\ImageOptimizeCommand\Operations\AttachmentImages\Restore;

add_filter('TypistTech/ImageOptimizeCommand/Operations/AttachmentImages/Backup', function (): Backup {
    // TODO: You have to implement a null backup class.
    return $myNullBackupObject;
});

add_filter('TypistTech/ImageOptimizeCommand/Operations/AttachmentImages/Restore', function (): Restore {
    // TODO: You have to implement a null restore class.
    return $myNullRestoreObject;
});
```

It would be nice to have those 2 null operations in a separate package. Submit pull requests to mention it in this readme if you have implemented it.

### Does running `wp image-optimize attachment / batch` multiple times trigger multiple optimization for the same attachments?

No.

By default, boolean flags ([meta fields](https://developer.wordpress.org/reference/functions/add_post_meta/)) are given to attachments after optimization. This is to prevent re-optimizing an already optimized attachment. If you changed the image files (e.g.: resize / regenerate thumbnail), you must first reset their meta flags.

Note: The `find` subcommand and its shortcuts don't create meta flags.

### Will the images look different after optimization?

Yes, a little bit. This is lossy optimization. However, you won't notice the difference unless you have a trained eye for that.

See [`spatie/image-optimizer`](https://github.com/spatie/image-optimizer#which-tools-will-do-what)'s readme on binary options used.

### Why my GIFs stopped animating?

> When you upload an image using the media uploader, WordPress automatically creates several copies of that image in different sizes...When creating new image sizes for animated GIFs, **WordPress ends up saving only the first frame of the GIF**...
>
> --- [wpbeginner](http://www.wpbeginner.com/wp-tutorials/how-to-add-animated-gifs-in-wordpress/)

Luckily for you, Lasse M. Tvedt showed [how to stop WordPress from resizing GIFs](https://wordpress.stackexchange.com/a/229724) on StackExchange.

### Can I use this on managed hosting?

No, you can't use this on managed hosting such as [Kinsta](https://typist.tech/go/kinsta), [Flywheel](https://typist.tech/go/flywheel) or [WP Engine](https://typist.tech/go/wp-engine) because they prohibit installing the binaries.

If you must use it on managed hosting, [hire a developer](https://typist.tech/contact/) to add SaaS provider integration:

- [EWWW](https://typist.tech/go/ewww/)
- [Kraken](https://typist.tech/go/kraken/)
- [ImageOptim](https://typist.tech/go/imageoptim-api/)
- [Imagify](https://typist.tech/go/imagify/)


### Do I have to install `SVGO` or `cwebp`?

**No**, if you don't have any svg files in WordPress media library.

**Yes**, if you have:

- [enabled WordPress SVG support](https://kinsta.com/blog/wordpress-svg/?kaid=CGCHYHJJJMMF)
- uploaded SVGs to WordPress media library
- using the `find` subcommand (or its shortcuts) with `--extensions=svg`

Same goes for cwebp.

### `PHP Fatal error: Allowed memory size of 999999 bytes exhausted (tried to allocate 99 bytes)`

This is a common WP CLI issue. See: [https://bit.ly/wpclimem](https://make.wordpress.org/cli/handbook/common-issues/#php-fatal-error-allowed-memory-size-of-999999-bytes-exhausted-tried-to-allocate-99-bytes)

### Does it have a limits or quotas?

No, unlike other SaaS alternatives, this package runs on your server without any limitation on file sizes or a monthly quota. Totally free of charge.

### Is it for everyone?

No, it comes at a cost. Optimization is CPU intensive. Expect CPU usage rockets up to 100% during optimization. Schedule it to run at late night in small batches.

Learn more on [this article](https://typist.tech/articles/easily-optimize-wordpress-images-using-wp-cli-and-some-binaries/).

### Will you add support for older PHP versions?

Never! This plugin will only work on [actively supported PHP versions](https://secure.php.net/supported-versions.php).

Don't use it on **end of life** or **security fixes only** PHP versions.

### It looks awesome. Where can I find some more goodies like this

- Articles on [Typist Tech's blog](https://typist.tech)
- [Tang Rufus' WordPress plugins](https://profiles.wordpress.org/tangrufus#content-plugins) on wp.org
- More projects on [Typist Tech's GitHub profile](https://github.com/TypistTech)
- Stay tuned on [Typist Tech's newsletter](https://typist.tech/go/newsletter)
- Follow [Tang Rufus' Twitter account](https://twitter.com/TangRufus)
- **Hire [Tang Rufus](https://typist.tech/contact) to build your next awesome site**

### Where can I give 5-star reviews?

Thanks! Glad you like it. It's important to let me knows somebody is using this project. Please consider:

- [tweet](https://twitter.com/intent/tweet?text=Image%20Optimize%20Command%20-%20%40wpcli%20wrapper%20for%20%40spatie_be%20image%20optimizer&url=https://github.com/TypistTech/image-optimize-command&hashtags=webdev,wordpress&via=TangRufus&url=https://github.com/TypistTech/image-optimize-command&hashtags=webdev,wordpress&via=TangRufus) something good with mentioning [@TangRufus](https://twitter.com/tangrufus)
- ★ star [the Github repo](https://github.com/TypistTech/image-optimize-command)
- [👀 watch](https://github.com/TypistTech/image-optimize-command/subscription) the Github repo
- write tutorials and blog posts
- **[hire](https://www.typist.tech/contact/) Typist Tech**

## Testing

Run the tests:

```sh-session
$ composer test
$ composer style:check
```

## Feedback

**Please provide feedback!** We want to make this project as useful as possible.
Please [submit an issue](https://github.com/TypistTech/image-optimize-command/issues/new) and point out what you do and don't like, or fork the project and [send pull requests](https://github.com/TypistTech/image-optimize-command/pulls/).
**No issue is too small.**

## Security Vulnerabilities

If you discover a security vulnerability within this project, please email us at [image-optimize-command@typist.tech](mailto:image-optimize-command@typist.tech). 
All security vulnerabilities will be promptly addressed.

## Credits

[Image Optimize Command](https://github.com/TypistTech/image-optimize-command) is a [Typist Tech](https://www.typist.tech) project and maintained by [Tang Rufus](https://twitter.com/Tangrufus), freelance developer for [hire](https://www.typist.tech/contact/).

Special thanks to [Freek Van der Herten](https://github.com/freekmurze/) whose [`spatie/image-optimizer`](https://github.com/spatie/image-optimizer) package makes this project possible.

Full list of contributors can be found [here](https://github.com/TypistTech/image-optimize-command/graphs/contributors).

## License

[Image Optimize Command](https://github.com/TypistTech/image-optimize-command) is released under the [MIT License](https://opensource.org/licenses/MIT).
