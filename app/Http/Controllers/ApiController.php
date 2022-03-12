<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use SebastianBergmann\Environment\Console;
use Illuminate\Support\Facades\Log;

class ApiController extends Controller
{
    function Index () {
        return response()->json(['greeting' => 'Hello!']);
    }

    public function newGame($num) {
        $answerIndex = rand(0, $num - 1);
        $quotes = $this->GenerateQuotes($num);
        $converted = $this->translateFromEn($quotes[$answerIndex], $this->get_language());

        //dd(['quote_answers' => $quotes, 'quote_display' => $converted, 'answer' => $quotes[$answerIndex]]);

        return response()->json(['quotes' => $quotes, 'quote_display' => $converted, 'answer' => $quotes[$answerIndex]]);
    }

    function GenerateQuotes($num) {
        $jsonQuotes = file_get_contents(__DIR__ . '../../../../database/quotes.json', true);
        $quotesArray = json_decode ($jsonQuotes, true);
        $randomNums = $this->getRandomNumbers($num, 5421);

        $quotes = array();
        foreach ($randomNums as $key => $value) {
            $quotes[] = $quotesArray[$key]['quoteText'];
        }
        
        return $quotes;
    }

    public function translateFromEn($en_string, $target_language) {

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,"https://libretranslate.de/translate");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,"q=$en_string&source=en&target=$target_language&format=text");  //Post Fields
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $headers = [
            'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
            'Accept-Encoding: gzip, deflate, br',
            'Cache-Control: no-cache',
            'Content-Type: application/x-www-form-urlencoded; charset=utf-8',
            'Host: libretranslate.de',
            'User-Agent: Mozilla/5.0 (X11; Ubuntu; Linux i686; rv:28.0) Gecko/20100101 Firefox/28.0',
            'X-MicrosoftAjax: Delta=true'
        ];


        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $server_output = curl_exec($ch);
        curl_close ($ch);

        return json_decode($server_output, true)['translatedText'];
    }

    public function get_language() {
        $languages = ["nl", "fi", "fr", "de", "hu", "ga", "it", "pl", "pt", "es", "sv", "tr"];
 
        $key = array_rand($languages);
        return $languages[$key];
    }

    public function getRandomNumbers($num, $max) {
  
        $randomNums = array();
        
        do {
            $randNum = rand(1, $max);
            if (!array_key_exists($num, $randomNums)) {
                $randomNums[$randNum] = $randNum;
            } 
        } while (Count($randomNums) < $num);
        return $randomNums;
    }
}
