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
        body { font-family: sans-serif; font-size: 12px; }
        h2 { margin-bottom: 10px; }
        .section { margin-bottom: 20px; }
        .align-center{ text-align: center; }
        .wd-3{ width: 3%; }
        .wd-20{ width: 20%; }
    </style>
</head>
<body>
    <h2>Client Profile Data Sheet</h2>
    <hr />
    <div class="section">
        <strong>Client Personal Information:</strong>
    </div>
    {{-- <div class="sub-section" style="margin-left: 20px;">
      <table width="100%">
        <tr>
          <td class="wd-20">Client Code</td>
          <td class="align-center wd-3">:</td>
          <td><strong>{{ $client->client_code }}</strong></td>
        </tr>
        <tr>
          <td class="wd-20">Client Name</td>
          <td class="align-center wd-3">:</td>
          <td><strong>{{ $client->client_name }}</strong></td>
        </tr>
        <tr>
          <td>Birthdate</td>
          <td class="align-center">:</td>
          <td>{{ $client->birthdate }}</td>
        </tr>
        <tr>
          <td>Address</td>
          <td class="align-center">:</td>
          <td>{{ $client->client_address }}</td>
        </tr>
        <tr>
          <td>Barangay</td>
          <td class="align-center">:</td>
          <td>{{ $client->barangay_label }}</td>
        </tr>
        <tr>
          <td>City / Municipality</td>
          <td class="align-center">:</td>
          <td>{{ $client->city_municipality_label }}</td>
        </tr>
        <tr>
          <td>Province</td>
          <td class="align-center">:</td>
          <td>{{ $client->province_label }}</td>
        </tr>
        <tr>
          <td>Region</td>
          <td class="align-center">:</td>
          <td>{{ $client->region_label }}</td>
        </tr>
        <tr>
          <td>Country</td>
          <td class="align-center">:</td>
          <td>{{ $client->country_label }}</td>
        </tr>
        <tr>
          <td>Citizenship / Nationality</td>
          <td class="align-center">:</td>
          <td>{{ $client->citizenship_nationality_label }}</td>
        </tr>
        <tr>
          <td>Religion</td>
          <td class="align-center">:</td>
          <td>{{ $client->religion_label }}</td>
        </tr>
        <tr>
          <td>Email Address</td>
          <td class="align-center">:</td>
          <td>{{ $client->email_address }}</td>
        </tr>
        <tr>
          <td>Contact No.</td>
          <td class="align-center">:</td>
          <td>{{ $client->telephone_number }}</td>
        </tr>
        <tr>
          <td>Affiliated Parish</td>
          <td class="align-center">:</td>
          <td>{{ $client->parish_name }}</td>
        </tr>
        <tr>
          <td>Affiliated GKK</td>
          <td class="align-center">:</td>
          <td>{{ $client->bec_name }}</td>
        </tr>
      </table>
    </div>
    <br><br>
    <hr />
    <div class="section">
      <strong>Spouse Information:</strong>
    </div>
    <div class="sub-section" style="margin-left: 20px;">
      <table width="100%">
        <tr>
          <td class="wd-20">Spouse Name</td>
          <td class="align-center wd-3">:</td>
          <td>{{ $client->spouse_name }}</td>
        </tr>
        <tr>
          <td class="wd-20">Spouse Birthdate</td>
          <td class="align-center wd-3">:</td>
          <td>{{ $client->spouse_birthdate }}</td>
        </tr>
        <tr>
          <td class="wd-20">Address</td>
          <td class="align-center wd-3">:</td>
          <td>{{ $client->spouse_address }}</td>
        </tr>
        <tr>
          <td>Barangay</td>
          <td class="align-center">:</td>
          <td>{{ $client->spouse_barangay_label }}</td>
        </tr>
        <tr>
          <td>City / Municipality</td>
          <td class="align-center">:</td>
          <td>{{ $client->spouse_city_municipality_label }}</td>
        </tr>
        <tr>
          <td>Province</td>
          <td class="align-center">:</td>
          <td>{{ $client->spouse_province_label }}</td>
        </tr>
        <tr>
          <td>Region</td>
          <td class="align-center">:</td>
          <td>{{ $client->spouse_region_label }}</td>
        </tr>
        <tr>
          <td>Country</td>
          <td class="align-center">:</td>
          <td>{{ $client->spouse_country_label }}</td>
        </tr>
        <tr>
          <td>Citizenship / Nationality</td>
          <td class="align-center">:</td>
          <td>{{ $client->spouse_citizenship_nationality_label }}</td>
        </tr>
        <tr>
          <td>Religion</td>
          <td class="align-center">:</td>
          <td>{{ $client->spouse_religion_label }}</td>
        </tr>
        <tr>
          <td>Wedding Date</td>
          <td class="align-center">:</td>
          <td>{{ $client->wedding_date }}</td>
        </tr>
        <tr>
          <td colspan="3">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="3">&nbsp;</td>
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
    </div> --}}

    <!-- Add more fields as needed -->

</body>
</html>
