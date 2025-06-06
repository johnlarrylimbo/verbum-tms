<!DOCTYPE html>
<html>
<head>
    <title>UIC-BEDCAS</title>
</head>
<body>
    <table style="width: 705px !important; margin: 0px auto;">
			<tr style="height: 119px;">
				<td style="background-image: url('https://newbedsis.uic.edu.ph/assets/img/placeholder/email_header2.jpg'); background-repeat: no-repeat !important;"></td>
			</tr>
			<tr>
				<td>
					<div style="margin-left: 10px; margin-right: 10px;">
						<table>
							<tr>
								<td>
									<p>Hi <b>{{ $client_name }}</b>,</p>
									<p style="margin-top: 25px;">Praised be Jesus and Mary!</p>
									<p style="margin-top: 31px;">Your payment transaction was successful. Please see details below for your information.</p>
									<p><strong>Payment Transaction Details:</strong></p>
									{{-- <p>
										<table width="100%">
											<tr>
												<td class="align-left" width="18%">Account Name</td>
												<td class="align-center" width="2%">:</td>
												<td>{{ $mailData['client_full_name'] }}</td>
											</tr>
											<tr>
												<td class="align-left">Payment For</td>
												<td class="align-center">:</td>
												<td>{{ $mailData['payment_for'] }}</td>
											</tr>
											<tr>
												<td class="align-left">Amount</td>
												<td class="align-center">:</td>
												<td>{{ $mailData['or_cash_amount'] }}</td>
											</tr>
											<tr>
												<td class="align-left">OR Number</td>
												<td class="align-center">:</td>
												<td>{{ $mailData['or_number'] }}</td>
											</tr>
											<tr>
												<td class="align-left">Transacted by</td>
												<td class="align-center">:</td>
												<td>{{ $mailData['transacted_by'] }}</td>
											</tr>
											<tr>
												<td class="align-left">Transaction Date</td>
												<td class="align-center">:</td>
												<td>{{ $mailData['transaction_date'] }}</td>
											</tr>
										</table>
									</p> --}}
                                    <br/>
									<p>If you have inquiries regarding this email, please email info@uic.edu.ph.</p>
									<p>Thank you for choosing UIC. For more information about the school, you may visit our website at https://www.uic.edu.ph.</p>
									<p>You can also visit our Official Facebook page at https://www.facebook.com/uicph</p>
									<p style="margin-top: 49px;">Thank you</p>
									<p style="margin-top: 30px;"><strong>This is a system-generated email. Do not reply.</strong></p>
								</td>
							</tr>
						</table>
					</div>
				</td>
			</tr>
			<tr style="height: 40px;">
				<td style="background-image: url('https://newbedsis.uic.edu.ph/assets/img/placeholder/email_footer.jpg')"></td>
			</tr>
		</table>

</body>
</html>