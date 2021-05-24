<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="//db.onlinewebfonts.com/c/32441506567156636049eb850b53f02a?family=Times+New+Roman" rel="stylesheet" type="text/css"/>
    <title>PDF</title>

    <style>
        body{
            padding-left: 20%; padding-right: 20%;
        }
        .header-part{
            line-height: 20px;
        }
        .header{
            text-align: center;
            color: #336699;font-size: 30px; 
            text-shadow: 3px 2px #C1C1C1;
            font-family: 'Times New Roman', Times, serif;
        }
        .address{
            text-align: center; 
            color: #548DD4;
        }
        .email{
            text-align: center;
            color: #548DD4;
        }
        .main-email-address{
            text-decoration: underline;
            font-weight: 600;
        }
        hr{
            border: 1px solid black;
        }
        .tabile-one{
            padding-top: 20px;
            width: 100%;
        }
        .tabile-one tr{
            text-align: left;
        }
        .invoice-table{
            width: 100%;
        }
        .to-table{
            width: 100%;
        }
        .table-two{
            border: 1px solid black;
            width: 100%;
            border-collapse: collapse;
        }
        .table-two th{
            border: 1px solid black;
        }
        .table-two tr{
            border: 1px solid black;
        }
        .table-two td{
            border: 1px solid black;
            text-align: center;
        }
        .total-row{
            font-weight: 600;
        }
        .bold{
            font-weight: 600;
        }
        .money-ammount{
            text-decoration: underline;
        }
        .table-three{
            width: 100%;
        }
        .table-three tr{
            font-size: large;
            font-weight: 600;
        }
        .table-three td{
            font-size: large;
            font-weight: 600;
        }
        .name{
            line-height: 0px;
            padding-bottom: 20%;
        }
        .note{
            text-align: center;
            padding-bottom: 5%;
            color: #808080;
        }
        .padding-margin p{
            padding: 0;
            margin: 0;
        }
    </style>
</head>



<body>
    <section>
        <div class="header-part">
            <h1 class="header">TENAGA CEKAP MANAGEMENT SDN BHD</h1>
            <h3 class="address">C-3A-1 MEGAN AVENUE 1, 189 JALAN TUN RAZAK, 50400 KUALA LUMPUR W.PKUALA LUMPUR MALAYSIA</h3>
            <p class="email">Email:<span class="main-email-address">tenagacekap@gmail.com</span> (013-6662225 / 012-3587846)</p>
            <hr>
        </div>

        <table class="tabile-one">
            <tr>
                <th>TO : HALFEN MOMENT SDN BHD     </th>
                <th>INVOICE</th>   
            </tr>
            <tr>
                <td>
                    <table class="to-table">
                        <tr>
                            <td>28 Jalan Anggerik Mokara 31/59 </td>
                        </tr>
                        <tr>
                            <td>Kota Kemuning, Seksyen 31,   </td>
                        </tr>
                        <tr>
                            <td>40460 Shah Alam</td>
                        </tr>
                        <tr>
                            <td>Selangor, Malaysia </td>
                        </tr>
                    </table>
                </td>
                <td>
                    <table class="invoice-table">
                        <tr>
                            <td colspan="3">Invoice No </td>
                            <td>:  H-00{{ $invoice->id }}</td>
                        </tr>
                        <tr>
                            <td colspan="3">Month  </td>
                            <td>:{{ Carbon\Carbon::parse($invoice->created_at)->format('M Y') }}</td>
                        </tr>
                        <tr>
                            <td colspan="3">Date </td>
                            <td>:{{ Carbon\Carbon::parse($invoice->created_at)->format('d M Y') }}</td>
                        </tr>

                    </table>
                </td> 
            </tr>

            
   
        </table>

        <h4>ATTN: HR DEPARTMENT</h4>

        <table class="table-two">
            <tr>
                <th>No</th>
                <th>Item</th>
                <th>Quantity</th>
                <th>Price (RM)</th>
                <th>Total (RM)</th>
            </tr>
            <tr class="padding-margin">
                <td>{{ $invoice->id }}</td>
                <td>
                    <p class="bold">{{ $invoice->invoiceHistory->item_name }}</p>
                </td>
                <td>  
                    <p style="text-align: center;">{{ $invoice->invoiceHistory->quantity }}</p>
                    <p style="text-align: center;">Cleaner</p>
                </td>
                <td>
                   <p>Rm {{ $invoice->invoiceHistory->amount }}</p> 
                   <p>Per Cleaner</p>  
                </td>
                <td>
                    RM {{ $invoice->invoiceHistory->amount*$invoice->invoiceHistory->quantity }}
                </td>
            </tr>
            <tr>
                <td>2</td>
                <td colspan="3">All chemical supply</td>
                <td>(Inclusive In Price)</td>
            </tr>
            <tr>
                <td>3</td>
                <td colspan="3">Equipment Rental</td>
                <td>(Inclusive In Price)</td>
            </tr>
            <tr class="total-row">
                <td colspan="4">TOTAL</td>
                <td>Rm {{ $invoice->invoiceHistory->amount*$invoice->invoiceHistory->quantity }}</td>
            </tr>

        </table>

        <h4>RINGGIT MALAYSIA: </h4>
        @php
            function numberTowords($num)
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
                    "014" => "FOURTEEN"
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
                $num = number_format($num,2,".",","); 
                $num_arr = explode(".",$num); 
                $wholenum = $num_arr[0]; 
                $decnum = $num_arr[1]; 
                $whole_arr = array_reverse(explode(",",$wholenum)); 
                krsort($whole_arr,1); 
                $rettxt = ""; 
                foreach($whole_arr as $key => $i){
                    
                    while(substr($i,0,1)=="0")
                            $i=substr($i,1,5);
                    if($i < 20){ 
                        /* echo "getting:".$i; */
                        $rettxt .= $ones[$i]; 
                    }elseif($i < 100){ 
                        if(substr($i,0,1)!="0")  $rettxt .= $tens[substr($i,0,1)]; 
                        if(substr($i,1,1)!="0") $rettxt .= " ".$ones[substr($i,1,1)]; 
                    }else{ 
                        if(substr($i,0,1)!="0") $rettxt .= $ones[substr($i,0,1)]." ".$hundreds[0]; 
                        if(substr($i,1,1)!="0")$rettxt .= " ".$tens[substr($i,1,1)]; 
                        if(substr($i,2,1)!="0")$rettxt .= " ".$ones[substr($i,2,1)]; 
                    } 
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
            $total_amount = $invoice->invoiceHistory->amount*$invoice->invoiceHistory->quantity;
        @endphp
        <h4 class="money-ammount">{{ numberTowords($total_amount) }} RINGGIT ONLY</h4>
        <table class="table-three">
            <tr>
                <td>Company Name</td>
                <td>: TENAGA CEKAP MANAGEMENT SDN BHD</td>
            </tr>
            <tr>
                <td>Bank</td>
                <td>: RHB BANK BERHAD</td>
            </tr>
            <tr>
                <td>Bank Account No</td>
                <td>: 21260200015117</td>
            </tr>
        </table>
        <h4>Yours Truly:</h4>
        <div class="name">
            <h4>Sri Krishnan Nair </h4>
            <h4>Masters Of  Mechanical Engineering (UPM)</h4>
            <h4>Manager,</h4>
            <h4>TENAGA CEKAP MANAGEMENT SDN BHD</h4>
            <h4>(012-6663770/ 012-3587846)</h4>
        </div>
        <h3 class="note">Note: This Invoice Is Computer Generated And No Signature Is Required</h3>
    </section>
    
</body>
</html>