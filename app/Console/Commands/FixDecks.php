<?php

namespace App\Console\Commands;

use Storage;

use Illuminate\Console\Command;

class FixDecks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'decks:fix';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Analyse and fix the deck json files.';
    
    protected $flags = [];

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
        $decks = collect(Storage::files('decks'))->map(function ($file) {
            $deck = [
                'file' => $file,
                'meta' => json_decode(Storage::get($file)),
            ];
            
            return (object) $deck;
        });
        
        $decks->each(function ($_deck) {
            $file = $_deck->file;
            $deck = $_deck->meta;
            
            $this->info('Checking deck <comment>' . $deck->name . '</comment>');
            $flags[$deck->name] = [];
            
            $this->checkBroken($deck, $deck->cards->calls);
            $this->checkBroken($deck, $deck->cards->responses);
            
            sort($deck->cards->calls);
            sort($deck->cards->responses);
            
            $deck->description = preg_replace('/(?<!href=")((?:https?:\/\/(?:www\.)?|(?<!http:\/\/)www\.)([^\s]+))/', '<a href="$1" target="_blank">$2</a>', $deck->description);
            
            Storage::put($file, str_finish(json_encode($deck, JSON_PRETTY_PRINT), PHP_EOL));
        });
        
        $this->flags = array_filter($this->flags);
        
        var_dump($this->flags);
        
        $this->line('Done!');
    }
    
    protected function checkBroken($deck, &$cards)
    {
        foreach ($cards as $key => &$card) {
            if (str_contains(strtolower($card->text), 'cards against humanity')) {
                if ($this->confirm('The card <comment>' . html_entity_decode($card->text) . '</comment> may be broken, do you wish to remove it? [y|N]', false)) {
                    unset($cards[$key]);
                } else {
                    $this->flags[$deck->name][] = [
                        'reason' => 'possible broken.',
                        'type' => isset($card->pick) ? 'call' : 'response',
                        'card' => $card->text,
                    ];
                }
            }
            
            if (! ends_with($card->text, '.') && ! ends_with($card->text, '!') && ! ends_with($card->text, '?') && ! ends_with($card->text, '%') && ! ends_with($card->text, '.&quot;') && ! ends_with($card->text, '!&quot;') && ! ends_with($card->text, '?&quot;')) {
                if ($this->confirm('Missing punctuation on card <comment>' . html_entity_decode($card->text) . '</comment>. Do you wish to add "."? [y|N]', true)) {
                    $card->text .= '.';
                } elseif ($this->confirm('Add punctuation manually? [y|N]')) {
                    $card->text .= $this->ask('End card with');
                } else {
                    $this->flags[$deck->name][] = [
                        'reason' => 'punctuation.',
                        'type' => isset($card->pick) ? 'call' : 'response',
                        'card' => $card->text,
                    ];
                }
            }
            
            $card->text = str_replace(['&ldquo;', '&rdquo;'], ['&quot;', '&quot;'], $card->text);
        }
    }
}
