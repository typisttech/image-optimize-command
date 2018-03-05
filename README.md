# typisttech/image-optimize-command

[![Build Status](https://travis-ci.org/TypistTech/image-optimize-command.svg?branch=master)](https://travis-ci.org/TypistTech/image-optimize-command)
[![PHP Versions Tested](http://php-eye.com/badge/typisttech/image-optimize-command/tested.svg)](https://travis-ci.org/TypistTech/image-optimize-command)
[![StyleCI](https://styleci.io/repos/119003751/shield?branch=master)](https://styleci.io/repos/119003751)
[![License](https://poser.pugx.org/typisttech/image-optimize-command/license)](https://packagist.org/packages/typisttech/image-optimize-command)
[![Donate via PayPal](https://img.shields.io/badge/Donate-PayPal-blue.svg)](https://typist.tech/donate/image-optimize-command/)
[![Hire Typist Tech](https://img.shields.io/badge/Hire-Typist%20Tech-ff69b4.svg)](https://typist.tech/contact/)

Easily optimize images using WP CLI.

<!-- START doctoc generated TOC please keep comment here to allow auto update -->
<!-- DON'T EDIT THIS SECTION, INSTEAD RE-RUN doctoc TO UPDATE -->


- [Using](#using)
- [Installing](#installing)
  - [Optimization tools](#optimization-tools)
- [FAQs](#faqs)
  - [What kind of optimization it does?](#what-kind-of-optimization-it-does)
  - [Why the optimize command stopped for no reason?](#why-the-optimize-command-stopped-for-no-reason)
  - [Does running `wp image-optimize run` multiple times trigger multiple optimization for the same attachments?](#does-running-wp-image-optimize-run-multiple-times-trigger-multiple-optimization-for-the-same-attachments)
  - [Why my GIFs stopped animating?](#why-my-gifs-stopped-animating)
  - [Is it for everyone?](#is-it-for-everyone)
- [Support](#support)
  - [Why don't you hire me?](#why-dont-you-hire-me)
  - [Want to help in other way? Want to be a sponsor?](#want-to-help-in-other-way-want-to-be-a-sponsor)
- [Credits](#credits)
- [Contributing](#contributing)
  - [Reporting a bug](#reporting-a-bug)
  - [Creating a pull request](#creating-a-pull-request)

<!-- END doctoc generated TOC please keep comment here to allow auto update -->

WP CLI wrapper for [spatie/image-optimizer](https://github.com/spatie/image-optimizer). **Optimizing PNGs, JPGs, SVGs and GIFs by running them through a chain of various image [optimization tools](#optimization-tools).** Check this project's [introductory blog post](https://typist.tech/articles/easily-optimize-wordpress-images-using-wp-cli-and-some-binaries/) about why I built it.


## Using

```bash
# Optimize 10 attachments
$ wp image-optimize run --limit=10

# Optimize after thumbnail regeneration
$ wp media regenerate --yes
$ wp image-optimize reset --yes
$ wp image-optimize run --limit=9999999
```

## Installing

Installing this package requires WP-CLI v1.4.1 or greater. Update to the latest stable release with `wp cli update`.

Once you've done so, you can install this package with:

```bash
$ wp package install typisttech/image-optimize-command:@stable
```

### Optimization tools

Under the hood, `image-optimize-command` invokes [spatie/image-optimizer](https://github.com/spatie/image-optimizer) which requires these binaries installed:

- [JpegOptim](http://freecode.com/projects/jpegoptim)
- [Optipng](http://optipng.sourceforge.net/)
- [Pngquant 2](https://pngquant.org/)
- [SVGO](https://github.com/svg/svgo)
- [Gifsicle](http://www.lcdf.org/gifsicle/)

Check spatie/image-optimizer's readme for [install instructions](https://github.com/spatie/image-optimizer#optimization-tools).

Note that [WordPress doesn't support SVG](https://core.trac.wordpress.org/ticket/24251) out of the box. You can omit [SVGO](https://github.com/svg/svgo).
However, if you have [enabled WordPress SVG support](https://kinsta.com/blog/wordpress-svg/?kaid=CGCHYHJJJMMF), you must install SVGO. Otherwise, the command will fail.

## FAQs

### What kind of optimization it does?

Mostly applying compression, removing metadata and reducing the number of colors to PNGs, JPGs, SVGs and GIFs. The package is smart enough pick the right tool for the right image.

Check Freek Van der Herten's [article](https://murze.be/easily-optimize-images-using-php-and-some-binaries) explaining `spatie/image-optimizer`'s [*sane default configuration*](https://github.com/spatie/image-optimizer/blob/124da0d/src/OptimizerChainFactory.php).

### Why the optimize command stopped for no reason?

Expected outputs:
```bash
$ wp image-optimize run --limit=3
Success: 3 unoptimized attachment(s) found. Starting...
Start optimizing /app/public/wp-content/uploads/2018/01/source-150x150.gif
Using optimizer: `Spatie\ImageOptimizer\Optimizers\Gifsicle`
Executing `"gifsicle" -b -O3 '/app/public/wp-content/uploads/2018/01/source-150x150.gif'`
...omitted...
Success: 3 attachment(s) optimized

$ wp image-optimize run --limit=10
Warning: No unoptimized attachment found. Abort!
```

If it stopped halfway, most likely you deleted the images from disk but not updated WordPress' database. Simplest solution is to regenerate thumbnails then optimize again:
```bash
$ wp media regenerate --yes
$ wp image-optimize reset --yes
$ wp image-optimize run --limit=9999999
```

### Does running `wp image-optimize run` multiple times trigger multiple optimization for the same attachments?

No.

By default, boolean flags (meta fields) are given to attachments after optimization. This is to prevent re-optimizing an already optimized attachment. If you changed the image files (e.g.: resize / regenerate thumbnail), you must first reset their meta flags.

### Will the images look different after optimization?

Yes, a litte bit. This is lossy optimization. However, you won't notice the difference unless you have a trained eye for that.

See [spatie/image-optimizer](https://github.com/spatie/image-optimizer#which-tools-will-do-what)'s readme on binary options used.

### Why my GIFs stopped animating?

> When you upload an image using the media uploader, WordPress automatically creates several copies of that image in different sizes...When creating new image sizes for animated GIFs, **WordPress ends up saving only the first frame of the GIF**...
>
> --- [wpbeginner](http://www.wpbeginner.com/wp-tutorials/how-to-add-animated-gifs-in-wordpress/)

Luckily for you, Lasse M. Tvedt showed how to stop WordPress from resizing GIFs on [StackExchange](https://wordpress.stackexchange.com/a/229724).

### Does it has any limitation?

No, unlike other SaaS alternatives, this package runs on your server without any limitation on file sizes or monthly quota. Totally free of charge.

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

Consider writing a blog post, submitting pull requests, [donating](https://typist.tech/donation/) or [hiring me](https://typist.tech/contact/) instead.

## Support

Love `image-optimize-command`? Help me maintain it, a [donation here](https://typist.tech/donation/) can help with it.

### Why don't you hire me?

Ready to take freelance WordPress jobs. Contact me via the contact form [here](https://typist.tech/contact/) or, via email [info@typist.tech](mailto:info@typist.tech)

### Want to help in other way? Want to be a sponsor?

Contact: [Tang Rufus](mailto:tangrufus@gmail.com)

## Credits

[`image-optimize-command`](https://github.com/TypistTech/image-optimize-command) is a [Typist Tech](https://typist.tech) project and maintained by [Tang Rufus](https://twitter.com/TangRufus), freelance developer for [hire](https://www.typist.tech/contact/).

Full list of contributors can be found [here](https://github.com/TypistTech/image-optimize-command/graphs/contributors).

Special thanks to [Freek Van der Herten](https://github.com/freekmurze/) whose [spatie/image-optimizer](https://github.com/spatie/image-optimizer) package makes this project possible.

## Contributing

We appreciate you taking the initiative to contribute to this project.

Contributing isn’t limited to just code. We encourage you to contribute in the way that best fits your abilities, by writing tutorials, giving a demo at your local meetup, helping other users with their support questions, or revising our documentation.

For a more thorough introduction, [check out WP-CLI's guide to contributing](https://make.wordpress.org/cli/handbook/contributing/). This package follows those policy and guidelines.

### Reporting a bug

Think you’ve found a bug? We’d love for you to help us get it fixed.

Before you create a new issue, you should [search existing issues](https://github.com/typisttech/image-optimize-command/issues?q=label%3Abug%20) to see if there’s an existing resolution to it, or if it’s already been fixed in a newer version.

Once you’ve done a bit of searching and discovered there isn’t an open or fixed issue for your bug, please [create a new issue](https://github.com/typisttech/image-optimize-command/issues/new). Include as much detail as you can, and clear steps to reproduce if possible. For more guidance, [review our bug report documentation](https://make.wordpress.org/cli/handbook/bug-reports/).

### Creating a pull request

Want to contribute a new feature? Please first [open a new issue](https://github.com/typisttech/image-optimize-command/issues/new) to discuss whether the feature is a good fit for the project.

Once you've decided to commit the time to seeing your pull request through, [please follow our guidelines for creating a pull request](https://make.wordpress.org/cli/handbook/pull-requests/) to make sure it's a pleasant experience. See "[Setting up](https://make.wordpress.org/cli/handbook/pull-requests/#setting-up)" for details specific to working on this package locally.
