<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/

Route::group(['middleware' => ['web']], function () {
    Route::get( '/',    ['as' => 'home', 'uses' => 'HomeController@index'   ]);
    Route::post('join', ['as' => 'join', 'uses' => 'HomeController@join'    ]);
    
    Route::group(['namespace' => 'Auth'], function () {
        Route::get( 'sign/in',  ['as' => 'login',           'uses' => 'AuthController@loginForm'    ]);
        Route::post('sign/in',  ['as' => 'login.do',        'uses' => 'AuthController@login'        ]);
        Route::get( 'sign/up',  ['as' => 'signUp',          'uses' => 'AuthController@signUpForm'   ]);
        Route::post('sign/up',  ['as' => 'signUp.do',       'uses' => 'AuthController@signUp'       ]);
        Route::post('/',        ['as' => 'loginOrSignUp',   'uses' => 'AuthController@loginOrSignUp']);
        Route::get( 'sign/out', ['as' => 'logout',          'uses' => 'AuthController@logout'       ]);
        
        Route::get( 'forgot/password',          ['as' => 'pw.forgot',   'uses' => 'PasswordController@showLinkRequestForm'  ]);
        Route::post('forgot/password',          ['as' => 'pw.email',    'uses' => 'PasswordController@sendResetLinkEmail'   ]);
        Route::get( 'reset/password/{token}',   ['as' => 'pw.reset',    'uses' => 'PasswordController@showResetForm'        ]);
        Route::post('reset/password',           ['as' => 'pw.reset.do', 'uses' => 'PasswordController@reset'                ]);
    });
});

Route::group(['middleware' => ['web', 'has.username']], function () {
    Route::get('lobby', ['as' => 'lobby', 'uses' => 'GameController@lobby']);
    
    Route::get( 'game/create', ['as' => 'room.create',  'uses' => 'GameController@create'   ]);
    Route::post('game/create', ['as' => 'room.store',   'uses' => 'GameController@store'    ]);
    Route::get( 'game/{game}', ['as' => 'room.show',    'uses' => 'GameController@show'     ]);
});

Route::group(['middleware' => ['web', 'auth']], function () {
    Route::get( 'my/account', ['as' => 'account.edit',      'uses' => 'AccountController@edit'  ]);
    Route::post('my/account', ['as' => 'account.update',    'uses' => 'AccountController@update']);
});

Route::get('sql', function() {
    $json = Storage::get('decks/cards-against-humanity.json');
    $main = json_decode($json);
    
    
    $json = Storage::get('decks/retail.json');
    $retail = json_decode($json);
    
    $main->cards->calls = array_merge($main->cards->calls, $retail->cards->calls);
    $main->cards->responses = array_merge($main->cards->responses, $retail->cards->responses);
    
    $main->calls = count($main->cards->calls);
    $main->responses = count($main->cards->responses);
    
    $sortCards = function ($a, $b) {
        $a = (array) $a;
        $b = (array) $b;
        
        if ($a['text'] == $b['text']) {
            return 0;
        }
        
        return $a['text'] < $b['text'] ? -1 : 1;
    };
    
    foreach ($main->cards->calls as &$card) {
        $new = [];
        
        $new['text'] = $card->text;
        if (isset($card->ca)) {
            $new['ca'] = $card->ca;
            $new['uk'] = $card->uk;
            $new['us'] = $card->us;
        } else {
            $new['ca'] = true;
            $new['uk'] = true;
            $new['us'] = true;
        }
        $new['draw'] = $card->draw;
        $new['pick'] = $card->pick;
        
        $card = $new;
    }
    
    foreach ($main->cards->responses as &$card) {
        $new = [];
        
        $new['text'] = $card->text;
        if (isset($card->ca)) {
            $new['ca'] = $card->ca;
            $new['uk'] = $card->uk;
            $new['us'] = $card->us;
        } else {
            $new['ca'] = true;
            $new['uk'] = true;
            $new['us'] = true;
        }
        
        $card = $new;
    }
    
    usort($main->cards->calls, $sortCards);
    usort($main->cards->responses, $sortCards);
    
    $filename = str_slug(html_entity_decode($main->name)) . '.json';
    
    Storage::put('decks/' . $filename, json_encode($main, JSON_PRETTY_PRINT));
    
    return response()->json($main);
    
    die();
    
    // Decks we need to grab from the xyzzy db
    $slugs = [
        1 => "&#x2744;",
        23 => "HBS",
    ];
    
    class Deck {
        public $name;
        public $description;
        public $version;
        public $slug;
        public $calls;
        public $responses;
        public $cards;
        
        public function __construct($deck)
        {
            foreach(get_object_vars($this) as $prop => $v) {
                $this->$prop = $deck->$prop;
            }
        }
    }
    
    // Grabbing decks fro, xyzzy
    DB::setDefaultConnection('xyzzy');

    //$set = DB::table('card_sets')->select('name', 'description', 'watermark as slug')->where('watermark', 'HOCAH')->orderBy('name', 'asc')->first();
    $set = (object) [];
    $set->name = 'RETAIL';
    $set->description = 'This expansion for Cards Against Humanity is an Indiegogo reward for people who backed Season 3 of TableTop.';
    $set->slug = 'TT';
    
        $getCards = function ($card) {
            $card->text = preg_replace('/_+/', '_', $card->text);
            $card->text = preg_replace('/<span\s+class="cardnum">.*?<\/span>/', '', $card->text);
            $card->text = str_replace(['<br/>', '<i>', '</i>'], ['<br>', '<em>', '</em>'], $card->text);
            $card->text = str_replace('<span class="card_number">&uarr;</span>', '<i class="icn --arrow-up"></i>', $card->text);
            $card->text = str_replace('<span class="card_number">&rarr;</span>', '<i class="icn --arrow-right"></i>', $card->text);
            $card->text = str_replace('<span class="card_number">&darr;</span>', '<i class="icn --arrow-down"></i>', $card->text);
            $card->text = str_replace('<span class="card_number">&larr;</span>', '<i class="icn --arrow-left"></i>', $card->text);
            $card->text = str_replace('<span class="card_number">B</span>', '<i class="icn --b"></i>', $card->text);
            $card->text = str_replace('<span class="card_number">B</span>', '<i class="icn --a"></i>', $card->text);
            $card->text = trim($card->text);
            
            if (str_contains($card->text, '<br>') || str_contains($card->text, '<em>') || str_contains($card->text, '<i class=')) {
                return $card;
            }
        };

        if ($set->name == 'First Version') {
            $set->name = 'Cards Against Humanity';
        }

        $set->cards = new stdClass;
        $set->special = new stdClass;
        $set->special->calls = [];
        $set->special->responses = [];
//        $set->special->calls = array_values(array_filter(array_map($getCards, DB::table('black_cards')->select('text', 'pick')->where('watermark', $set->slug)->get()), function ($card) { return ! is_null($card); }));
//        $set->special->responses = array_values(array_filter(array_map($getCards, DB::table('white_cards')->select('text')->where('watermark', $set->slug)->get()), function ($card) { return ! is_null($card); }));
        
        $set->special_calls = count($set->special->calls);
        $set->special_responses = count($set->special->responses);

    DB::setDefaultConnection(config('database.default'));
    
    // https://docs.google.com/spreadsheets/d/1lsy7lIwBe-DWOi2PALZPf5DgXHx9MEvKfRw1GaWQkzg/edit#gid=10
    
    //
    // Main Deck - white
    //
    
    $crawler = new Symfony\Component\DomCrawler\Crawler(file_get_contents(storage_path('CAH/CAH Expansions - White.html')));
    $crawler = $crawler->filter('table tbody tr')->reduce(function ($node, $i) {
        return $i > 704 && $i < 710;
    });
    
    $responses = $crawler->each(function($node, $i) {
        $node = $node->filter('td');
        
        if ($node->count() > 1 && $node->eq(1)->text() && $node->eq(1)->text()) {
            return [
                'text' => $node->eq(1)->text(),
            ];
        }
    });
    
    //
    // Main Deck - black
    //
    $crawler = new Symfony\Component\DomCrawler\Crawler(file_get_contents(storage_path('CAH/CAH Expansions - Black.html')));
    $crawler = $crawler->filter('table tbody tr')->reduce(function ($node, $i) {
        return $i > 272 && $i < 277;
    });
    
    $calls = $crawler->each(function($node, $i) {
        $node = $node->filter('td');
        
        if ($node->count() > 2 && $node->eq(1)->text() && $node->eq(1)->text()) {
            return [
                'text' => $node->eq(1)->text(),
                'special' => $node->eq(2)->text(),
            ];
        }
    });
    
    $getCards = function ($card) {
        $card['text'] = preg_replace('/_+/', '_', $card['text']);
        $card['text'] = str_replace(['<br/>', '<i>', '</i>'], ['<br>', '<em>', '</em>'], $card['text']);
        $card['text'] = htmlentities($card['text']);
        $card['text'] = str_replace('A Mexican child trapped inside of a burrito.', 'Oreos for dinner.', $card['text']);
        $card['text'] = str_replace(['&nbsp;', '&rsquo;'], [' ', "'"], $card['text']);
        $card['text'] = trim($card['text']);
        
        if (isset($card['special'])) {
            $card['draw'] = 0;
            $card['pick'] = 1;
            
            if (preg_match('/(?:DRAW\s+(\d+),\s)?PICK\s+(\d+)/i', $card['special'], $matches)) {
                $card['pick'] = (int) $matches[2];
                
                if ($matches[1]) {
                    $card['draw'] = (int) $matches[1];
                }
            }
            
            unset($card['special']);
        }
        
        return (object) $card;
    };
    
    $sortCards = function ($a, $b) {
        if ($a->text == $b->text) {
            return 0;
        }
        
        return $a->text < $b->text ? -1 : 1;
    };
    
    $cardset = function($set) use ($getCards, $sortCards) {
        $set = array_filter($set);
        $cards = array_map($getCards, $set);
        usort($cards, $sortCards);
        
        return $cards;
    };
    
    $set->cards->calls = $cardset($calls);
    $set->cards->responses = $cardset($responses);
    $set->calls = count($set->cards->calls);
    $set->responses = count($set->cards->responses);
    if ($set->special_calls > 0 || $set->special_responses > 0) {
        if ($set->special_calls > 0) {
            foreach ($set->special->calls as $call) {
                $search = preg_replace('/(<br>)+/', ' ', $call->text);
                $search = strip_tags($search);
                
                $index = collect($set->cards->calls)->search(function ($call) use ($search) {
                    return $call->text == $search;
                });
                
                if ($index !== false) {
                    $set->cards->calls[$index]->text = $call->text;
                }
            }
        }
        
        if ($set->responses > 0) {
            foreach ($set->special->responses as $response) {
                $search = preg_replace('/(<br>)+/', ' ', $response->text);
                $search = strip_tags($search);
                
                $index = collect($set->cards->responses)->search(function ($response) use ($search) {
                    return $response->text == $search;
                });
                
                if ($index) {
                    $set->cards->responses[$index]->text = $response->text;
                }
            }
        }
    }
    
    $set->version = "1.0";
    
    $filename = str_slug(html_entity_decode($set->name)) . '.json';
    $special = [$set->special_calls, $set->special_responses, $set->special];
    unset($set->special, $set->special_calls, $set->special_responses);
    
    $set = new Deck($set);
    
    Storage::put('decks/' . $filename, json_encode($set, JSON_PRETTY_PRINT));
    
    $set->stored = 'decks/' . $filename;
    $set->special = $special;
    
    return response()->json($set);
    
    //return response()->json($sets);
});
