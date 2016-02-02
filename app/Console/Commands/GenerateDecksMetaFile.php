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
        
        $decks = Storage::files('decks');
        
        $decks = collect($decks)->map(function ($file, $key) {
            $deck = json_decode(Storage::get($file), true);
            $deck['file'] = str_replace('decks/', '', $file);
            
            unset($deck['cards']);
            
            return $deck;
        });
        
        $main = $decks->pull($decks->search(function ($deck) {
            return $deck['name'] == 'Cards Against Humanity';
        }));
        
        $main = DeckMeta::make($main);
        
        $meta['main'] = $main->add([
            'id' => 'main',
            'locales' => $this->determineLocalesPosition($main->locales, array_get($meta, 'main.locales', [])),
            'source' => 'file',
            'type' => 'official',
        ])->toArray();
        
        $decks = $decks->map(function ($deck) use ($meta) {
            $deck_meta = $this->getMeta($deck, $meta);
            
            if ($deck_meta) {
                $deck_id = head(array_keys($deck_meta));
                $deck_meta = head($deck_meta);
                
                $deck['id'] = $deck_id;
                $deck['type'] = $deck_meta['type'];
                $deck['meta'] = $deck_meta;
            } else {
                $deck['id'] = $this->ask('Enter an ID for the deck <comment>' . $deck['name'] . '</comment>', str_slug(strtolower($deck['slug']), '_'));
                $deck['type'] = $this->anticipate('Specify deck type.', ['official' => 'o', 'pax' => 'p', 'thirdParty' => 3], 'official');
                $deck['meta'] = false;
            }
            
            return $deck;
        });
        
        // file based
        
        foreach ($decks->pluck('type')->unique() as $type) {
            $set = $decks->where('type', $type)->values();
            $set = $this->sortSet($set, array_get($meta, $type, []));
            $set = $set->keyBy('id');
            $set = $set->map(function ($deck) {
                return DeckMeta::make($deck)->toArray();
            });
            
            $meta[$type] = $set;
        }
        
        // currated / cardcast
        
        // save
        
        Storage::put('decks.json', str_finish(json_encode($meta, JSON_PRETTY_PRINT), PHP_EOL));
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
            $suggested_index = count($meta);
            
            foreach (Counter::loop($meta) as $id => $deck) {
                $this->info(' [<comment>' . Counter::i() . '</comment>] </info>' . $id . '<info> => </info>' . $deck['name'] . '<info>');
                
                $meta[$id]['index'] = Counter::i();
                
                if ($deck['name'] == $set[$i]['name']) {
                    $suggested_index = Counter::i();
                }
            }
            
            $deck = $set[$i];
            
            if (! count($meta)) {
                $index = $i;
            } else {
                $index = $this->ask('Deck index for <comment>' . $deck['id'] . ' => ' . $deck['name'] . '</comment>', $suggested_index);
            }
            
            $set = $set->map(function ($deck) use ($index) {
                if (isset($deck['index']) && $deck['index'] >= $index) {
                    $deck['index']++;
                }
                
                return $deck;
            });
            
            $deck['index'] = $index;
            $set[$i] = $deck;
            
            $id = $set[$i]['id'];
            
            $meta = collect($meta)->map(function ($deck, $meta_id) use ($index, $id) {
                if ($meta_id != $id && isset($deck['index']) && $deck['index'] >= $index) {
                    $deck['index']++;
                }
                
                return $deck;
            })->all();
            
            $meta[$id] = $deck;
            
            $meta = collect($meta)->sortBy('index')->all();
        }
        
        return $set->sortBy('index');
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
