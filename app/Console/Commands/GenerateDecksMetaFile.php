<?php

namespace App\Console\Commands;

use Storage;

use Illuminate\Console\Command;

class GenerateDecksMetaFile extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'decks:meta';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create the decks meta file.';

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
     * @return mixed
     */
    public function handle()
    {
        if (Storage::exists('decks.json')) {
            $meta = json_decode(Storage::get('decks.json'), true);
        } else {
            $meta = [];
        }
        
        $decks = collect(Storage::files('decks'))->map(function ($file) {
            $deck = [
                'file' => str_replace('decks/', '', $file),
                'meta' => json_decode(Storage::get($file), true),
            ];
            
            unset($deck['meta']['cards']);
            
            return $deck;
        });
        
        $main = $decks->pull($decks->search(function ($deck) {
            return array_get($deck, 'meta.name') == 'Cards Against Humanity';
        }));
        
        $locales = $this->determineLocalesPosition($main['meta']['locales'], array_get($meta, 'main.locales', []));
        
        $meta['main'] = [
            'name' => $main['meta']['name'],
            'file' => $main['file'],
            'description' => $main['meta']['description'],
            'locales' => $locales,
            'calls' => $main['meta']['calls'],
            'responses' => $main['meta']['responses'],
        ];
    }
    
    protected function determineLocalesPosition($locales, $meta_locales)
    {
        $l1 = $locales;
        $l2 = $meta_locales;
        sort($l1);
        sort($l2);
        
        if ($l1 == $l2) {
            return $meta_locales;
        }
        
        $iterations = count($locales);
        $ordered_locales = [];
        
        for ($i = 0; $i < $iterations; $i++) {
            if (count($locales) > 1) { 
                $key = $this->choice('Select the locale for index ' . $i, $locales);
                $ordered_locales[$key] = $locales[$key];
            } else {
                $ordered_locales = array_merge($ordered_locales, $locales);
            }
            
            unset($locales[$key]);
        }
        
        return $ordered_locales;
    }
}
