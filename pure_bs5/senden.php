<?php
/**
 * Kontaktformular-Versand (Ersatz fuer das alte unsichere bat/rd-mailform.php)
 *
 * - Serverseitige Validierung + Honeypot-Spamschutz
 * - Header-Injection-Schutz (CR/LF aus Einzeilern entfernt)
 * - Versand per SMTP ueber aktuelles PHPMailer 6 (empfohlen),
 *   Fallback auf PHP mail() wenn MAIL_TRANSPORT='mail' und PHPMailer fehlt
 * - Weiterleitung auf success.html / error.html
 *
 * Einrichtung: siehe MAILER-SETUP.md
 */

// --- Konfiguration laden (lokale Datei bevorzugt) ------------------------
$dir = __DIR__;
if (is_file($dir . '/senden.config.local.php')) {
    require $dir . '/senden.config.local.php';
} elseif (is_file($dir . '/senden.config.php')) {
    require $dir . '/senden.config.php';
} else {
    http_response_code(500);
    exit('Konfiguration fehlt.');
}

function redirect_exit($target)
{
    header('Location: ' . $target, true, 303);
    exit;
}

function clean_line($v)
{
    // CR/LF raus -> verhindert Header-Injection in Einzeiler-Feldern
    return trim(str_replace(array("\r", "\n", "%0a", "%0d"), '', (string) $v));
}

// --- Nur POST zulassen ---------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect_exit('index.html');
}

// --- Honeypot: ausgefuellt = Bot. Still auf OK leiten (nicht verraten) ---
if (!empty($_POST['website'])) {
    redirect_exit(REDIRECT_OK);
}

// --- Eingaben einlesen + begrenzen --------------------------------------
$name    = clean_line($_POST['name']    ?? '');
$email   = clean_line($_POST['email']   ?? '');
$phone   = clean_line($_POST['phone']   ?? '');
$message = trim((string) ($_POST['message'] ?? ''));

$name    = mb_substr($name, 0, 100);
$email   = mb_substr($email, 0, 150);
$phone   = mb_substr($phone, 0, 50);
$message = mb_substr($message, 0, 5000);

// --- Validierung ---------------------------------------------------------
$errors = array();
if ($name === '')                                            { $errors[] = 'name'; }
if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) { $errors[] = 'email'; }
if ($message === '')                                         { $errors[] = 'message'; }
if ($errors) {
    redirect_exit(REDIRECT_ERROR);
}

// --- Nachrichtentext -----------------------------------------------------
$body  = "Neue Nachricht ueber das Kontaktformular\n";
$body .= "----------------------------------------\n\n";
$body .= "Name:     " . $name . "\n";
$body .= "E-Mail:   " . $email . "\n";
$body .= "Telefon:  " . ($phone !== '' ? $phone : '-') . "\n\n";
$body .= "Nachricht:\n" . $message . "\n";

// --- Versand -------------------------------------------------------------
$sent = false;

$pmBase = $dir . '/lib/phpmailer/src/';
$hasPhpMailer = is_file($pmBase . 'PHPMailer.php');

if ($hasPhpMailer) {
    require $pmBase . 'Exception.php';
    require $pmBase . 'PHPMailer.php';
    require $pmBase . 'SMTP.php';

    $mail = new \PHPMailer\PHPMailer\PHPMailer(true);
    try {
        $mail->CharSet = 'UTF-8';

        if (MAIL_TRANSPORT === 'smtp') {
            $mail->isSMTP();
            $mail->Host       = SMTP_HOST;
            $mail->Port       = SMTP_PORT;
            $mail->SMTPAuth   = true;
            $mail->Username   = SMTP_USER;
            $mail->Password   = SMTP_PASS;
            $mail->SMTPSecure = SMTP_SECURE; // 'tls' oder 'ssl'
        } else {
            $mail->isMail();
        }

        $mail->setFrom(MAIL_FROM, MAIL_FROM_NAME);
        $mail->addAddress(MAIL_TO, MAIL_TO_NAME);
        $mail->addReplyTo($email, $name); // Antwort geht an den Absender
        $mail->Subject = MAIL_SUBJECT;
        $mail->Body    = $body;

        $mail->send();
        $sent = true;
    } catch (\Exception $e) {
        error_log('Kontaktformular: ' . $mail->ErrorInfo);
        $sent = false;
    }
} elseif (MAIL_TRANSPORT === 'mail') {
    // Fallback ohne PHPMailer (nur wenn bewusst 'mail' gewaehlt)
    $headers  = 'From: ' . MAIL_FROM_NAME . ' <' . MAIL_FROM . ">\r\n";
    $headers .= 'Reply-To: ' . $email . "\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
    $subject  = '=?UTF-8?B?' . base64_encode(MAIL_SUBJECT) . '?=';
    $sent = @mail(MAIL_TO, $subject, $body, $headers);
} else {
    error_log('Kontaktformular: PHPMailer fehlt unter lib/phpmailer/ (siehe MAILER-SETUP.md)');
}

redirect_exit($sent ? REDIRECT_OK : REDIRECT_ERROR);
