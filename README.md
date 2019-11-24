# typisttech/image-optimize-command

[![Packagist](https://img.shields.io/packagist/v/typisttech/image-optimize-command.svg)](https://packagist.org/packages/typisttech/image-optimize-command)
[![Packagist](https://img.shields.io/packagist/dt/typisttech/image-optimize-command.svg)](https://packagist.org/packages/typisttech/image-optimize-command)
[![CircleCI](https://circleci.com/gh/TypistTech/image-optimize-command.svg?style=svg)](https://circleci.com/gh/TypistTech/image-optimize-command)
[![codecov](https://codecov.io/gh/TypistTech/image-optimize-command/branch/master/graph/badge.svg)](https://codecov.io/gh/TypistTech/image-optimize-command)
[![GitHub](https://img.shields.io/github/license/TypistTech/image-optimize-command.svg)](https://github.com/TypistTech/image-optimize-command/blob/master/LICENSE.md)
[![GitHub Sponsor](https://img.shields.io/badge/Sponsor-GitHub-ea4aaa)](https://github.com/sponsors/TangRufus)
[![Sponsor via PayPal](https://img.shields.io/badge/Sponsor-PayPal-blue.svg)](https://typist.tech/donate/image-optimize-command/)
[![Hire Typist Tech](https://img.shields.io/badge/Hire-Typist%20Tech-ff69b4.svg)](https://typist.tech/contact/)

Easily optimize images using WP CLI.

<!-- START doctoc generated TOC please keep comment here to allow auto update -->
<!-- DON'T EDIT THIS SECTION, INSTEAD RE-RUN doctoc TO UPDATE -->


- [Usage](#usage)
- [Installing](#installing)
  - [Optimization tools](#optimization-tools)
    - [SVGO and cwebp are optional](#svgo-and-cwebp-are-optional)
- [Use Cases](#use-cases)
  - [First use](#first-use)
  - [After upgrading WordPress core / plugins / themes](#after-upgrading-wordpress-core--plugins--themes)
  - [Restoring the originals](#restoring-the-originals)
  - [Migrating from image-optimize-command v0.1.x](#migrating-from-image-optimize-command-v01x)
- [FAQs](#faqs)
  - [What kind of optimization it does?](#what-kind-of-optimization-it-does)
  - [How to customize the optimization?](#how-to-customize-the-optimization)
  - [How to skip backups / restorations?](#how-to-skip-backups--restorations)
  - [Does running `wp image-optimize attachment / batch` multiple times trigger multiple optimization for the same attachments?](#does-running-wp-image-optimize-attachment--batch-multiple-times-trigger-multiple-optimization-for-the-same-attachments)
  - [Will the images look different after optimization?](#will-the-images-look-different-after-optimization)
  - [Why my GIFs stopped animating?](#why-my-gifs-stopped-animating)
  - [Can I use this on managed hosting?](#can-i-use-this-on-managed-hosting)
  - [Do I have to install `SVGO` or `cwebp`?](#do-i-have-to-install-svgo-or-cwebp)
  - [`PHP Fatal error: Allowed memory size of 999999 bytes exhausted (tried to allocate 99 bytes)`](#php-fatal-error-allowed-memory-size-of-999999-bytes-exhausted-tried-to-allocate-99-bytes)
  - [Does it have a limits or quotas?](#does-it-have-a-limits-or-quotas)
  - [Will you add support for older PHP versions?](#will-you-add-support-for-older-php-versions)
  - [Is it for everyone?](#is-it-for-everyone)
  - [It looks awesome. Where can I find some more goodies like this?](#it-looks-awesome-where-can-i-find-some-more-goodies-like-this)
  - [This package isn't on wp.org. Where can I give a :star::star::star::star::star: review?](#this-package-isnt-on-wporg-where-can-i-give-a-starstarstarstarstar-review)
- [Sponsoring :heart:](#sponsoring-heart)
  - [GitHub Sponsors Matching Fund](#github-sponsors-matching-fund)
  - [Why don't you hire me?](#why-dont-you-hire-me)
  - [Want to help in other way? Want to be a sponsor?](#want-to-help-in-other-way-want-to-be-a-sponsor)
- [Running the Tests](#running-the-tests)
- [Feedback](#feedback)
- [Change log](#change-log)
- [Security](#security)
- [Credits](#credits)
- [License](#license)

<!-- END doctoc generated TOC please keep comment here to allow auto update -->

WP CLI wrapper for [spatie/image-optimizer](https://github.com/spatie/image-optimizer). **Optimizing `gif`, `jpeg`, `jpg`, `png`, `svg`, `webp` images by running them through a chain of various image [optimization tools](#optimization-tools).** Check this project's [introductory blog post](https://typist.tech/articles/easily-optimize-wordpress-images-using-wp-cli-and-some-binaries/) about why I built it.


## Usage

```bash
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

## Installing

Installing this package requires [WP-CLI v2.3.0](https://wp-cli.org/) or greater. Update to the latest stable release with `wp cli update`.

Once you've done so, you can install this package with:

```bash
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

Same goes to cwebp.

## Use Cases

### First use

This command optimize both the full sized image(the one you uploaded) and the thumbnails(WordPress auto-resize these images for you).

Chances are the thumbnails are missing or never generated:

- theme switched after upload
- plugins activated after upload
- deleted the images from disk but not updated WordPress' database

Simplest solution is to regenerate thumbnails then optimize:

```bash
$ wp media regenerate
$ wp image-optimize batch --limit=9999999

$ wp image-optimize mu-plugins
$ wp image-optimize plugins
$ wp image-optimize themes
$ wp image-optimize wp-admin
$ wp image-optimize wp-includes
```

### After upgrading WordPress core / plugins / themes

```bash
$ wp image-optimize mu-plugins
$ wp image-optimize plugins
$ wp image-optimize themes
$ wp image-optimize wp-admin
$ wp image-optimize wp-includes
```

### Restoring the originals

This command backs up the full sized images before optimizing attachments. If you want to restore them:

```bash
# optimize
$ wp image-optimize attachment 123

# restore the full sized image
$ wp image-optimize restore 123
# regenerate the thumbnails from the original full sized image
$ wp media regenerate 123
```

### Migrating from image-optimize-command v0.1.x

Starting from v0.2, this command backs up the full sized images before optimizing attachments. To migrate from image-optimize-command v0.1.x:

```bash
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

No, you can't use this on managed hosting such as [Kinsta](http://bit.ly/kinsta-com), [Flywheel](https://typist.tech/go/flywheel) or [WP Engine](https://typist.tech/go/wp-engine) because they prohibit installing the binaries.

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

Same goes to cwebp.

### `PHP Fatal error: Allowed memory size of 999999 bytes exhausted (tried to allocate 99 bytes)`

This is a common WP CLI issue. See: [https://bit.ly/wpclimem](https://make.wordpress.org/cli/handbook/common-issues/#php-fatal-error-allowed-memory-size-of-999999-bytes-exhausted-tried-to-allocate-99-bytes)

### Does it have a limits or quotas?

No, unlike other SaaS alternatives, this package runs on your server without any limitation on file sizes or a monthly quota. Totally free of charge.

### Will you add support for older PHP versions?

Never! This package will only works on [actively supported PHP versions](https://secure.php.net/supported-versions.php).

Don't use it on **end of life** or **security fixes only** PHP versions.

### Is it for everyone?

No, it comes at a cost. Optimization is CPU intensive. Expect CPU usage rockets up to 100% during optimization. Schedule it to run at late night in small batches.

Learn more on [this article](https://typist.tech/articles/easily-optimize-wordpress-images-using-wp-cli-and-some-binaries/).

### It looks awesome. Where can I find some more goodies like this?

* Articles on Typist Tech's [blog](https://typist.tech)
* [Tang Rufus' WordPress plugins](https://profiles.wordpress.org/tangrufus#content-plugins) on wp.org
* More projects on [Typist Tech's GitHub profile](https://github.com/TypistTech)
* Stay tuned on [Typist Tech's newsletter](https://typist.tech/go/newsletter)
* Follow [Tang Rufus' Twitter account](https://twitter.com/TangRufus)
* Hire [Tang Rufus](https://typist.tech/contact) to build your next awesome site

### This package isn't on wp.org. Where can I give a :star::star::star::star::star: review?

Thanks!

Consider writing a blog post, submitting pull requests, [sponsoring](https://typist.tech/donation/) or [hiring me](https://typist.tech/contact/) instead.

## Sponsoring :heart:

Love `image-optimize-command`? Help me maintain it, a [sponsorship here](https://typist.tech/donation/) can help with it.

### GitHub Sponsors Matching Fund

Do you know [GitHub is going to match your sponsorship](https://help.github.com/en/github/supporting-the-open-source-community-with-github-sponsors/about-github-sponsors#about-the-github-sponsors-matching-fund)?

[Sponsor now via GitHub](https://github.com/sponsors/TangRufus) to double your greatness.

### Why don't you hire me?

Ready to take freelance WordPress jobs. Contact me via the contact form [here](https://typist.tech/contact/) or, via email [info@typist.tech](mailto:info@typist.tech)

### Want to help in other way? Want to be a sponsor?

Contact: [Tang Rufus](mailto:tangrufus@gmail.com)

## Running the Tests

Run the tests:

``` bash
$ composer test
$ composer style:check
```

## Feedback

**Please provide feedback!** We want to make this library useful in as many projects as possible.
Please submit an [issue](https://github.com/TypistTech/image-optimize-command/issues/new) and point out what you do and don't like, or fork the project and make suggestions.
**No issue is too small.**

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Security

If you discover any security related issues, please email [image-optimize-command@typist.tech](mailto:image-optimize-command@typist.tech) instead of using the issue tracker.

## Credits

[`image-optimize-command`](https://github.com/TypistTech/image-optimize-command) is a [Typist Tech](https://typist.tech) project and maintained by [Tang Rufus](https://twitter.com/TangRufus), freelance developer for [hire](https://www.typist.tech/contact/).

Full list of contributors can be found [here](https://github.com/TypistTech/image-optimize-command/graphs/contributors).

Special thanks to [Freek Van der Herten](https://github.com/freekmurze/) whose [`spatie/image-optimizer`](https://github.com/spatie/image-optimizer) package makes this project possible.

## License

The MIT License (MIT). Please see [License File](./LICENSE.md) for more information.
