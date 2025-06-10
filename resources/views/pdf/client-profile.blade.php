<!DOCTYPE html>
<html>
<head>
    <title>Client Profile</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        h2 { margin-bottom: 10px; }
        .section { margin-bottom: 20px; }
    </style>
</head>
<body>
    <h2>Client Profile Data Sheet</h2>
    <hr />
    <div class="section">
        <strong>Client Personal Information:</strong>
    </div>
    <div class="sub-section" style="margin-left: 20px;">
        <strong>Client Name:</strong> {{ $client->client_name }}<br>
    </div>
    <br><br>
    <hr />
    <div class="section">
      <strong>Spouse Information:</strong>
    </div>
    <div class="sub-section" style="margin-left: 20px;">
      <strong>Spouse Name:</strong> -<br>
    </div>

    <!-- Add more fields as needed -->

</body>
</html>
