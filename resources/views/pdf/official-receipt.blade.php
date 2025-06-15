<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Client Profile</title>

    <!-- For PNG -->
    <link rel="icon" href="{{ asset('images/favicon.png') }}" type="image/png">

    <style>
        body { font-family: sans-serif; font-size: 10px; }
        h2 { margin-bottom: 10px; }
        .section { margin-bottom: 20px; display: inline-block; width: 48%; }
        .align-center{ text-align: center; }
        .align-left{text-align: left;}
        .align-right{text-align: right;}
        .wd-3{ width: 3%; }
        .wd-20{ width: 20%; }
        .table-1{ border: 1px solid black;}
        .th-sold-to{ border: 1px solid black; height: 20px;;}
        .td-info{height: 20px;  }
        .td-info2{height: 25px;  }
        .td-info3{height: 23px; border-bottom: 1px solid black;  }
        .td-head{height: 25px; font-weight: bold; background-color: gray; color: black; border-left: 1px solid white;  }
        .td-head2{height: 25px; font-weight: bold; background-color: gray; color: black; border-right: 1px solid white; border-left: 1px solid black;  }
    </style>
</head>
<body>
    {{-- <h2>Client Profile Data Sheet</h2>
    <hr /> --}}
    <div class="section" style="margin-top: 100px; margin-left: -15px;">
      <table width="100%">
        <tr>
          <td colspan="2">Original Copy</td>
        </tr>
        <tr>
          <td>
            <h2>SERVICE</h2>
            <h1 style="margin-top: -15px; margin-bottom: 0px;">INVOICE</h1>
          </td>
          <td>
            <div style="float: right;">
              Cash : {{ number_format($detail->payment_made, 2, '.', ',') }} <br/>
              Change : {{ number_format($detail->amount_change, 2, '.', ',') }}
            </div>
          </td>
        </tr>
        <tr>
          <td colspan="2">
            <div style="float: right; margin-top: 3px;">
              <table>
                <tr>
                  <td>Date:</td>
                  <td>{{ $detail->or_date; }}</td>
                </tr>
              </table>
            </div>
          </td>
        </tr>
      </table><br /><br />
      <table width="100%" class="table-1" cellspacing="0">
        <tr>
          <th class="align-left th-sold-to" colspan="3">SOLD TO</th>
        </tr>
        <tr>
          <td class="td-info" width="22%">Customer</td>
          <td width="3%">:</td>
          <td>{{ $detail->client_name; }}</td>
        </tr>
        <tr>
          <td class="td-info" width="22%">Code</td>
          <td width="3%">:</td>
          <td>{{ $detail->contract_account_no; }}</td>
        </tr>
        <tr>
          <td class="td-info" width="22%">Transaction #</td>
          <td width="3%">:</td>
          <td>{{ $detail->or_number; }}</td>
        </tr>
        <tr>
          <td class="td-info" width="22%">Sales Type</td>
          <td width="3%">:</td>
          <td>{{ $detail->payment_type; }}</td>
        </tr>
      </table>
      <br/>
      <table width="100%" class="table-1" cellspacing="0">
        <tr>
          <td class="align-center td-head2">Item Description</td>
          <td class="align-center td-head">Qty</td>
          <td class="align-center td-head">Unit Price</td>
          <td class="align-center td-head">Amount</td>
        </tr>
        @foreach($result as $item)
          <tr>
            <td class="td-info2 align-left">
              {{ $item->contract_category_type_detail_label; }}<br/>
              {{ $item->nature_description; }}
            </td>
            <td class="td-info2 align-center">1</td>
            <td class="td-info2 align-right">{{ number_format($item->unit_price, 2, '.', ',') }}</td>
            <td class="td-info2 align-right">{{ number_format($item->amount_to_be_paid, 2, '.', ',') }}</td>
          </tr>
        @endforeach
      </table>
      <div style="margin-left: 140px; margin-top: 10px; width: 60%">
        <table class="table-1" width="100%" cellspacing="0">
          <tr>
            <td class="td-info3" width="70%">TOTAL SALES</td>
            <td class="td-info3 align-right">{{ number_format($detail->total_sales, 2, '.', ',') }}</td>
          </tr>
          <tr>
            <td class="td-info3">Less Discount</td>
            <td class="td-info3 align-right"></td>
          </tr>
          <tr>
            <td class="td-info3">Less Witholding Tax</td>
            <td class="td-info3 align-right"></td>
          </tr>
          <tr>
            <td class="td-info3">Payment made</td>
            <td class="td-info3 align-right">{{ number_format($detail->total_payment_made, 2, '.', ',') }}</td>
          </tr>
          <tr>
            <td class="td-info3">Total Payables</td>
            <td class="td-info3 align-right">{{ number_format($detail->total_payables, 2, '.', ',') }}</td>
          </tr>
          <tr>
            <td class="td-info3">Balance</td>
            <td class="td-info3 align-right">{{ number_format($detail->balance, 2, '.', ',') }}</td>
          </tr>
        </table>
      </div>
      
        {{-- <strong>Client Personal Information:</strong> --}}
    </div>

    <div class="section" style="margin-top: 35px; margin-left: 50px; margin-right: -20px;">
      <table width="100%">
        <tr>
          <td colspan="2">Customer's Copy</td>
        </tr>
        <tr>
          <td>
            <h2>SERVICE</h2>
            <h1 style="margin-top: -15px; margin-bottom: 0px;">INVOICE</h1>
          </td>
          <td>
            <div style="float: right;">
              Cash : {{ number_format($detail->payment_made, 2, '.', ',') }} <br/>
              Change : {{ number_format($detail->amount_change, 2, '.', ',') }}
            </div>
          </td>
        </tr>
        <tr>
          <td colspan="2">
            <div style="float: right; margin-top: 3px;">
              <table>
                <tr>
                  <td>Date:</td>
                  <td>{{ $detail->or_date; }}</td>
                </tr>
              </table>
            </div>
          </td>
        </tr>
      </table><br /><br />
      <table width="100%" class="table-1" cellspacing="0">
        <tr>
          <th class="align-left th-sold-to" colspan="3">SOLD TO</th>
        </tr>
        <tr>
          <td class="td-info" width="22%">Customer</td>
          <td width="3%">:</td>
          <td>{{ $detail->client_name; }}</td>
        </tr>
        <tr>
          <td class="td-info" width="22%">Code</td>
          <td width="3%">:</td>
          <td>{{ $detail->contract_account_no; }}</td>
        </tr>
        <tr>
          <td class="td-info" width="22%">Transaction #</td>
          <td width="3%">:</td>
          <td>{{ $detail->or_number; }}</td>
        </tr>
        <tr>
          <td class="td-info" width="22%">Sales Type</td>
          <td width="3%">:</td>
          <td>{{ $detail->payment_type; }}</td>
        </tr>
      </table>
      <br/>
      <table width="100%" class="table-1" cellspacing="0">
        <tr>
          <td class="align-center td-head2">Item Description</td>
          <td class="align-center td-head">Qty</td>
          <td class="align-center td-head">Unit Price</td>
          <td class="align-center td-head">Amount</td>
        </tr>
        @foreach($result as $item)
          <tr>
            <td class="td-info2 align-left">
              {{ $item->contract_category_type_detail_label; }}<br/>
              {{ $item->nature_description; }}
            </td>
            <td class="td-info2 align-center">1</td>
            <td class="td-info2 align-right">{{ number_format($item->unit_price, 2, '.', ',') }}</td>
            <td class="td-info2 align-right">{{ number_format($item->amount_to_be_paid, 2, '.', ',') }}</td>
          </tr>
        @endforeach
      </table>
      <div style="margin-left: 140px; margin-top: 10px; width: 60%">
        <table class="table-1" width="100%" cellspacing="0">
          <tr>
            <td class="td-info3" width="70%">TOTAL SALES</td>
            <td class="td-info3 align-right">{{ number_format($detail->total_sales, 2, '.', ',') }}</td>
          </tr>
          <tr>
            <td class="td-info3">Less Discount</td>
            <td class="td-info3 align-right"></td>
          </tr>
          <tr>
            <td class="td-info3">Less Witholding Tax</td>
            <td class="td-info3 align-right"></td>
          </tr>
          <tr>
            <td class="td-info3">Payment made</td>
            <td class="td-info3 align-right">{{ number_format($detail->total_payment_made, 2, '.', ',') }}</td>
          </tr>
          <tr>
            <td class="td-info3">Total Payables</td>
            <td class="td-info3 align-right">{{ number_format($detail->total_payables, 2, '.', ',') }}</td>
          </tr>
          <tr>
            <td class="td-info3">Balance</td>
            <td class="td-info3 align-right">{{ number_format($detail->balance, 2, '.', ',') }}</td>
          </tr>
        </table>
      </div>
      
        {{-- <strong>Client Personal Information:</strong> --}}
    </div>
    

    <!-- Add more fields as needed -->

</body>
</html>
