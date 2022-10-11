<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


class UndispatchedController extends Controller
{
    
    
    
    /*
    NOTES: Displays total numer of orders and date/ time when orders were last refreshed in the footer bar:
    Eg. 'No orders selected 2741 total orders' ' Orders refreshed 03/10/22 08:10:01'
    */
    public function index()
    {
        $pcode = 'PR22 6SZ';
        // $pcode = 'L93BT L10AA';
        
        $postcode = $this->checkPostCode($pcode);

        echo '<pre style="background:#111; color:#b5ce28; font-size:11px;">'; print_r($postcode); echo '</pre>'; die();
        
        return $return;
    }
    
    private function checkPostCode($rawPostCode)
    {
        // Permitted letters depend upon their position in the postcode.
        $alpha1 = '[abcdefghijklmnoprstuwyz]'; // Character 1
        $alpha2 = '[abcdefghklmnopqrstuvwxy]'; // Character 2
        $alpha3 = '[abcdefghjkpmnrstuvwxy]';   // Character 3
        $alpha4 = '[abehmnprvwxy]';            // Character 4
        $alpha5 = '[abdefghjlnpqrstuwxyz]';    // Character 5
        $bfpoa5 = '[abdefghjlnpqrst]{1}';      // BFPO character 5
        $bfpoa6 = '[abdefghjlnpqrstuwzyz]{1}'; // BFPO character 6
        $pcexp = [];
        // Expression for BF1 type postcodes
        $pcexp[0] = '/^(bf1)([[:space:]]{0,})([0-9]{1}'.$bfpoa5.$bfpoa6.')$/';
        // Expression for postcodes: AN NAA, ANN NAA, AAN NAA, and AANN NAA with a space
        $pcexp[1] = '/^('.$alpha1.'{1}'.$alpha2.'{0,1}[0-9]{1,2})([[:space:]]{0,})([0-9]{1}'.$alpha5.'{2})$/';
        // Expression for postcodes: ANA NAA
        $pcexp[2] = '/^('.$alpha1.'{1}[0-9]{1}'.$alpha3.'{1})([[:space:]]{0,})([0-9]{1}'.$alpha5.'{2})$/';
        // Expression for postcodes: AANA NAA
        $pcexp[3] = '/^('.$alpha1.'{1}'.$alpha2.'{1}[0-9]{1}'.$alpha4.')([[:space:]]{0,})([0-9]{1}'.$alpha5.'{2})$/';
        // Exception for the special postcode GIR 0AA
        $pcexp[4] = '/^(gir)([[:space:]]{0,})(0aa)$/';
        // Standard BFPO numbers
        $pcexp[5] = '/^(bfpo)([[:space:]]{0,})([0-9]{1,4})$/';
        // c/o BFPO numbers
        $pcexp[6] = '/^(bfpo)([[:space:]]{0,})(c\/o([[:space:]]{0,})[0-9]{1,3})$/';
        // Overseas Territories
        $pcexp[7] = '/^([a-z]{4})([[:space:]]{0,})(1zz)$/';
        // Anquilla
        $pcexp[8] = '/^ai-2640$/';
        // Load up the string to check, converting into lowercase
        $postCode = strtolower($rawPostCode);
        // Assume we are not going to find a valid postcode
        $valid = false;
        // Check the string against the six types of postcodes
        foreach ($pcexp as $regexp) {
            if (preg_match($regexp, $postCode, $matches)) {
                // Load new postcode back into the form element
                $postCode = strtoupper($matches[1].' '.$matches[3]);
                // Take account of the special bfpo c/o format
                $postCode = preg_replace('/C\/O([[:space:]]{0,})/', 'c/o ', $postCode);
                // Take acount of special Anquilla postcode format (a pain, but that's the way it is)
                if (preg_match($pcexp[7], strtolower($rawPostCode), $matches)) {
                    $postCode = 'AI-2640';
                }
                // Remember that we have found that the code is valid and break from loop
                $valid = true;
                break;
            }
        }
        // Return with the reformatted valid postcode in uppercase if the postcode was valid
        if ($valid) {
            return $postCode;
        }

        return false;
    }
}
