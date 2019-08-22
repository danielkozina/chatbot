<?php
// declare(strict_types=1);
// namespace PhpmlExamples;

// use Phpml\Classification\SVC;
// use Phpml\CrossValidation\StratifiedRandomSplit;
// use Phpml\Dataset\FilesDataset;
// use Phpml\FeatureExtraction\StopWords\English;
// use Phpml\FeatureExtraction\TfIdfTransformer;
// use Phpml\FeatureExtraction\TokenCountVectorizer;
// use Phpml\Metric\Accuracy;
// use Phpml\ModelManager;
// use Phpml\Pipeline;
// use Phpml\SupportVectorMachine\Kernel;
// use Phpml\Tokenization\NGramTokenizer;

// require 'vendor/autoload.php';

// $dataset = new FilesDataset('data/shop');
// $split = new StratifiedRandomSplit($dataset, 0.3);

// $samples = $split->getTrainSamples();

// $vectorizer = new TokenCountVectorizer(new NGramTokenizer(1, 3), new English());
// $vectorizer->fit($samples);
// $vectorizer->transform($samples);

// $transformer = new TfIdfTransformer();
// $transformer->fit($samples);
// $transformer->transform($samples);

// $classifier = new SVC();
// $classifier->train($samples, $split->getTrainLabels());

// $testSamples = $split->getTestSamples();

// $vectorizer->transform($testSamples);

// $transformer->transform($testSamples);

// $predicted = $classifier->predict($testSamples);

// echo 'Accuracy: ' . Accuracy::score($split->getTestLabels(), $predicted);

// $pipeline = new Pipeline([
//     new TokenCountVectorizer($tokenizer = new NGramTokenizer(1, 3), new English()),
//     new TfIdfTransformer()
// ], new SVC(Kernel::LINEAR));

// $start = microtime(true);
// $pipeline->train($split->getTrainSamples(), $split->getTrainLabels());
// $stop = microtime(true);
// $predicted = $pipeline->predict($split->getTestSamples());

// echo 'Train: ' . round($stop - $start, 4) . 's'. PHP_EOL;
// echo 'Estimator: ' . get_class($pipeline->getEstimator()) . PHP_EOL;
// echo 'Tokenizer: ' . get_class($tokenizer) . PHP_EOL;
// echo 'Accuracy: ' . Accuracy::score($split->getTestLabels(), $predicted);

// $modelManager = new ModelManager();
// $modelManager->saveToFile($pipeline, __DIR__.'/model/shop-model.phpml');
require 'vendor/autoload.php';

use Phpml\Classification\SVC;
use Phpml\CrossValidation\StratifiedRandomSplit;
use Phpml\Dataset\FilesDataset;
use Phpml\FeatureExtraction\StopWords\English;
use Phpml\FeatureExtraction\TfIdfTransformer;
use Phpml\FeatureExtraction\TokenCountVectorizer;
use Phpml\Metric\Accuracy;
use Phpml\ModelManager;
use Phpml\Pipeline;
use Phpml\SupportVectorMachine\Kernel;
use Phpml\Tokenization\NGramTokenizer;
use Phpml\Tokenization\WordTokenizer;

$dataset = new FilesDataset('data/shop');

$split = new StratifiedRandomSplit($dataset, 0.2);
$samples = $split->getTrainSamples();

$vectorizer = new TokenCountVectorizer(new WordTokenizer, new English());
$vectorizer->fit($samples);
$vectorizer->transform($samples);

$classifier = new SVC();
$classifier->train($samples, $split->getTrainLabels());

$transformer = new TfIdfTransformer();
$transformer->fit($samples);
$transformer->transform($samples);

$testSamples = $split->getTestSamples();
$vectorizer->transform($testSamples);
$transformer->transform($testSamples);

$predicted = $classifier->predict($testSamples);

// echo 'Accuracy: ' . Accuracy::score($split->getTestLabels(), $predicted);

$pipeline = new Pipeline([
    new TokenCountVectorizer(new NGramTokenizer(1, 3), new English()),
    new TfIdfTransformer()
], new SVC());
$pipeline->train($split->getTrainSamples(), $split->getTrainLabels());

$predicted = $pipeline->predict($split->getTestSamples());

// echo 'Accuracy: ' . Accuracy::score($split->getTestLabels(), $predicted);

$modelManager = new ModelManager();
$modelManager->saveToFile($pipeline, 'model/shop-model.phpml');

