<?php
$bitsToday = explode('/', $_GET["today"]);
$today = strtotime($bitsToday[1] . '/' . $bitsToday[0] . '/' . $bitsToday[2]);
$minAllowed = strtotime("-5 years", $today);

$invoices = $_GET["invoices"];
$dataExport = [];
foreach ($invoices as $invoice) {
    $tokens = explode("|", $invoice);
    foreach ($tokens as $key => $delivery) {
        $tokens[$key] = trim($delivery);
    }

    $currDate = $tokens[0];
    $bits = explode('/', $currDate);
    $date = strtotime($bits[1] . '/' . $bits[0] . '/' . $bits[2]);
    $company = $tokens[1];
    $drug = $tokens[2];
    $price = floatval($tokens[3]);

    if ($date >= $minAllowed) {
        if (!array_key_exists($date, $dataExport)) {
            $dataExport[$date] = [];
        }

        if (!array_key_exists($company, $dataExport[$date])) {
            $dataExport[$date][$company] = [];
        }

        $dataExport[$date][$company][] = [$drug, $price];
    }
}

ksort($dataExport);
$output = "<ul>";
foreach ($dataExport as $date => $companies) {
    $date = date("d/m/Y", $date);
    $output .= "<li><p>{$date}</p>";

    ksort($companies);
    foreach ($companies as $company => $drugs) {
        $output .= "<ul><li><p>{$company}</p>";

        $totalPrice = 0;
        $allDrugs = [];
        foreach ($drugs as $drug) {
            $allDrugs[] = $drug[0];
            $totalPrice += floatval($drug[1]);
        }

        sort($allDrugs);
        $output .= "<ul><li><p>" . implode(",", $allDrugs) . "-{$totalPrice}lv</p></li></ul></li></ul>";
    }

    $output .= "</li>";
}

$output .= "</ul>";

echo $output;