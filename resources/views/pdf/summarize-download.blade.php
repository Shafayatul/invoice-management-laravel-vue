<!doctype html>
<html lang="ar" dir="rtl">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Summarize Invoice All</title>

    <style type="text/css">
        body { 
            font-family: DejaVu Sans, sans-serif;
        }
        @page {
        padding: 0px 15px 0 15px;
        
        }
        .top-title{
        text-align:center;
        margin-bottom:0px !important;
        }
        .logo_div{
            text-align: center;
            width: 250px;        
            margin-top: 0px;
        
        }
        .top_table{
            margin-bottom: 10px;
        }

        table {
        font-size: 12px;
        }

        .border_color tr td {
        border-bottom: 2px dotted #8F8F8F;
        padding-top: 5px;
        padding-bottom: 5px;
        margin-left:-14px;
        }

        .border_color tr th {
        border-bottom: 2px dotted #8F8F8F;
        margin-left:-14px;
        }

        tfoot tr td {
        font-weight: normal;
        font-size: 12px;
        }


        .gray {
        background-color: lightgray
        }
        .footer_text{
        text-align:center;
        font-size: 12px;
        }
        .table_top tfoot tr td{
        padding-bottom: 0px !important;
        padding-top: 0px !;
        }
        .top_header{
        margin-bottom: -10px;
        }
        table {
            border-left: 0.01em solid #ccc;
            border-right: 0;
            border-top: 0.01em solid #ccc;
            border-bottom: 0;
            border-collapse: collapse;
        }
        table td,
        table th {
            border-left: 0;
            border-right: 0.01em solid #ccc;
            border-top: 0;
            border-bottom: 0.01em solid #ccc;
        }
    </style>

</head>

<body>
    <h2>Summarized Invoice Data</h2>
    <hr/>
    <table width="100%">
        <thead>
        <tr>
            <th align="center" >
                Client
            </th>
            <th align="center" >
                Company
            </th>
            <th align="center" >
                Type
            </th>
            <th align="center" >
                Sending Date
            </th>
            <th align="center" >
                Recurring Period
            </th>
            <th align="center" >
                Created By
            </th>
            <th align="center" >
                Item Name
              </th>
              <th align="center" >
                Qty
              </th>
            <th align="center" >
              Amount
            </th>
            <th align="center" >
              Status
            </th>
        </tr>
        </thead>
        <tbody>
            @foreach($invoices as $invoice)
            <tr>
              <td align="center">{{ $invoice->client->name }}</td>
              <td align="center">{{ $invoice->companies->name }}</td>           
              <td align="center">{{ ($invoice->sending_type == 'one_time') ? 'One Time' : 'Recurring' }}</td>
              <td align="center">{{ Carbon\Carbon::parse($invoice->sending_date)->format("Y-m-d") }}</td>
              <td align="center">{{ $invoice->recurring_period }}</td>
              <td align="center">{{ $invoice->createdBy->name }}</td>           
              <td align="center">{{ $invoice->invoiceHistory->item_name }}</td>          
              <td align="center">{{ $invoice->invoiceHistory->quantity }}</td>          
              <td align="center">{{ $invoice->invoiceHistory->amount }}</td>          
              <td align="center">{{ ($invoice->invoiceHistory->is_paid == 1) ? 'Paid' : 'Pending' }}</td>       
            </tr>
            @endforeach
          </tbody>

    </table>
</body>

</html>