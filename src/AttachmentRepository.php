<?php

declare(strict_types=1);

namespace TypistTech\ImageOptimizeCommand;

use WP_Query;

class AttachmentRepository
{
    private const OPTIMIZED_META_KEY = '_typist_tech_image_optimized';

    /**
     * Get not yet optimized image attachments.
     *
     * @param int $num Number of image attachments to return.
     *
     * @return int[]
     */
    public static function take(int $num): array
    {
        $query = new WP_Query(
            [
                'post_type' => 'attachment',
                'post_mime_type' => 'image',
                'post_status' => 'any',
                'fields' => 'ids',
                'posts_per_page' => $num,
                'meta_query' => // phpcs:ignore WordPress.VIP.SlowDBQuery.slow_db_query_meta_query
                    [
                        [
                            'key' => self::OPTIMIZED_META_KEY,
                            'compare' => 'NOT EXISTS',
                        ],
                    ],
            ]
        );

        return $query->posts;
    }

    /**
     * Add optimized meta key to attachments.
     *
     * @param int[] ...$ids Attachment ids.
     *
     * @return void
     */
    public static function markAsOptimized(int ...$ids): void
    {
        array_map(function (int $id): void {
            add_post_meta($id, self::OPTIMIZED_META_KEY, true, true);
        }, $ids);
    }

    /**
     * Remove optimized meta flags from all attachments
     *
     * @return void
     */
    public static function markAllAsUnoptimized(): void
    {
        global $wpdb;

        $wpdb->query( // phpcs:ignore WordPress.VIP.DirectDatabaseQuery
            $wpdb->prepare(
                "DELETE FROM $wpdb->postmeta WHERE meta_key = %s;",
                self::OPTIMIZED_META_KEY
            )
        );
    }
}
