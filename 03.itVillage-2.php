<?php
$matrix = explode(" | ", trim($_GET["board"]));
for ($i = 0; $i < 4; $i++) {
    $matrix[$i] = array_map("trim", explode(" ", $matrix[$i]));
}

$start = explode(" ", trim($_GET["beginning"]));
$startCoordinates = [
    "11" => 0,
    "12" => 1,
    "13" => 2,
    "14" => 3,
    "24" => 4,
    "34" => 5,
    "44" => 6,
    "43" => 7,
    "42" => 8,
    "41" => 9,
    "31" => 10,
    "21" => 11,
];

$board = $matrix[0];
$board[] = $matrix[1][3];
$board[] = $matrix[2][3];
$board = array_merge($board, array_reverse($matrix[3]));
$board[] = $matrix[2][0];
$board[] = $matrix[1][0];
$playerPos = $startCoordinates[$start[0] . $start[1]];

$ins = count(array_filter($board, function ($cell) {
    return strtoupper($cell) === "I";
}));

$ownedIns = 0;
$coins = 50;
$moves = explode(" ", trim($_GET["moves"]));
for ($i = 0; $i < count($moves); $i++) {
    $playerPos = ($playerPos + intval($moves[$i])) % count($board);
    $coins += $ownedIns * 20;
    switch ($board[$playerPos]) {
        case "P":
            $coins -= 5;
            break;
        case "I":
            if ($coins >= 100) {
                $ownedIns++;
                $coins -= 100;
                $board[$playerPos] = 0;
            } else {
                $coins -= 10;
            }

            break;
        case "F":
            $coins += 20;
            break;
        case "S":
            $i += 2;
            break;
        case "V":
            $coins *= 10;
            break;
        case "N":
            echo "<p>You won! Nakov's force was with you!<p>";
            exit;
    }

    if ($coins < 0) {
        echo "<p>You lost! You ran out of money!<p>";
        exit;
    }

    if ($ins === $ownedIns) {
        echo "<p>You won! You own the village now! You have {$coins} coins!<p>";
        exit;
    }
}

echo "<p>You lost! No more moves! You have {$coins} coins!<p>";