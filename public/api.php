<?php
/**
 * dummy API in order to not break old plugins but inform them that they need to update.
 */

$dummyOutput = [
    "id"               => 0,
    "beatname"         => 'Plugin outdated! Please update.',
    "ownerid"          => 0,
    "downloads"        => 0,
    "upvotes"          => 0,
    "plays"            => 0,
    "beattext"         => 'Plugin outdated! Please update.',
    "uploadtime"       => time(),
    "songName"         => 'Plugin outdated! Please update.',
    "songSubName"      => 'Plugin outdated! Please update.',
    "authorName"       => 'Plugin outdated! Please update.',
    "beatsPerMinute"   => 'Plugin outdated! Please update.',
    "difficultyLevels" => [],
    "img"              => 'jpg',
];


if (empty($_GET['mode'])) {
    $_GET['mode'] = 'top';
}

switch ($_GET['mode']) {
    case "top":
    case "new":
    case "star":
    case "usersongs":
    case "plays":
        header('Content-Type: application/json');
        die(json_encode([$dummyOutput]));
    case 'votekey':
        header('Content-Type: application/json');
        die(json_encode("INVALID"));
    case 'hashinfo':
        header('Content-Type: application/json');
        die(json_encode([]));
    case 'details':
        header('Content-Type: application/json');
        die(json_encode($dummyOutput));
    default:
        die('API OUTDATED!');
}
