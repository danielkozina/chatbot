<?php

declare(strict_types=1);

namespace PhpmlExamples;
use Phpml\ModelManager;

include 'vendor/autoload.php';

$modelManager = new ModelManager();
$model = $modelManager->restoreFromFile('model/shop-model.phpml');

// $text = "Zakładanie własnego sklepu internetowego to najlepsza z możliwych opcji";
$text = "Autorska grafika strony internetowej";
// $text = "najlepszy sposób na pozyskanie nowych klientów";

$predicted = $model->predict([$text]);

var_dump($predicted);