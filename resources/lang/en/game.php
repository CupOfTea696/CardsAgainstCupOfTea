<?php

return array_dot([
    'play' => 'play',
    'join' => 'join game',
    'create' => 'create game',
    'create.heading' => 'Create a new game',
    'settings' => 'settings',
    'decks' => 'card deck|card decks',
    'sets' => [
        'main' => 'Main Cards Against Humanity deck',
        'official' => 'Official decks',
        'pax' => 'Penny Arcade Expo decks',
        'third-party' => 'Third-party decks',
    ],
    'locales' => [
        'ca' => 'Canadian version',
        'uk' => 'UK version',
        'us' => 'US version',
        'description' => 'The official Cards Against Humanity base game, ',
    ],
    
    'calls' => 'black cards',
    'responses' => 'white cards',
    
    'props' => [
        'name' => 'name',
        'name.label' => 'name your game',
        'score_limit' => 'score limit',
        'capacity' => 'player limit',
        'spec_capacity' => 'spectator limit',
        'private' => 'make this a private game',
        'private.help' => 'Only people you invite will be able to join this game, unless you set a password below. You can only invite people that have signed up with :site_name.',
        'pass' => 'password',
        'pass.label' => 'password protect your game.',
        'pass.help' => 'Only invited people and people with the password will be able to join your game.',
    ],
    
    'username.inuse' => 'Someone has already taken this username!',
]);
