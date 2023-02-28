<?php

namespace App\Console\Commands;

use App\Models\Gallery;
use App\Service\GalleryService;
use Illuminate\Console\Command;

class GenerateThumbnail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gallery:generate-thumbnail';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '为相册中的图片生成缩略图';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $service = app()->make(GalleryService::class);

        $images = Gallery::select('url', 'thumbnail', 'id')->whereNull('thumbnail')->orWhere('thumbnail', '')->get();
        
        $images->each(fn ($item) => $item->update(['thumbnail' => $service->createTinyThumbnail($item->getRawOriginal('url'))]));
    }
}
