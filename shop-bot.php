<?php

require 'vendor/autoload.php';

// $dataset = new ArrayDataset(
//     ['Sklep internetowy z gotowego szablonu graficznego. To z pewnością wartość dodana dla Twojej firmy. Oferta zawiera: 6 gotowych szablonów do wyboru Maksymalnie 5 zakładek + zakładki sklepowe Optymalizacja sklepu Zabezpieczenie sklepu Podłączenie do Google Analytics oraz Pixel Facebook Sklep internetowy oparty o szablon to zdecydowanie wartość dodana do Twojej firmy. Pozwala zbadać rynek oraz jednocześnie zaoszczędzić sporo pieniędzy.'],['Sklep internetowy z indywidualną autorską grafiką. To z pewnością wartość dodana dla Twojej firmy. Oferta zawiera: Autorska grafika sklepu Maksymalnie 5 zakładek + zakładki sklepowe Optymalizacja sklepu Zabezpieczenie sklepu Podłączenie do Google Analytics oraz Pixel Facebook Sklep internetowy oparty o autorską grafikę to zdecydowanie wartość dodana do Twojej firmy.'],
//     ['http://webcrafters.studio/index.php/produkt/sklep-internetowy-gotowy-szablon','http://webcrafters.studio/index.php/produkt/sklep-internetowy-autorska-grafika']
// );

use Phpml\Classification\SVC;
use Phpml\CrossValidation\StratifiedRandomSplit;
use Phpml\Dataset\FilesDataset;
use Phpml\FeatureExtraction\StopWords\Polish;
use Phpml\FeatureExtraction\TfIdfTransformer;
use Phpml\FeatureExtraction\TokenCountVectorizer;
use Phpml\Metric\Accuracy;
use Phpml\ModelManager;
use Phpml\Pipeline;
use Phpml\SupportVectorMachine\Kernel;
use Phpml\Tokenization\NGramTokenizer;

// $dataset = new ArrayDataset(
//     ['Sklep internetowy z gotowego szablonu graficznego. To z pewnością wartość dodana dla Twojej firmy. Oferta zawiera: 6 gotowych szablonów do wyboru Maksymalnie 5 zakładek + zakładki sklepowe Optymalizacja sklepu Zabezpieczenie sklepu Podłączenie do Google Analytics oraz Pixel Facebook Sklep internetowy oparty o szablon to zdecydowanie wartość dodana do Twojej firmy. Pozwala zbadać rynek oraz jednocześnie zaoszczędzić sporo pieniędzy.'],['Sklep internetowy z indywidualną autorską grafiką. To z pewnością wartość dodana dla Twojej firmy. Oferta zawiera: Autorska grafika sklepu Maksymalnie 5 zakładek + zakładki sklepowe Optymalizacja sklepu Zabezpieczenie sklepu Podłączenie do Google Analytics oraz Pixel Facebook Sklep internetowy oparty o autorską grafikę to zdecydowanie wartość dodana do Twojej firmy.'],
//     ['http://webcrafters.studio/index.php/produkt/sklep-internetowy-gotowy-szablon','http://webcrafters.studio/index.php/produkt/sklep-internetowy-autorska-grafika']
// );

$dataset = new FilesDataset('data/webcrafters.studio');
// var_dump($dataset);
$split = new StratifiedRandomSplit($dataset, 0.5);
// var_dump($split);
$pipeline = new Pipeline([
    new TokenCountVectorizer($tokenizer = new NGramTokenizer(1, 3), new Polish()),
    new TfIdfTransformer()
], new SVC(Kernel::LINEAR));
// var_dump($pipeline);

$pipeline->train($split->getTrainSamples(), $split->getTrainLabels());
$predicted = $pipeline->predict($split->getTestSamples());
// var_dump($predicted);

$modelManager = new ModelManager();
$modelManager->saveToFile($pipeline,'model/shop-model.phpml');