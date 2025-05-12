<?php
include_once ('classes/Surveyed.php');
$survey = new Surveyed();
$addSurv = $survey->addSurveyed($_POST["idempresa"], $_POST["nombreempresa"], $_POST["sexo"], $_POST["edad"], $_POST["area"], $_POST["antiguedad"], $_POST["niveleducativo"]);
?>
