<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Contract Data Sheet</title>

    <!-- For PNG -->
    <link rel="icon" href="{{ asset('images/favicon.png') }}" type="image/png">

    <style>
        body { font-family: sans-serif; font-size: 12px; }
        h2 { margin-bottom: 10px; }
        .section { margin-bottom: 20px; }
        .align-center{ text-align: center; }
        .align-left{text-align: left;}
        .align-right{text-align: right;}
        .wd-3{ width: 3%; }
        .wd-20{ width: 20%; }
        .wd-30{ width: 20%; }
        .td{ border: 1px solid gray; height: 20px; padding: 5px; }
        .td2{ height: 20px;}
        .th{ height: 25px; border: 1px solid gray;background-color: gray; padding: 5px;}
        body{
          font-size: 11px;
        }
    </style>
</head>
<body>
    <div>
      <div style="display: inline-block; float; left;"><h2>Payment Summary Sheet</h2></div>
      <div style="display: inline-block; float: right; margin-top: 25px;">Original Copy</div>
    </div>
    
    <hr />
    <div class="section">
        <strong>Contract Information:</strong>
    </div>
    {{-- <pre>{{ print_r($summary, true) }}</pre> --}}
    <div class="sub-section" style="margin-left: 20px;">
      <table width="100%">
        <tr>
          <td class="wd-20">Contract No.</td>
          <td class="align-center wd-3">:</td>
          <td><strong>{{ $detail->contract_account_no }}</strong></td>
        </tr>
        <tr>
          <td class="wd-20">Client Name</td>
          <td class="align-center wd-3">:</td>
          <td><strong>{{ $detail->client_name }}</strong></td>
        </tr>
        <tr>
          <td class="wd-20">Amount</td>
          <td class="align-center wd-3">:</td>
          <td><strong>Php {{ number_format($detail->amount, 2, '.', ','); }}</strong></td>
        </tr>
        <tr>
          <td class="wd-20">Contact Person</td>
          <td class="align-center wd-3">:</td>
          <td>{{ $detail->contact_person }} / {{ $detail->contact_person_designation }}</td>
        </tr>
        <tr>
          <td class="wd-20">Account Representative</td>
          <td class="align-center wd-3">:</td>
          <td>{{ $detail->account_representative_name }}</td>
        </tr>
        <tr>
          <td class="wd-20">Contract Duration</td>
          <td class="align-center wd-3">:</td>
          <td>{{ $detail->contract_duration_start_at }} to {{ $detail->contract_duration_end_at }}</td>
        </tr>
      </table>
    </div>
    <br><br>
    <div>
      <table width="100%" cellspacing="0" cellpadding="0">
        <tr>
          <th class="align-left" colspan="4">Contract Details:</th>
        </tr>
        <tr>
          <th class="align-left th">Category</th>
          <th class="align-left th">Type</th>
          <th class="align-left th">Contract Detail</th>
          <th class="align-right th">Contract Amount</th>
        </tr>
        <tr style="height: 25px;">
          <td class="td">{{ $detail->contract_category_label }}</td>
          <td class="td">{{ $detail->contract_type_label }}</td>
          <td class="td">{{ $detail->contract_category_type_detail_label }}</td>
          <td class="align-right td">{{ number_format($detail->amount, 2, '.', ','); }}</td>
        </tr>
      </table>
    </div>
    <br><br>
    <div>
      <table width="100%" cellspacing="0" cellpadding="0">
        <tr>
          <th class="align-left" colspan="4">Payment Details:</th>
        </tr>
        <tr>
          <th class="align-center th" width="15%">OR No.</th>
          <th class="align-center th" width="15%">Type</th>
          <th class="align-center th" width="15%">To Pay</th>
          <th class="align-center th" width="15%">Amount</th>
          <th class="align-center th" width="15%">Change</th>
          <th class="align-center th">OR Date</th>
        </tr>

        @foreach($summary as $item)
          <tr>
              <td class="align-left td">{{ $item->or_number }}</td>
              <td class="align-left td">{{ $item->payment_type }}</td>
              <td class="align-right td">{{ number_format($item->amount_to_be_paid, 2, '.', ',') }}</td>
              <td class="align-right td">{{ number_format($item->amount_tendered, 2, '.', ',') }}</td>
              <td class="align-right td">{{ number_format($item->amount_change, 2, '.', ',') }}</td>
              <td class="align-center td">{{ $item->or_date }}</td>
          </tr>
        @endforeach
      </table>
      <br/><br/>
      <table width="100%">
        <tr>
          <td class="wd-30 td2">Total Payables</td>
          <td class="align-center wd-3 td2">:</td>
          <td>Php {{ number_format($detail->payables, 2, '.', ','); }}</td>
        </tr>
        <tr>
          <td class="wd-30 td2">Payment Made</td>
          <td class="align-center wd-3 td2">:</td>
          <td>Php {{ number_format($detail->total_payment, 2, '.', ','); }}</td>
        </tr>
        <tr>
          <td class="wd-30 td2">Balance</td>
          <td class="align-center wd-3 td2">:</td>
          <td>Php {{ number_format($detail->balance, 2, '.', ','); }}</td>
        </tr>
        <tr>
          <td class="wd-30 td2">Excess Payment</td>
          <td class="align-center wd-3 td2">:</td>
          <td>Php {{ number_format($detail->excess, 2, '.', ','); }}</td>
        </tr>
        <tr>
          <td colspan="3">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="3">&nbsp;</td>
        </tr>
        <tr>
          <td class="align-center" colspan="3"><em>*** Nothing Follows ***</em></td>
        </tr>
        <tr>
          <td colspan="3">&nbsp;</td>
        </tr>
        <tr>
          <td class="align-center" colspan="3"><em>*** This document is system-generated. No need for signature. ***</em></td>
        </tr>
        <tr>
          <td class="align-center" colspan="3"><em>Document is generated by {{ config('app.name') }} ***</em></td>
        </tr>
      </table>
    </div>
    <br><br>
    <hr />
    

    <!-- Add more fields as needed -->

</body>
</html>
