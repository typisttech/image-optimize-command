<?php

declare(strict_types=1);

namespace TypistTech\ImageOptimizeCommand\Repositories;

use Codeception\Test\Unit;
use WP_Mock;

class AttachmentRepositoryTest extends Unit
{
    /**
     * @var \TypistTech\ImageOptimizeCommand\UnitTester
     */
    protected $tester;

    public function testMarkAsOptimized()
    {
        WP_Mock::userFunction(__NAMESPACE__ . '\add_post_meta')
            ->with(111, '_typist_tech_image_optimized', true, true)
            ->once();
        WP_Mock::userFunction(__NAMESPACE__ . '\add_post_meta')
               ->with(222, '_typist_tech_image_optimized', true, true)
               ->once();

        $repo = new AttachmentRepository();

        $repo->markAsOptimized(111, 222);
    }

    public function testMarkAsNonOptimized()
    {
        WP_Mock::userFunction(__NAMESPACE__ . '\delete_post_meta')
               ->with(111, '_typist_tech_image_optimized', true)
               ->once();
        WP_Mock::userFunction(__NAMESPACE__ . '\delete_post_meta')
               ->with(222, '_typist_tech_image_optimized', true)
               ->once();

        $repo = new AttachmentRepository();

        $repo->markAsNonOptimized(111, 222);
    }
}
