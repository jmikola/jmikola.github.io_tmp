#!/usr/bin/env php
<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Symfony\Component\String\Slugger\AsciiSlugger;
use Symfony\Component\Yaml\Yaml;

$inFile = $argv[1] ?? null;
$outDir = realpath(__DIR__ . '/../video/_posts');

if ($inFile === null || !is_file($inFile) || !is_readable($inFile)) {
    fprintf(STDERR, "%s is not a readable file\n", $inFile ?? '(null)');
    exit;
}

if (!is_dir($outDir) || !is_writable($outDir)) {
    fprintf(STDERR, "%s is not a writable directory\n", $outFile);
    exit;
}

$videos = json_decode(file_get_contents($inFile));
$slugger = new AsciiSlugger('en');

// Strip single and double quotes before generating slugs
$stripChars = ['\'', '"', "\u{2018}", "\u{2019}", "\u{201C}", "\u{201D}"];

foreach ($videos as $video) {
    $frontMatter = Yaml::dump([
        'layout' => 'video',
        'title' => $video->title,
        'youtube_id' => $video->id,
        'tags' => ['jrl'],
    ]);

    $output = <<<END
        ---
        {$frontMatter}
        ---
        {$video->description}
        END;

    $date = new DateTime($video->recordingDate);
    $basename = $date->format('Y-m-d-') . str_replace($stripChars, '', $video->title);
    $outFile = $outDir . '/' . $slugger->slug($basename)->lower() . '.md';

    echo "Writing output to $outFile\n";
    file_put_contents($outFile, $output);
}
