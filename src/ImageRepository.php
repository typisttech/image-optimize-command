<?php

declare(strict_types=1);

namespace TypistTech\ImageOptimizeCommand;

class ImageRepository
{
    /**
     * Get all(original and chopped) paths for attachments.
     *
     * @param int[] ...$ids Attachment ids.
     *
     * @return string[]
     */
    public static function pathsFor(int ...$ids): array
    {
        $fileNames = array_map(function (int $id): array {
            return self::pathsForSingle($id);
        }, $ids);

        return array_merge(...$fileNames);
    }

    /**
     * Get all(original and chopped) paths for a single attachment.
     *
     * @see https://wordpress.stackexchange.com/questions/182477/wp-get-attachment-image-src-and-server-path
     *
     * @param int $id Attachment id.
     *
     * @return string[]
     */
    private static function pathsForSingle(int $id): array
    {
        $metadata = wp_get_attachment_metadata($id);
        $originalFilePath = get_attached_file($id, true);

        $sizes = array_keys(
            wp_list_pluck($metadata['sizes'] ?? [], 'file')
        );

        $choppedFilePaths = array_map(function (string $size) use ($id, $originalFilePath): string {
            $info = image_get_intermediate_size($id, $size);

            return str_replace(wp_basename($originalFilePath), $info['file'], $originalFilePath);
        }, $sizes);

        return array_merge($choppedFilePaths, [
            $originalFilePath,
        ]);
    }
}
