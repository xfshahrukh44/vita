<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{$pdf_name}}</title>
</head>

    <style>
        table,td,th{
            border: 1px solid black;
        }
        label, input, td, th{
            font-size: 10px;
        }
        th{
            text-align: center;
        }
        .details{
            width: 10%;
        }
        .master_img{
            width: 25%; 
            margin-left: 24%;
            /* margin-top: 10%; */
        }
        .gold_img{
            width: 25%; 
            margin-left: 50%;
            margin-top: -19%;
            margin-bottom: 10%;
        }
        .headin_gs{
            /* font-family: inherit !important; */
            text-align: center;
            margin-top: -9%; 
        }
        h5{
            margin-bottom: -3px;
            font-size: 18px;
        }
        h6{
            font-size: 17px;
        }
        .bills, .date{
            width: 30%;
        }
        .date_lable{
            margin-left: 24.5%;
        }
        .ms{
            width: 90%;
        }
        table{
            width: 100%;
        }
        .border_none{
            border: none;
            /* border-bottom: 1px solid gray; */
            margin-top:1%;
        }
    </style>
    <body>
        <div class="master_img">
            <img class="img-fluid" src="../public/pdf_img/mg-02.jpg" alt="Master">
        </div>
    
        <div class="gold_img">
            <img class="img-fluid" src="../public/pdf_img/mg-01.jpg" alt="Gold">
        </div>
    
        <div class="headin_gs">
            <p style="font-size: 14px;">Plot 247, sector 16b, Malik Anwar goth, Gabol town, North Karachi.</p>
        </div>
    
        <div>
            <br>
            <label for="" style="position: absolute; top:0.8rem;" ><strong>Customer Name:</strong>
                <input type="text" class="border_none" value="{{$invoice->customer ? $invoice->customer->name : NULL}}" style="margin-top: 0.72%;">
            </label>
           
            <label for="" class="" style="position: absolute; top:9.1rem; left:35rem;"><strong>Rider Name:</strong>
                 <input type="text" class="border_none" value="{{$invoice->rider_id ? return_user_name($invoice->rider_id) : NULL}}"  style="margin-top: 3.1%;">
            </label>
            
            <label style="position: absolute; top:12.35rem; left:35.02rem;width: 20rem;"><strong>Supplier</strong></label>
            <label class="" style="position: absolute; top:11.5rem; left:38.47rem;width: 20rem;"><strong>:</strong>
                <input type="text" class="border_none" value="Master material" style="margin-top: 1.55%;">
            </label>

            <label style="position: absolute; top:13.62rem; left:35.02rem;width: 20rem;"><strong>Contact</strong></label>
            <label for="" class="" style="position: absolute; top:12.8rem; left:38.46rem;width: 20rem;"><strong>:</strong>
                <input type="text" class="border_none" value="0311-1039785"  style="margin-top: 1.55%;">
            </label>

            <label style="position: absolute; top:14.825rem; left:35.02rem;width: 20rem;"><strong>Date</strong></label>
            <label for="" class="date_lable"  style="position: absolute; top:14rem; left:27.58rem; width: 20rem;"><strong>:</strong>
                <input type="text" class="date border_none" value="{{return_date_pdf($invoice->created_at)}}" style="margin-top: 1.65%;">
            </label>
        </div>
        {{-- <br> --}}
        <div>
            <label for="" style="margin-top: 1%;"><strong>Invoice</strong></label>
            <label for="" style="margin-top: 1%; margin-left: 2.355rem"><strong>:</strong></label>
            <input type="text" class="border_none" value="{{$invoice->id}}" style="margin-top: 1.3%;">
        </div>
        <div>
            <label for=""><strong>Order</strong></label>
            <label for="" style="margin-left: 2.81rem"><strong>:</strong></label>
            <input type="text" class="border_none" value="{{$invoice->order ? $invoice->order->id : NULL}}" style="margin-top: 0.27%;">
        </div>
        <div style="margin-top:-1%;">
            <label for=""><strong>Shop name & #</strong></label>
            <label for="" style="margin-left: 0rem"><strong>:</strong></label>
            <input type="text" class="border_none" value="{{$invoice->customer ? $invoice->customer->shop_name . ' - ' . $invoice->customer->shop_number : NULL}}" style="margin-top: 0.25%;">
        </div>
        <div style="margin-top:-1%;">
            <label for=""><strong>Mobile #</strong></label>
            <label for="" style="margin-left: 2.01rem"><strong>:</strong></label>
            <input type="text" class="border_none" value="{{$invoice->customer ? $invoice->customer->contact_number : NULL}}" style="margin-top: 0.3%;">
        </div>
        <div style="margin-top:-1%;">
            <label for=""><strong>Market & area</strong></label>
            <label for="" style="margin-left: 0.41rem"><strong>:</strong></label>
            <input type="text" class="border_none" value="{{($invoice->customer && $invoice->customer->market && $invoice->customer->market->area) ? $invoice->customer->market->name.'-'.$invoice->customer->market->area->name : NULL}}" style="margin-top: 0.3%; width:60%;">
        </div>
        <div>
            <img src="../public/pdf_img/NULK.png" alt="" style="width: 25%; position:absolute; z-index:-111; left:35%; opacity:0.4;">
        </div>
    
        <table>
            <tr>
              <th>Qty.</th>
              <th>Details</th>
              <th>Rate</th>
              <th>Amount</th>
            </tr>
            @foreach($invoice->invoice_products as $invoice_product)
                <tr>
                    <td style="text-align: center">{{number_format(intval($invoice_product->quantity))}}</td>
                    <td style="text-align: center">{{$invoice_product->product ? ($invoice_product->product->category->name . ' - ' . $invoice_product->product->brand->name . ' - ' . $invoice_product->product->article) : NULL}}</td>
                    <td style="text-align: center">{{number_format(intval($invoice_product->price))}}</td>
                    <td style="text-align: center">{{number_format(intval($invoice_product->quantity * $invoice_product->price))}}</td>
                </tr>
            @endforeach
            <tr>
                <td style="border: none"></td>
                <td style="border: none"></td>
                <td style="border: none; font-size:14px;">Total</td>
                <td style="text-align: right">{{number_format(intval($invoice->total))}}</td>
            </tr>
            <tr>
                <td style="border: none"></td>
                <td style="border: none"></td>
                <td style="border: none; font-size:14px;">Previous Bal.</td>
                <td style="text-align: right">{{number_format(intval($invoice->previous_balance))}}</td>
                <!-- <td style="text-align: right">{{$invoice->customer->outstanding_balance + $invoice->amount_pay - $invoice->total}}</td> -->
                <!-- <td style="text-align: right">{{$invoice->customer->outstanding_balance - $invoice->amount_pay}}</td> -->
            </tr>
            <tr>
                <td style="border: none"></td>
                <td style="border: none"></td>
                <td style="border: none; font-size:14px;">Total Due</td>
                <td style="text-align: right">{{number_format(intval($invoice->total + $invoice->previous_balance))}}</td>
                <!-- <td style="text-align: right">{{$invoice->customer->outstanding_balance + $invoice->amount_pay}}</td> -->
                <!-- <td style="text-align: right">{{$invoice->customer->outstanding_balance - $invoice->amount_pay}}</td> -->
            </tr>
            <tr>
                <td style="border: none"></td>
                <td style="border: none">
                    <input type="text">
                    <br>
                    <label  style="margin-left:5%;" for="">Customer Receiving</label>
                </td>
                <td style="border: none; font-size:14px;">Amount Received</td>
                <td style="text-align: right">{{($invoice->amount_pay != 0) ? number_format(intval($invoice->amount_pay)) : ""}}</td>
            </tr>
            <tr>
                <td style="border: none"></td>
                <td style="border: none"></td>
                <td style="border: none; font-size:14px;">Balance Due</td>
                <td style="text-align: right">{{(($invoice->amount_pay != 0) ? number_format(intval(($invoice->total + $invoice->previous_balance) - $invoice->amount_pay)) : "")}}</td>
                <!-- <td style="text-align: right">{{$invoice->customer->outstanding_balance}}</td> -->
            </tr>
        </table>
        <!-- message -->
        <div class="row text-center">
            <p style="font-size:12px;">This is a computer generated invoice and requires no signature</p>
        </div>
        <!-- logos -->
        <div class="row text-center">
            <!-- sui dhaaga -->
            <div class="text-center" style="margin-right:4.8rem;">
                <img src="../public/img/sdpl4.png" alt="core2plusIcon" style="width: 10%; margin-top:-0.08rem;">
                <p style="font-size:8px;">domain-name.com</p>
            </div>
            <!-- powered by core2plus -->
            <div class="text-center" style="margin-left:4.8rem;">
                <p style="font-size:8px;">Powered By</p>
                <img src="../public/img/core2plusIcon.jpg" alt="core2plusIcon" style="width: 6%; margin-top:-0.5rem;">
                <p style="font-size:8px;">core2plus.com</p>
            </div>
        </div>
    </body>
</html>