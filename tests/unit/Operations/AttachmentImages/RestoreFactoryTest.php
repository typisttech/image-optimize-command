<?php
declare(strict_types=1);

namespace TypistTech\ImageOptimizeCommand\Operations\AttachmentImages;

use Codeception\Test\Unit;
use Mockery;
use Symfony\Component\Filesystem\Filesystem;
use TypistTech\ImageOptimizeCommand\CLI\Logger;
use TypistTech\ImageOptimizeCommand\Repositories\AttachmentRepository;
use WP_Mock;

class RestoreFactoryTest extends Unit
{
    public function testCreate()
    {
        $repo = Mockery::spy(AttachmentRepository::class);
        $filesystem = Mockery::spy(Filesystem::class);
        $logger = Mockery::spy(Logger::class);

        $expected = new Restore($repo, $filesystem, $logger);

        WP_Mock::userFunction(__NAMESPACE__ . '\apply_filters')
               ->with(
                   RestoreFactory::HOOK,
                   Mockery::type(Restore::class),
                   $repo,
                   $filesystem,
                   $logger
               )
               ->andReturnUsing(function ($_hook, $arg) {
                   return $arg;
               })
               ->once();

        $actual = RestoreFactory::create($repo, $filesystem, $logger);

        $this->assertEquals($expected, $actual);
    }
}
