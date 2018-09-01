<?php

declare(strict_types=1);

namespace TypistTech\ImageOptimizeCommand\Repositories;

class AttachmentImagePathRepository
{
    /**
     * Get all(original and chopped) paths for attachments.
     *
     * @param int|int[] ...$ids Attachment ids.
     *
     * @return string[]
     */
    public function get(int ...$ids): array
    {
        $paths = array_map(function (int $id): array {
            return $this->getSingle($id);
        }, $ids);

        return array_merge(...$paths);
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
    protected function getSingle(int $id): array
    {
        $metadata = wp_get_attachment_metadata($id);
        $originalFilePath = get_attached_file($id, true);

        if (empty($originalFilePath)) {
            return [];
        }

        $sizes = array_keys(
            wp_list_pluck($metadata['sizes'] ?? [], 'file')
        );

        $choppedFilePaths = array_map(function (string $size) use ($id, $originalFilePath): string {
            $info = image_get_intermediate_size($id, $size);

            return str_replace(wp_basename($originalFilePath), $info['file'], $originalFilePath);
        }, $sizes);

        return array_filter(array_merge($choppedFilePaths, [
            $originalFilePath,
        ]));
    }

    /**
     * Get all original paths for attachments.
     *
     * @param int|int[] ...$ids Attachment ids.
     *
     * @return string[]
     */
    public function getFullSized(int ...$ids): array
    {
        $paths = array_map(function (int $id) {
            return get_attached_file($id, true);
        }, $ids);

        return array_filter($paths);
    }
}
