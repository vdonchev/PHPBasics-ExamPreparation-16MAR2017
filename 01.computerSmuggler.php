<?php
if (isset($_GET['list'])) {
    define("COMPUTER_PRICE", 420);
    $prices = [
        "CPU" => 85,
        "ROM" => 45,
        "RAM" => 35,
        "VIA" => 45
    ];

    $parts = [
        "CPU" => 0,
        "ROM" => 0,
        "RAM" => 0,
        "VIA" => 0
    ];

    $money = 0.;
    $partsToBuy = explode(", ", $_GET["list"]);
    foreach ($partsToBuy as $part) {
        if (array_key_exists(strtoupper($part), $parts)) {
            $parts[$part]++;
        }
    }

    foreach ($parts as $part => $count) {
        $price = $prices[strtoupper($part)];
        if ($count >= 5) {
            $price /= 2;
        }

        $money -= $count * $price;
    }

    $computers = 0;
    while (!in_array(0, array_values($parts))) {
        $computers++;
        $money += COMPUTER_PRICE;
        $parts = array_combine(array_keys($parts), array_map(function ($count) {
            return $count - 1;
        }, $parts));
    }

    foreach ($parts as $part => $count) {
        $money += ($prices[$part] / 2) * $count;
    }

    $partsLeft = array_sum(array_values($parts));
    $output = "<ul><li>{$computers} computers assembled</li><li>{$partsLeft} parts left</li></ul>";
    if ($money <= 0) {
        $output .= "<p>Nakov lost {$money} leva</p>";
    } else {
        $output .= "<p>Nakov gained {$money} leva</p>";
    }

    echo $output;
}