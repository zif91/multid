<?php

// Initialize curl session
$ch = curl_init();

// Set URL to retrieve
curl_setopt($ch, CURLOPT_URL, "https://vysota-auto.ru/auto/");

// Set return transfer type to string
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

// Execute curl session
$output = curl_exec($ch);

// Close curl session
curl_close($ch);

// Load output into a DOM document
$dom = new DOMDocument;
@$dom->loadHTML($output);

// Find elements matching the CSS selector
$xpath = new DOMXPath($dom);
$elements = $xpath->query("body > div.brands-line > div > ul > li > a > span");

// Open a text file for writing
$file = fopen("output.txt", "w");

// Write contents of each element to the text file
foreach ($elements as $element) {
  fwrite($file, $element->textContent . "\n");
}

// Close the text file
fclose($file);

?>
