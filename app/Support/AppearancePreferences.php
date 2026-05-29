<?php

namespace App\Support;

class AppearancePreferences
{
    public const THEME_IMPASUGONG = 'impasugong';

    public const THEME_GREEN = 'green';

    public const MODE_LIGHT = 'light';

    public const MODE_DARK = 'dark';

    public const MODE_SYSTEM = 'system';

    /** @var list<string> */
    public const THEMES = [
        self::THEME_IMPASUGONG,
        self::THEME_GREEN,
    ];

    /** @var list<string> */
    public const MODES = [
        self::MODE_LIGHT,
        self::MODE_DARK,
        self::MODE_SYSTEM,
    ];

    /**
     * @param  array<string, mixed>|null  $raw
     * @return array{theme: string, mode: string}
     */
    public static function normalize(?array $raw): array
    {
        $theme = is_array($raw) ? ($raw['theme'] ?? null) : null;
        $mode = is_array($raw) ? ($raw['mode'] ?? null) : null;

        if (! is_string($theme) || ! in_array($theme, self::THEMES, true)) {
            $theme = self::THEME_IMPASUGONG;
        }

        if (! is_string($mode) || ! in_array($mode, self::MODES, true)) {
            $mode = self::MODE_LIGHT;
        }

        return [
            'theme' => $theme,
            'mode' => $mode,
        ];
    }
}
