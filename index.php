<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>currency thingy</title>
</head>
<body>
  <form action="<?php $PHP_SELF?>"  method="post">
    <h3>From</h3>
    <select name="from_currency" id="fromCurrency">
      <option value="USD">USD</option>
      <option value="RUB">RUB</option>
      <option value="UZS">UZS</option>
    </select>
    <input type="text" placeholder="Amount" name="from_amount">

    <h3>To</h3>
    <select name="to_currency" id="toCurrency">
      <option value="USD">USD</option>
      <option value="RUB">RUB</option>
      <option value="UZS">UZS</option>
    </select>
    <input type="submit" value="Submit">
  </form>  
</body>
</html>
<?php
extract($_POST);
$api_key = "c923c62dcb5f8184fb9be756";
$endpoint = "latest";
$url = 'https://v6.exchangerate-api.com/v6/'.$api_key.'/'.$endpoint.'/'.$from_currency;
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$json = curl_exec($ch);
curl_close($ch);

if ($json === false) {
    die('Error fetching data: ' . curl_error($ch));
}

$outputRates = json_decode($json, true);

if ($outputRates === null) {
    die('Error decoding JSON: ' . json_last_error());
}

if (!isset($outputRates['conversion_rates'][$to_currency])) {
    die('Invalid to_currency');
}

if (!is_numeric($from_amount) || $from_amount <= 0) {
    die('Invalid from_amount');
}

$outputRate = $outputRates['conversion_rates'][$to_currency];

if ($from_amount > 1) {
    echo "1 $from_currency is " . number_format($outputRate, 2) . " $to_currency, $from_amount $from_currency is " . number_format($outputRate * $from_amount, 2) . ' ' . $to_currency;
} elseif ($from_amount == 1) {
    echo "1 $from_currency is " . number_format($outputRate, 2) . ' ' . $to_currency;
}
?>