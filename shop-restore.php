<?php
declare(strict_types=1);

namespace PhpmlExamples;
use Phpml\ModelManager;

require 'vendor/autoload.php';

$start = microtime(true);
$modelManager = new ModelManager();
$model = $modelManager->restoreFromFile(__DIR__.'/model/shop-model.phpml');
$total = microtime(true) - $start;

echo sprintf('Model loaded in %ss', round($total, 4)) . PHP_EOL;

$text = 'Hello i want to buy plugins for my wife. She want to have a TNT and Raben.';
$start = microtime(true);
$predicted = $model->predict([$text])[0];

$total = microtime(true) - $start;

echo sprintf('Predicted category: %s in %ss', $predicted, round($total, 6)) . PHP_EOL;