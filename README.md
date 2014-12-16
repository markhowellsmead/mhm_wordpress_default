#WordPress Helpers

##Beschreibung
Dieser Repository enthält Basisfunktionen für WordPress, inklusive eigene Plugins und Themes. Es gibt im jeweiligen Verzeichnis einen eigenen README, die du für Anleitungen anschauen solltest.

##Anwendung
Zuerst der WordPress-Core von https://github.com/WordPress/WordPress laden: diese ist immer die aktuelle stabile Version.

Die DB und ein DB-Benutzer erstellen.

Im Wordpress-Paket aus GitHub die Datei «wp-config-sample.php» in «wp-config.php» umbenennen und in dieser Datei folgende Werte setzen:

* DB_NAME
* DB_USER
* DB_PASSWORD
* DB_HOST (fast immer 'localhost')
* DB_CHARSET bleibt immer auf 'utf8'
* AUTH_KEY, SECURE_AUTH_KEY, LOGGED_IN_KEY, NONCE_KEY, AUTH_SALT, SECURE_AUTH_SALT, LOGGED_IN_SALT, NONCE_SALT können alle via https://api.wordpress.org/secret-key/1.1/salt/ automatisch erstellt werden.

Folgende Unterordner aus unser Repository in wordpress/wp-content kopieren. Diese Unterordner sollen die bestehende Unterordner überschreiben. Die Defaultordner in der Wordpress-Paket enthalten Dateien bzw. Themes und Plugins, die wir nicht anwenden.

* plugins	(Tools, die wir immer wieder anwenden)
* themes	(Vorbereiteten Layoutvorlagen)

Das Verzeichnis wp-content/uploads (leer) erstellen. Das Verzeichnis frp_wordpress_child für dein Projekt umbenennen.

Die projektspezifische Angaben in wp-content/themes/frp_wordpress_child/style.css angeben.

Ein PNG-Screenshot – 968px x 725px – als screenshot.png im Child Theme ablegen.

Der Pfad http://WEBSITE/wp-admin/ aufrufen. (WEBSITE mit der richtigen Domainnamen auswechseln.) Wordpress leitet dich durch das einfache Setup-Prozess.

Select the Child Theme under **Appearance** » **Themes**. (Not the parent theme.)

Einloggen und das Child-Theme unter **Design** » **Themes** anwählen.

Falls definiert, die Theme-Einstellungen unter **Design** » **Theme-Einstellungen** setzen.

##Autor
Mark Howells-Mead | www.permanenttourist.ch/github | Seit Oktober 2014

##Lizenz
Gibt es nicht. Diese Technik darf nur für !frappant-Projekte eingesetzt werden.