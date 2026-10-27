<?php
// 1. Setup your core variables
$to      = 'recipient@example.com';
$subject = 'Inquiry from Website';
$message = 'Hello, this is a test email sent natively via HostGator hosting.';

// 3. Construct headers correctly (Using CRLF "\r\n")
$headers   = [];
$headers[] = 'From: ' . ADMIN_EMAIL;
$headers[] = 'Reply-To: ' . ADMIN_EMAIL;
$headers[] = 'X-Mailer: PHP/' . phpversion();
$headers[] = 'MIME-Version: 1.0';
$headers[] = 'Content-Type: text/plain; charset=utf-8';

// Flatten the headers array into a string separated by newlines
$headers_string = implode("\r\n", $headers);

// 4. The 5th parameter (-f) tells HostGator's Sendmail the exact envelope sender
$additional_params = '-f' . ADMIN_EMAIL;

// 5. Execute the mail function
$success = mail($to, $subject, $message, $headers_string, $additional_params);

if ($success) {
    echo "Success: Email accepted by HostGator mail queue.";
} else {
    echo "Error: HostGator server rejected the mail execution.";
}
?>