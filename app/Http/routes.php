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
    // Decks we need to grab from the xyzzy db
    $slugs = [
        0 => null,
        1 => "&#x2744;",
        5 => "13PAX",
        6 => "14PAX",
        7 => "90s",
        13 => "BOX",
        23 => "HBS",
        30 => "Panel",
        31 => "PAX A",
        32 => "PAX B",
        33 => "PAX C",
        44 => "X1",
        45 => "X2",
        46 => "X3",
        47 => "X4",
    ];
    
    // 15 => "CAN",
    
    // Grabbing decks fro, xyzzy
//    DB::setDefaultConnection('xyzzy');
//
//    $sets = DB::table('card_sets')->select('name', 'description', 'watermark as slug')->whereIn('watermark', $slugs)->orWhere(function($query) { $query->whereNull('watermark'); })->orderBy('name', 'asc')->get();
//    $sets = array_map(function($set) {
//        $getCards = function ($card) {
//            $card->text = preg_replace('/_+/', '_', $card->text);
//            $card->text = preg_replace('/<span\s+class="cardnum">.*?<\/span>/', '', $card->text);
//            $card->text = str_replace(['<br/>', '<i>', '</i>'], ['<br>', '<em>', '</em>'], $card->text);
//            $card->text = str_replace('<span class="card_number">&uarr;</span>', '<i class="icn --arrow-up"></i>', $card->text);
//            $card->text = str_replace('<span class="card_number">&rarr;</span>', '<i class="icn --arrow-right"></i>', $card->text);
//            $card->text = str_replace('<span class="card_number">&darr;</span>', '<i class="icn --arrow-down"></i>', $card->text);
//            $card->text = str_replace('<span class="card_number">&larr;</span>', '<i class="icn --arrow-left"></i>', $card->text);
//            $card->text = str_replace('<span class="card_number">B</span>', '<i class="icn --b"></i>', $card->text);
//            $card->text = str_replace('<span class="card_number">B</span>', '<i class="icn --a"></i>', $card->text);
//
//            return $card;
//        };
//
//        if ($set->name == 'First Version') {
//            $set->name = 'Cards Against Humanity';
//        }
//
//        $set->cards = new stdClass;
//        $set->cards->calls = array_map($getCards, DB::table('black_cards')->select('text', 'pick')->where('watermark', $set->slug)->get());
//        $set->cards->responses = array_map($getCards, DB::table('white_cards')->select('text')->where('watermark', $set->slug)->get());
//
//        $set->calls = count($set->cards->calls);
//        $set->responses = count($set->cards->responses);
//
//        return $set;
//    }, $sets);
//
//    foreach ($sets as $set) {
//        $filename = str_slug(html_entity_decode($set->name)) . '.json';
//    }
//
//    DB::setDefaultConnection(config('database.default'));
    
    // https://docs.google.com/spreadsheets/d/1lsy7lIwBe-DWOi2PALZPf5DgXHx9MEvKfRw1GaWQkzg/edit#gid=10
    
    //
    // Main Deck - white
    //
    
    $crawler = new Symfony\Component\DomCrawler\Crawler(file_get_contents(storage_path('Cards Against Humanity/CAH Main Deck - White.html')));
    $crawler = $crawler->filter('table tbody tr')->reduce(function ($node, $i) {
        return $i > 5;
    });
    
    $cards = $crawler->each(function($node, $i) {
        $node = $node->filter('td');
        
        if ($node->count() > 11 && $node->eq(1)->text() && $node->eq(1)->text() != 'TOTALS') {
            return [
                'text' => $node->eq(1)->text(),
                'uk' => $node->eq(9)->text(),
                'ca' => $node->eq(10)->text(),
                'us' => $node->eq(11)->text(),
            ];
        }
    });
    
    $responses = array_where($cards, function ($key, $value) {
        return $value['uk'] || $value['ca'] || $value['us'];
    });
    
    //
    // Main Deck - black
    //
    
    $crawler = new Symfony\Component\DomCrawler\Crawler(file_get_contents(storage_path('Cards Against Humanity/CAH Main Deck - Black.html')));
    $crawler = $crawler->filter('table tbody tr')->reduce(function ($node, $i) {
        return $i > 5;
    });
    
    $cards = $crawler->each(function($node, $i) {
        $node = $node->filter('td');
        
        if ($node->count() > 12 && $node->eq(1)->text() && $node->eq(1)->text() != 'TOTALS') {
            return [
                'text' => $node->eq(1)->text(),
                'special' => $node->eq(2)->text(),
                'uk' => $node->eq(10)->text(),
                'ca' => $node->eq(11)->text(),
                'us' => $node->eq(12)->text(),
            ];
        }
    });
    
    $calls = array_where($cards, function ($key, $value) {
        return $value['uk'] || $value['ca'] || $value['us'];
    });
    
    $getCards = function ($card) {
        $card['text'] = preg_replace('/_+/', '_', $card['text']);
        $card['text'] = str_replace(['<br/>', '<i>', '</i>'], ['<br>', '<em>', '</em>'], $card['text']);
        $card['text'] = htmlentities($card['text']);
        $card['text'] = str_replace(['&nbsp;', '&rsquo;'], [' ', "'"], $card['text']);
        
        $card['ca'] = (bool) $card['ca'];
        $card['uk'] = (bool) $card['uk'];
        $card['us'] = (bool) $card['us'];
        
        if (isset($card['special'])) {
            $card['draw'] = 0;
            $card['pick'] = 1;
            
            if (preg_match('/(?:DRAW\s+(\d+),\s)?PICK\s+(\d+)/', $card['special'], $matches)) {
                $card['pick'] = (int) $matches[2];
                
                if ($matches[1]) {
                    $card['draw'] = (int) $matches[1];
                }
            }
            
            unset($card['special']);
        }
        
        return $card;
    };
    
    $sortCards = function ($a, $b) {
        if ($a['text'] == $b['text']) {
            return 0;
        }
        
        return $a['text'] < $b['text'] ? -1 : 1;
    };
    
    $cardset = function($set) use ($getCards, $sortCards) {
        $cards = array_map($getCards, $set);
        usort($cards, $sortCards);
        
        return $cards;
    };
    
    $main_deck = [
        'calls' => $cardset($calls),
        'responses' => $cardset($responses),
    ];
    
    return response()->json($cards);
    
    //return response()->json($sets);
});
