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

// czyli pobieranie danych nie ma zadnego znaczenia

$dataset = new FilesDataset('data/webcrafters.studio');
// var_dump($dataset);
$split = new StratifiedRandomSplit($dataset, 0.2); // wtedy po 10 próbek z każdej kategorii i 8 wykorzystujemy do uczenia a 2 do walidacji
// var_dump($split);
$pipeline = new Pipeline([
    new TokenCountVectorizer($tokenizer = new NGramTokenizer(1, 3), new Polish()),
    new TfIdfTransformer()
], new SVC());

// var_dump($pipeline);

//predict propability i zwraca tablice gdzie jest przedzial jak bardzo jest pewny odpowiedzi SVM czyli Pipeline nie ma tego // 
$start = microtime(true);
$pipeline->train($split->getTrainSamples(), $split->getTrainLabels());
$stop = microtime(true);
// trenowanie ma sens tylko jesli zmieni się zawartość katalogu - trenowanie powinno być offline czyli po prostu plik wyczony wrzumay na serwer i na podsatwei jego odpowiadać
// można tu policzyć accuracy żeby dowiedzieć się jak on się wytrenował choćby ogólnie
// confusion matrix sprawdzić / chodzi o to że ma przewidziane i prawidzwe czyli zestawienie dwoch wartosci i mozemy przewidzieć / sprawdzenie gdzie się pomylił
// accuracy na 90% da już odpowiednią ocenę, można wyswietlać przy trenowaniu
// głównym problemem jest to accuracy dokładnie

$predicted = $pipeline->predict($split->getTestSamples());
// var_dump($predicted);

echo 'Train: ' . round($stop - $start, 4) . 's'. PHP_EOL;
echo 'Estimator: ' . get_class($pipeline->getEstimator()) . PHP_EOL;
echo 'Tokenizer: ' . get_class($tokenizer) . PHP_EOL;
echo 'Accuracy: ' . Accuracy::score($split->getTestLabels(), $predicted);

$modelManager = new ModelManager();
$modelManager->saveToFile($pipeline,'model/shop-model.phpml'); // zapisuje się model