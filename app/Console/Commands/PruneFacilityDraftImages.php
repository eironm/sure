<?php

namespace App\Console\Commands;

use App\Models\FacilityImage;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

/**
 * Removes orphaned facility gallery drafts.
 *
 * When an admin uploads photos on the "Create Facility" screen (instant upload)
 * but never submits the form, the images stay attached to a draft_token with a
 * null facility_id. This command deletes those stragglers and their files.
 *
 * Usage:
 *   php artisan facilities:prune-draft-images
 *   php artisan facilities:prune-draft-images --hours=48
 */
class PruneFacilityDraftImages extends Command
{
    protected $signature = 'facilities:prune-draft-images {--hours=24 : Age threshold in hours}';

    protected $description = 'Delete orphaned facility draft images that were never attached to a facility';

    public function handle(): int
    {
        $cutoff = now()->subHours((int) $this->option('hours'));

        $orphans = FacilityImage::whereNull('facility_id')
            ->whereNotNull('draft_token')
            ->where('created_at', '<', $cutoff)
            ->get();

        $count = 0;
        foreach ($orphans as $image) {
            $this->deleteFile($image->image_path);
            $image->delete();
            $count++;
        }

        $this->info("Pruned {$count} orphaned facility draft image(s).");

        return self::SUCCESS;
    }

    protected function deleteFile(?string $path): void
    {
        if (!$path) {
            return;
        }

        if (str_starts_with($path, 'uploads/') && file_exists(public_path($path))) {
            @unlink(public_path($path));
        } elseif (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}
