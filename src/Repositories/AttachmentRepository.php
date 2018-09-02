<?php

declare(strict_types=1);

namespace TypistTech\ImageOptimizeCommand\Repositories;

use WP_Query;

class AttachmentRepository
{
    protected const OPTIMIZED_META_KEY = '_typist_tech_image_optimized';
    protected const OPTIMIZED_META_VALUE = true;

    /**
     * Get not yet optimized image attachments.
     *
     * @param int $num Number of image attachments to return.
     *
     * @return int[]
     */
    public function take(int $num): array
    {
        $query = new WP_Query(
            [
                'post_type' => 'attachment',
                'post_mime_type' => 'image',
                'post_status' => 'any',
                'fields' => 'ids',
                'posts_per_page' => $num,
                'meta_query' =>
                    [
                        [
                            'key' => static::OPTIMIZED_META_KEY,
                            'compare' => 'NOT EXISTS',
                        ],
                    ],
            ]
        ); // WPCS: slow query ok.

        return $query->posts;
    }

    /**
     * Add optimized meta key to attachments.
     *
     * @param int|int[] ...$ids Attachment ids.
     *
     * @return void
     */
    public function markAsOptimized(int ...$ids): void
    {
        array_map(function (int $id): void {
            add_post_meta($id, static::OPTIMIZED_META_KEY, static::OPTIMIZED_META_VALUE, true);
        }, $ids);
    }

    /**
     * Remove optimized meta key from attachments.
     *
     * @param int|int[] ...$ids Attachment ids.
     *
     * @return void
     */
    public function markAsNonOptimized(int ...$ids): void
    {
        array_map(function (int $id): void {
            delete_post_meta($id, static::OPTIMIZED_META_KEY, static::OPTIMIZED_META_VALUE);
        }, $ids);
    }

    /**
     * Remove optimized meta flags from all attachments.
     *
     * TODO: To keep or not to keep?
     *
     * @return void
     */
    public function markAllAsUnoptimized(): void
    {
        global $wpdb;

        $wpdb->query(
            $wpdb->prepare(
                "DELETE FROM $wpdb->postmeta WHERE meta_key = %s;",
                static::OPTIMIZED_META_KEY
            )
        ); // WPCS: cache ok, db call ok.
    }

    /**
     * Get all(original and chopped) paths for attachments.
     *
     * @param int|int[] ...$ids Attachment ids.
     *
     * @return string[]
     */
    public function getPaths(int ...$ids): array
    {
        $paths = array_map(function (int $id): array {
            return $this->getPathsSingle($id);
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
    protected function getPathsSingle(int $id): array
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
    public function getFullSizedPaths(int ...$ids): array
    {
        $paths = array_map(function (int $id) {
            return get_attached_file($id, true);
        }, $ids);

        return array_filter($paths);
    }
}
