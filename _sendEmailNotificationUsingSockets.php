<?php
include_once  ('-RMLCMS/processors/settings.php');
function get_smtp_response($socket) {
    $response = "";
    while (true) {
        if (!($line = fgets($socket, 512))) {
            break;
        }
        $response .= $line;
        // SMTP defines the end of a multi-line response status with a space 
        // after the 3-digit code (e.g., "250-FirstLine\r\n250 LastLine\r\n")
        if (strlen($line) >= 4 && $line[3] === ' ') {
            break;
        }
    }
    return $response;
}

/**
 * Natively sends an email over a raw SSL/TLS socket connection.
 */
function send_native_smtp($to, $subject, $body, $smtp_user, $smtp_pass, $smtp_port = 465) {
    // 1. Connect securely via SSL/TLS socket
    $errno = 0;
    $errstr = '';
    
    // Assumes MAIL_HOST is a predefined global constant
    $socket = fsockopen("ssl://" . MAIL_HOST, $smtp_port, $errno, $errstr, 15);
    
    if (!$socket) {
        return "Connection Failed: $errstr ($errno)";
    }

    // 2. Start the SMTP handshake conversation
    get_smtp_response($socket);
    
    $server_name = isset($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'] : 'localhost';
    fwrite($socket, "EHLO " . $server_name . "\r\n");
    get_smtp_response($socket);

    // 3. Authenticate with credentials
    fwrite($socket, "AUTH LOGIN\r\n");
    get_smtp_response($socket);
    
    fwrite($socket, base64_encode($smtp_user) . "\r\n");
    get_smtp_response($socket);
    
    fwrite($socket, base64_encode($smtp_pass) . "\r\n");
    get_smtp_response($socket);

    // 4. Set the Envelope Sender and Recipient
    fwrite($socket, "MAIL FROM: <" . $smtp_user . ">\r\n");
    get_smtp_response($socket);
    
    fwrite($socket, "RCPT TO: <" . $to . ">\r\n");
    get_smtp_response($socket);

    // 5. Send Data payload
    fwrite($socket, "DATA\r\n");
    get_smtp_response($socket);

    // Construct headers and content manually
    $headers = "From: <" . $smtp_user . ">\r\n" . 
               "To: <" . $to . ">\r\n" . 
               "Subject: " . $subject . "\r\n" . 
               "MIME-Version: 1.0\r\n" . 
               "Content-Type: text/plain; charset=utf-8\r\n\r\n";
               
    fwrite($socket, $headers . $body . "\r\n.\r\n");
    get_smtp_response($socket);

    // 6. Close Connection
    fwrite($socket, "QUIT\r\n");
    get_smtp_response($socket);
    fclose($socket);

    return true;
}

// // --- HOW TO EXECUTE IT ---

// $result = send_native_smtp(
//     'rmessina10@gmail.com', 
//     'Direct Authenticated SMTP Test', 
//     'This script logged natively into the mailbox to send this!', 
//     ADMIN_MAIL,
//     ADMIN_MAIL_PASS
// );

// if ($result === true) {
//     echo "Success! The script logged into your specific mailbox and sent the email securely.";
// } else {
//     echo "Error: " . $result;
// }
?>