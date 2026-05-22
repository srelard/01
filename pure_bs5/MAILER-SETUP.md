# Kontaktformular einrichten (senden.php)

Ersetzt das alte unsichere `bat/rd-mailform.php` (veraltetes PHPMailer
mit bekannten RCE-Luecken). Neuer Stand: aktuelles PHPMailer 6 + SMTP,
serverseitige Validierung, Honeypot-Spamschutz.

## 1. PHPMailer 6 hinzufuegen
Voraussetzung: PHP-Hosting (Standard bei deutschen Anbietern).

- PHPMailer 6.x von der offiziellen Quelle laden:
  https://github.com/PHPMailer/PHPMailer/releases (neuestes 6.x-Release)
- Aus dem Release nur den Ordner `src/` uebernehmen, sodass folgende
  Dateien existieren:
  ```
  pure_bs5/lib/phpmailer/src/PHPMailer.php
  pure_bs5/lib/phpmailer/src/SMTP.php
  pure_bs5/lib/phpmailer/src/Exception.php
  ```
  (Composer ist alternativ moeglich; dann den require-Pfad in
  `senden.php` auf den Autoloader anpassen.)

## 2. Zugangsdaten eintragen
- `senden.config.php` enthaelt nur Platzhalter (TODO-Werte).
- **Echte Zugangsdaten NICHT committen.** Stattdessen auf dem Server
  eine Datei `senden.config.local.php` mit denselben `define()`-Zeilen
  und den echten Werten anlegen. Diese Datei wird per `.gitignore`
  ausgeschlossen und von `senden.php` automatisch bevorzugt geladen.
- Benoetigte Angaben:
  - Empfaenger-Adresse (`MAIL_TO`)
  - Absender-Postfach der Domain (`MAIL_FROM`)
  - SMTP-Host, -Port, -Benutzer, -Passwort
  - `MAIL_TRANSPORT = 'smtp'` (empfohlen) oder `'mail'`

## 3. Test
- Seite auf das PHP-Hosting hochladen, Formular absenden.
- Erfolg leitet auf `success.html`, Fehler auf `error.html`.
- Versandfehler landen im PHP-Error-Log (`error_log`).

## Hinweis Vorschau / Vercel
Die rein statische Vorschau (z. B. Vercel) kann **kein** PHP ausfuehren,
das Formular ist dort nur Optik. Echter Versand nur auf PHP-Hosting.
