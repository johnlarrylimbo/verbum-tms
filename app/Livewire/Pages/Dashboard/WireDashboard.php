<?php

namespace App\Livewire\Pages\Dashboard;

use Livewire\Component;
use Illuminate\Support\Facades\Mail;

class WireDashboard extends Component
{
	public bool $showSuccessMessage = false;
	public bool $showErrorMessage = false;
	public string $addMessage = '';
	// public string $addErrorMessage = '';
    public function sendMail($email)
    {
        // $mailData['email'] = $email;
        // $mailData['subject'] = 'Mail Check';

         ##let's send some email
      $mailData = [
        'client_name' => 'JOHN LARRY',
        // 'client_full_name' => $student_name,
        // 'payment_for' => $payment_for,
        // 'or_cash_amount' => number_format($or_cash_amount, 2),
        // 'or_number' => $or_no,
        // 'or_cheque_amount' => number_format($or_cheque_amount, 2),
        // 'or_cheque_bank' => $or_cheque_bank,
        // 'or_cheque_number' => $or_cheque_number,
        // 'transacted_by' => $transacted_by,
        // 'transaction_date' => $transaction_date,
        // 'grade_level_long' => $grade_level_long,
        // 'school_year_numeric' => $school_year_numeric,
        // 'student_number' => $student_number,
        'subject' => 'No-Reply: UIC-BED CASHIERING (Payment Received)',
        'email' => explode(',', str_replace(' ', '', $email))
      ];

      try {

        Mail::send('emails.email-temp', $mailData, function($message) use($mailData) {
            $message->to($mailData['email'])
                ->subject($mailData['subject']);
        });

				$this->addMessage = 'Email sent successfully.';
        $this->showSuccessMessage = true;
			}
			catch (Exception $e) {
					// Optional: Show error to user
					$this->addMessage = 'Failed to send email to recipient.';
        	$this->showErrorMessage = true;
					// session()->flash('error', 'Mail failed to send: ' . $e->getMessage());
					// Or: $this->dispatchBrowserEvent('mail-sent', ['status' => 'error', 'message' => $e->getMessage()]);
			}

    }

    public $mail_content;
    public function sendToAll()
    {
        $students = Student::all();
        $content = $this->mail_content;
        foreach($students as $student){
            dispatch(function() use($student, $content){
                $mailData['email'] = $student->email;
                $mailData['subject'] = 'Test Mail';
                $mailData['content'] = $content;

                Mail::send('emails.send-to-all', $mailData, function($message) use($mailData) {
                    $message->to($mailData['email'])
                        ->subject($mailData['subject']);
                });
            });
        }

        // $this->dispatchBrowserEvent('success', ['message'=>'Mail sent successfully']);
    }

    public function render()
    {
        return view('livewire.pages.dashboard.dashboard-index');
    }
}
