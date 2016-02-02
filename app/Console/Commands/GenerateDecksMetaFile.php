<?php

namespace App\Console\Commands;

use Counter;
use Storage;

use App\Game\Objects\DeckMeta;

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
            $deck = json_decode(Storage::get($file), true);
            $deck['file'] = str_replace('decks/', '', $file);
            
            unset($deck['cards']);
            
            return $deck;
        });
        
        $meta['main'] = new DeckMeta($decks->pull($decks->search(function ($deck) {
            return $deck['name'] == 'Cards Against Humanity';
        })));
        
        $meta['main']->add([
            'id' => 'main',
            'locales' => $this->determineLocalesPosition($main['locales'], array_get($meta, 'main.locales', [])),
            'source' => 'file',
            'type' => 'official',
        ]);
        
        $decks = $decks->map(function ($deck) use ($meta) {
            $deck_meta = $this->getMeta($deck, $meta);
            
            if ($deck_meta) {
                $deck_id = array_first(array_keys($deck_meta));
                $deck_meta = array_first($deck_meta);
                
                $deck['id'] = $deck_id;
                $deck['type'] = $deck_meta['type'];
                $deck['meta'] = $deck_meta;
            } else {
                $deck['id'] = $this->ask('Enter an ID for the deck <comment>' . $deck['name'] . '</comment>', strtolower($deck['slug']));
                $deck['type'] = $this->choice('Specify deck type.', ['o' => 'official', 'p' => 'pax', 3 => 'thirdParty'], 'o');
                $deck['meta'] = false;
            }
            
            return $deck;
        });
        
        // official
        $official = $decks->where('type', 'official')->values();
        $official = $this->sortSet($official, array_get($meta, 'official', []));
        
        // pax
        $pax = $decks->where('type', 'pax')->values();
        $pax = $this->sortSet($pax, array_get($meta, 'pax', []));
        
        // thirdParty
        $third = $decks->where('type', 'thirdParty')->values();
        $third = $this->sortSet($third, array_get($meta, 'thirdParty', []));
        
        // currated
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
        
        $count = count($locales);
        $ordered_locales = [];
        
        for ($i = 0; $i < $count; $i++) {
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
    
    protected function sortSet($set, $meta)
    {
        $count = $set->count();
        
        for ($i = 0; $i < $count; $i++) {
            foreach (Counter::loop($meta) as $id => $deck) {
                $this->info(' [<comment>' . Counter::i() . '</comment>] </info>' . $id . '<info> => </info>' . $deck['name'] . '<info>');
            }
            
            $deck['id'] = $id;
            
            if (! count($meta)) {
                $deck = $set[$i];
                $deck['index'] = $i;
                $set[$i] = $deck;
                
                $meta[$set[$i]['id']] = $set[$i];
            } else {
                $deck = $set[$i];
                $index = $this->ask('Deck index for <comment>' . $deck['name'] . '</comment>', count($meta));
                
                $set = $set->map(function ($deck) use ($index) {
                    if (isset($deck['index']) && $deck['index'] >= $index) {
                        $deck['index']++;
                    }
                    
                    return $deck;
                });
                
                $deck['index'] = $index;
                $set[$i] = $deck;
                
                $meta[$set[$i]['id']] = $set[$i];
            }
            
            $set = $set->sortBy('index');
            $meta = $set->filter(function ($deck) {
                return isset($deck['index']);
            })->keyBy('id')->all();
        }
        
        return $set;
    }
    
    protected function getMeta($deck, $meta)
    {
        $find_meta = function ($meta_deck) use ($deck) {
            return $meta_deck['file'] == $deck['file'];
        };
        
        if ($deck_meta = array_filter(array_get($meta, 'official', []), $find_meta)) {
            return $deck_meta;
        } elseif ($deck_meta = array_filter(array_get($meta, 'pax', []), $find_meta)) {
            return $deck_meta;
        } elseif ($deck_meta = array_filter(array_get($meta, 'thirdParty', []), $find_meta)) {
            return $deck_meta;
        }
        
        return false;
    }
}
