<x-mail::message>
  Dear {{ $user ? $user : 'Customer' }},

  Thank you for using our services. To complete your verification or authentication process, please use the following
  One-Time Password (OTP) code.

  OTP Code: [{{ $otp }}]

  This code is valid for 1 minute. Please enter it in the otp confirm form to verify your email address and activate
  your account.

  If you did not request this code, please ignore this email.

  Thank you for using our application.

  Best regards, <br>
  {{ config('app.name') }}
</x-mail::message>