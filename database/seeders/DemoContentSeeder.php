<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Loads realistic demo catalogue content (destinations, hotels, flights,
 * packages and activities) from a plain SQL file.
 */
class DemoContentSeeder extends Seeder
{
    public function run(): void
    {
        $path = database_path('seeders/data/demo_content.sql');
        if (!is_file($path)) {
            $this->command?->warn("Demo content file not found: {$path}");
            return;
        }

        $lines = preg_split('/\r?\n/', (string) file_get_contents($path)) ?: [];
        $lines = array_filter($lines, fn ($line) => !str_starts_with(ltrim($line, "\xEF\xBB\xBF"), '--'));
        $clean = preg_replace('/^\xEF\xBB\xBF/', '', implode("\n", $lines));

        foreach (array_filter(array_map('trim', explode(';', $clean))) as $statement) {
            DB::unprepared($statement);
        }
    }
}
