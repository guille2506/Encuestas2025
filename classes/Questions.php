<?php
class Questions {
    public function getQuestions($csvfile) {
        $fp = fopen($csvfile, 'r');
        $headers = fgetcsv($fp);
        $data = array();
        while (($row = fgetcsv($fp)) !== false) {
            $data[] = array_combine($headers, $row);
        }
        fclose($fp);
        $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        $arrayoraciones1 = json_decode($json, true);
        return $arrayoraciones1;
    }
    public function getScales($csvfile) {
        $fp = fopen($csvfile, 'r');
        $headers = fgetcsv($fp);
        $data = array();
        while (($row = fgetcsv($fp)) !== false) {
            $data[] = array_combine($headers, $row);
        }
        fclose($fp);
        $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        $arrayescalas = json_decode($json, true);
        return $arrayescalas;
    }
}
?>
