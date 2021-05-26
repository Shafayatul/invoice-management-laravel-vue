<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InvoiceSent extends Mailable
{
    use Queueable, SerializesModels;
    public $invoice;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($invoice)
    {
        $this->invoice = $invoice;
    }

    public function numberTowords($num)
    {

        $ones = array(
            0 =>"ZERO",
            1 => "ONE",
            2 => "TWO",
            3 => "THREE",
            4 => "FOUR",
            5 => "FIVE",
            6 => "SIX",
            7 => "SEVEN",
            8 => "EIGHT",
            9 => "NINE",
            10 => "TEN",
            11 => "ELEVEN",
            12 => "TWELVE",
            13 => "THIRTEEN",
            14 => "FOURTEEN",
            15 => "FIFTEEN",
            16 => "SIXTEEN",
            17 => "SEVENTEEN",
            18 => "EIGHTEEN",
            19 => "NINETEEN",
            );
        $tens = array( 
            0 => "ZERO",
            1 => "TEN",
            2 => "TWENTY",
            3 => "THIRTY", 
            4 => "FORTY", 
            5 => "FIFTY", 
            6 => "SIXTY", 
            7 => "SEVENTY", 
            8 => "EIGHTY", 
            9 => "NINETY" 
        ); 
        $hundreds = array( 
            "HUNDRED", 
            "THOUSAND", 
            "MILLION", 
            "BILLION", 
            "TRILLION", 
            "QUARDRILLION" 
        ); /*limit t quadrillion */
        //$num_key = number_format($num,2,".",",");
        $num = number_format($num,2,".","");
        $num_arr = explode(".",$num); 
        $wholenum = $num_arr[0]; 
        $decnum = $num_arr[1]; 
        $whole_arr = array_reverse(explode(",",$wholenum)); 
        
        krsort($whole_arr,1);
        $rettxt = ""; 
        foreach($whole_arr as $key => $i){
            
            
            while(substr($i,0,1)== "0"){
                $i=substr($i, 1, strlen($i));
            }
            //0100000
            if((int) $i < 20){
                $rettxt .= $ones[$i];
            } else if((int) $i < 100){
                if(substr($i,0,1)!="0") $rettxt .= $tens[substr($i,0,1)];
                if(substr($i,1,1)!="0") $rettxt .= " ".$ones[substr($i,1,1)];
            } else if((int) $i < 1000){
                if(substr($i,0,1)!="0") $rettxt .= $ones[substr($i,0,1)]." ".$hundreds[0];
                if(substr($i,1,1)!="0") $rettxt .= " ".$tens[substr($i,1,1)];
                if(substr($i,2,1)!="0") $rettxt .= " ".$ones[substr($i,2,1)];
            } else if((int) $i < 10000){
                if(substr($i,0,1)!="0") $rettxt .= " ".$ones[substr($i,0,1)]." ".$hundreds[1];
                if(substr($i,1,1)!="0") $rettxt .= " ".$ones[substr($i,1,1)]." ".$hundreds[0];
                if(substr($i,2,1)!="0") $rettxt .= " ".$tens[substr($i,2,1)];
                if(substr($i,3,1)!="0") $rettxt .= " ".$ones[substr($i,3,1)];
            } else if((int) $i < 100000){
                if(substr($i,0,1)!="0") $rettxt .= " ".$tens[substr($i,0,1)];
                if(substr($i,1,1)!="0") $rettxt .= " ".$ones[substr($i,1,1)]." ".$hundreds[1];
                if(substr($i,2,1)!="0") $rettxt .= " ".$ones[substr($i,2,1)]." ".$hundreds[0];
                if(substr($i,3,1)!="0") $rettxt .= " ".$tens[substr($i,3,1)];
                if(substr($i,4,1)!="0") $rettxt .= " ".$ones[substr($i,4,1)];
            } else if((int) $i < 1000000){
                if(substr($i,0,1)!="0") $rettxt .= " ".$ones[substr($i,0,1)]." ".$hundreds[0];
                if(substr($i,1,1)!="0") $rettxt .= " ".$tens[substr($i,1,1)];
                if(substr($i,2,1)!="0") $rettxt .= " ".$ones[substr($i,2,1)]." ".$hundreds[1];
                if(substr($i,3,1)!="0") $rettxt .= " ".$ones[substr($i,3,1)]." ".$hundreds[0];
                if(substr($i,4,1)!="0") $rettxt .= " ".$tens[substr($i,4,1)];
                if(substr($i,5,1)!="0") $rettxt .= " ".$ones[substr($i,5,1)];
            } else if((int) $i < 10000000){
                if(substr($i,0,1)!="0") $rettxt .= " ".$ones[substr($i,0,1)]." ".$hundreds[2];
                if(substr($i,1,1)!="0") $rettxt .= " ".$ones[substr($i,1,1)]." ".$hundreds[0];
                if(substr($i,2,1)!="0") $rettxt .= " ".$tens[substr($i,2,1)];
                if(substr($i,3,1)!="0") $rettxt .= " ".$ones[substr($i,3,1)]." ".$hundreds[1];
                if(substr($i,4,1)!="0") $rettxt .= " ".$ones[substr($i,4,1)]." ".$hundreds[0];
                if(substr($i,5,1)!="0") $rettxt .= " ".$tens[substr($i,5,1)];
                if(substr($i,6,1)!="0") $rettxt .= " ".$ones[substr($i,6,1)];
            }
            /*else {
                if(substr($i,0,1)!="0") $rettxt .= " ".$ones[substr($i,0,1)]." ".$hundreds[2];
                //if(substr($i,1,1)!="0") $rettxt .= " ".$ones[substr($i,1,1)]." ".$hundreds[2];
                if(substr($i,2,1)!="0") $rettxt .= " ".$ones[substr($i,2,1)]." ".$hundreds[0];
                if(substr($i,3,1)!="0") $rettxt .= " ".$tens[substr($i,3,1)];
                if(substr($i,4,1)!="0") $rettxt .= " ".$ones[substr($i,4,1)]." ".$hundreds[1];
                if(substr($i,5,1)!="0") $rettxt .= " ".$ones[substr($i,5,1)]." ".$hundreds[0];
                if(substr($i,6,1)!="0") $rettxt .= " ".$tens[substr($i,6,1)];
                if(substr($i,7,1)!="0") $rettxt .= " ".$ones[substr($i,7,1)];
            }*/
            if($key > 0){ 
                $rettxt .= " ".$hundreds[$key]." "; 
            }
        }
        
        if($decnum > 0){
            $rettxt .= " and ";
            if($decnum < 20){
                $rettxt .= $ones[$decnum];
            }elseif($decnum < 100){
                $rettxt .= $tens[substr($decnum,0,1)];
                $rettxt .= " ".$ones[substr($decnum,1,1)];
            }
        }
        return $rettxt;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $total_amount = $this->invoice->invoiceHistory->amount*$this->invoice->invoiceHistory->quantity;
        $amount_in_words = $this->numberTowords($total_amount);
        return $this->markdown('emails.invoice', compact('amount_in_words'));
    }
}
