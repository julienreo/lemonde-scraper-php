<?php

require __DIR__ . '/../src/helpers.php';
require __DIR__ . '/../config/config.php';
require __DIR__ . '/../src/LeMondeScraper.php';
require __DIR__ . '/../src/FormatData.php';
require __DIR__ . '/../src/Database.php';
require __DIR__ . '/../src/Mailer.php';
require __DIR__ . '/../vendor/autoload.php';

/*
 * Retrieve headline data from http://www.lemonde.fr 
 */

$scraper = new LeMondeScraper;

$data = [];
try {
    $data['headline_id'] = $scraper->getHeadlineId();
    $data['headline_title'] = $scraper->getHeadlineTitle();
    $data['headline_img_src'] = $scraper->getHeadlineImgSrc();
    $data['headline_img_legend'] = $scraper->getHeadlineImgLegend();
    $data['headline_article_link'] = $scraper->getHeadlineArticleLink();
}
catch(Exception $e) {
    error_log($e->getMessage());
    die;
}

$date = new DateTime('now', new DateTimeZone('Europe/Paris'));
$data['created_at'] = $date->format('Y-m-d H:i:s');

$data = array_filter($data, function($data) {
    return !empty($data);
});

/*
 * Format data to the expected output 
 */

$formattedData = FormatData::format($data);

/*
 * Instantiate Database class 
 */

try {
    $database = new Database($dbConfig[$env]);
}
catch(Exception $e) {
    error_log($e->getMessage());
    die;
}

/*
 * Retrieve last entry in le_monde_scraper table
 */

try {
    $lastEntry = $database->getLastEntry($tableConfig);
}
catch(Exception $e) {
    error_log($e->getMessage());
    die;
}

/*
 * Check if last entry matches extracted data, if so end the script as healdine hasn't changed
 */

if ($lastEntry['headline_id'] === $formattedData['headline_id']) {
    die;
}

/*
 * Insert data into le_monde_scraper table
 */

try {
    $database->insert($formattedData, $tableConfig);
}
catch(Exception $e) {
    error_log($e->getMessage());
    die;
}

/*
 * Send data per mail 
 */

$dataToSend = [
    'headline_title' => $formattedData['headline_title'],
    'headline_img_src' => $formattedData['headline_img_src'],
    'headline_img_legend' => $formattedData['headline_img_legend'],
    'headline_article_link' => $formattedData['headline_article_link'],
];

try {
    $mailer = new Mailer($mailerConfig[$env]);
    $mailer->send([
        'to' => $mailingList,
        'from' => ['XXXXXXXX@XXXXXXXX.com' => 'LeMonde.fr'],
        'subject' => 'Nouvelle Une disponible !',
        'body' => implode('<br><br>', $dataToSend)
    ]);
}
catch(Exception $e) {
    error_log($e->getMessage());
    die;
}