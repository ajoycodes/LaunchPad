<?php

namespace App\Models\Concerns;

/**
 * Provides a deterministic colored "initials tile" used as a fallback
 * whenever a user has no avatar or a product has no logo. The color and
 * letters are derived from the model's name, so the same record always
 * renders the same tile.
 */
trait HasTileAvatar
{
    /**
     * A curated palette of accessible, white-text-friendly tile colors.
     *
     * @var list<string>
     */
    private const TILE_PALETTE = [
        '#FF6154', '#3B82F6', '#10B981', '#8B5CF6', '#F59E0B',
        '#EC4899', '#06B6D4', '#EF4444', '#6366F1', '#14B8A6',
        '#F97316', '#A855F7', '#0EA5E9', '#D946EF', '#22C55E',
    ];

    /**
     * Up to two uppercase initials derived from the model name.
     * "Alice Chen" -> "AC", camelCase "ShipFast" -> "SF", "admin" -> "AD".
     */
    public function tileInitials(): string
    {
        $source = trim((string) ($this->name ?? ''));

        if ($source === '') {
            return '?';
        }

        $parts = preg_split('/\s+/', $source) ?: [];

        if (count($parts) >= 2) {
            return strtoupper(mb_substr($parts[0], 0, 1) . mb_substr((string) end($parts), 0, 1));
        }

        // Single token: prefer internal capitals (camelCase product names).
        if (preg_match_all('/\p{Lu}/u', $source, $caps) && count($caps[0]) >= 2) {
            return $caps[0][0] . $caps[0][1];
        }

        return strtoupper(mb_substr($source, 0, 2));
    }

    /**
     * A stable hex color picked from the palette based on the model name.
     */
    public function tileColor(): string
    {
        $key = (string) ($this->name ?? $this->getKey());

        return self::TILE_PALETTE[crc32($key) % count(self::TILE_PALETTE)];
    }
}
