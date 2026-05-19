<?php
/**
 * Konfiguration fuer das Kontaktformular (senden.php)
 *
 * WICHTIG:
 *  - Hier stehen nur PLATZHALTER. Echte Zugangsdaten NICHT in dieses
 *    Repo committen. Entweder direkt auf dem Server eintragen oder eine
 *    Datei "senden.config.local.php" mit denselben define()-Zeilen
 *    anlegen (die wird per .gitignore ausgeschlossen und automatisch
 *    bevorzugt geladen).
 *  - Werte von Silvia / vom Hoster einsetzen, sobald bekannt.
 */

// --- Empfaenger: an diese Adresse gehen die Kontaktanfragen --------------
define('MAIL_TO',        'TODO-empfaenger@ihre-domain.de');
define('MAIL_TO_NAME',   'Naturheilpraxis');

// --- Absender (i. d. R. ein Postfach der eigenen Domain) -----------------
define('MAIL_FROM',      'TODO-noreply@ihre-domain.de');
define('MAIL_FROM_NAME', 'Kontaktformular Website');
define('MAIL_SUBJECT',   'Neue Nachricht ueber das Kontaktformular');

// --- Transport: 'smtp' (empfohlen) oder 'mail' (PHP-Standard) ------------
define('MAIL_TRANSPORT', 'smtp');

// --- SMTP-Zugangsdaten (nur bei MAIL_TRANSPORT = 'smtp') -----------------
define('SMTP_HOST',   'TODO-smtp.ihr-hoster.de');
define('SMTP_PORT',   587);              // 587 = STARTTLS, 465 = SSL
define('SMTP_SECURE', 'tls');            // 'tls' oder 'ssl'
define('SMTP_USER',   'TODO-postfach@ihre-domain.de');
define('SMTP_PASS',   'TODO-passwort');

// --- Weiterleitungen nach dem Absenden -----------------------------------
define('REDIRECT_OK',    'success.html');
define('REDIRECT_ERROR', 'error.html');
