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
        .td{ border: 1px solid gray; height: 20px; padding: 5px; }
        .th{ height: 25px; border: 1px solid gray;background-color: gray; padding: 5px;}
    </style>
</head>
<body>
    <div>
      <div style="display: inline-block; float; left;"><h2>Contract Data Sheet</h2></div>
      <div style="display: inline-block; float: right; margin-top: 25px;">Original Copy</div>
    </div>
    
    <hr />
    <div class="section">
        <strong>Contract Information:</strong>
    </div>
    <div class="sub-section" style="margin-left: 20px;">
      <table width="100%">
        <tr>
          <td class="wd-20">Contract No.</td>
          <td class="align-center wd-3">:</td>
          <td><strong>{{ $contract->contract_account_no }}</strong></td>
        </tr>
        <tr>
          <td class="wd-20">Client Name</td>
          <td class="align-center wd-3">:</td>
          <td><strong>{{ $contract->client_name }}</strong></td>
        </tr>
        <tr>
          <td class="wd-20">Amount</td>
          <td class="align-center wd-3">:</td>
          <td><strong>Php {{ number_format($contract->amount, 2, '.', ','); }}</strong></td>
        </tr>
        <tr>
          <td class="wd-20">Contact Person</td>
          <td class="align-center wd-3">:</td>
          <td>{{ $contract->contact_person }} / {{ $contract->contact_person_designation }}</td>
        </tr>
        <tr>
          <td class="wd-20">Account Representative</td>
          <td class="align-center wd-3">:</td>
          <td>{{ $contract->account_representative_name }}</td>
        </tr>
        <tr>
          <td class="wd-20">Contract Duration</td>
          <td class="align-center wd-3">:</td>
          <td>{{ $contract->contract_duration_start_at }} to {{ $contract->contract_duration_end_at }}</td>
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
          <th class="align-right th">Amount</th>
        </tr>
        <tr style="height: 25px;">
          <td class="td">{{ $contract->contract_category_label }}</td>
          <td class="td">{{ $contract->contract_type_label }}</td>
          <td class="td">{{ $contract->contract_category_type_detail_label }}</td>
          <td class="align-right td">{{ number_format($contract->amount, 2, '.', ','); }}</td>
        </tr>
      </table>
    </div>
    <br><br><br><br><br><br>
    <hr />
    <br><br>
    
    <div>
      <div style="display: inline-block; float; left;"><h2>Contract Data Sheet</h2></div>
      <div style="display: inline-block; float: right; margin-top: 25px;">Client Copy</div>
    </div>
    
    <hr />
    <div class="section">
        <strong>Contract Information:</strong>
    </div>
    <div class="sub-section" style="margin-left: 20px;">
      <table width="100%">
        <tr>
          <td class="wd-20">Contract No.</td>
          <td class="align-center wd-3">:</td>
          <td><strong>{{ $contract->contract_account_no }}</strong></td>
        </tr>
        <tr>
          <td class="wd-20">Client Name</td>
          <td class="align-center wd-3">:</td>
          <td><strong>{{ $contract->client_name }}</strong></td>
        </tr>
        <tr>
          <td class="wd-20">Amount</td>
          <td class="align-center wd-3">:</td>
          <td><strong>Php {{ number_format($contract->amount, 2, '.', ','); }}</strong></td>
        </tr>
        <tr>
          <td class="wd-20">Contact Person</td>
          <td class="align-center wd-3">:</td>
          <td>{{ $contract->contact_person }} / {{ $contract->contact_person_designation }}</td>
        </tr>
        <tr>
          <td class="wd-20">Account Representative</td>
          <td class="align-center wd-3">:</td>
          <td>{{ $contract->account_representative_name }}</td>
        </tr>
        <tr>
          <td class="wd-20">Contract Duration</td>
          <td class="align-center wd-3">:</td>
          <td>{{ $contract->contract_duration_start_at }} to {{ $contract->contract_duration_end_at }}</td>
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
          <th class="align-right th">Amount</th>
        </tr>
        <tr style="height: 25px;">
          <td class="td">{{ $contract->contract_category_label }}</td>
          <td class="td">{{ $contract->contract_type_label }}</td>
          <td class="td">{{ $contract->contract_category_type_detail_label }}</td>
          <td class="align-right td">{{ number_format($contract->amount, 2, '.', ','); }}</td>
        </tr>
      </table>
    </div>
    <br><br>
    <hr />
    

    <!-- Add more fields as needed -->

</body>
</html>
