<?php

declare(strict_types=1);

namespace PhpmlExamples;
use Phpml\ModelManager;

include 'vendor/autoload.php';

$modelManager = new ModelManager();
$model = $modelManager->restoreFromFile('model/shop-model.phpml');

$text = "Chciałbym zbudowałać własny sklep internetowy."; // wordpress
$text = "Chciałbym zbudowałać własną stronę internetową."; // wordpress
$text = "Dzień dobry, chciałbym zbudowałać własną stronę internetową."; // wordpress
$text = "Dzień dobry, czy jest możliwe skorzystanie z gotowego motywu graficznego?"; // wordpress

$text = "Dzień dobry, interesuje mnie dobra reklama w interneci i pozyskanie nowych klientów."; // marketing
$text = "Dzień dobry, interesuje mnie Pozycjonowanie w wyszukiwarce Google i wysoka pozycja w wynikach wyszukiwania."; // marketing
$text = "Dzień dobry, mam pytanie o płatne reklamy. Czy pozwalają na skuteczną reklamę w sieci. Zastanawiam sie jakie jest idealne narzędzie do pozycjonowania."; // marketing

$predicted = $model->predict([$text]);

// za mało próbek do uczenia się dlatego może odpowiadać ostatni rekordem

var_dump($predicted);