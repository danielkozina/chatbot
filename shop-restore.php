<?php

declare(strict_types=1);

namespace PhpmlExamples;
use Phpml\ModelManager;

include 'vendor/autoload.php';

$modelManager = new ModelManager();
$model = $modelManager->restoreFromFile('model/shop-model.phpml');

// $text = "Kampanie SEO które pozwolą Ci się zareklamować";
// $text = "Budowa stron internetowych opartych o super funkcjnalności i HTML, CSS";
// $text = "Prowadzenie Adwords to nasza specjalność";
// $text = "Budowa JavaScript i PHP";
$text = 'z gotowego motywu graficznego';
$predicted = $model->predict([$text]);

var_dump($predicted);