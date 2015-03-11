#Starter package for WordPress

##Usage
This version is not yet ready for use. (Nor is this instruction file!)

##Introduction
Dieses Repository ist als Standardpaket für WordPress-Installationen vorgesehen. Das Vorgehen für Installation ist folgendes.

##Download WordPress Core
Download the current version of WordPress from Github at https://github.com/WordPress/WordPress. This is always the current reliable release version. (There are no beta or release candidate versions in that repository.)

##Install Languages
Read documentation of language setup [here](tree/master/wp-content/languages).

##Configure the website
Die DB und ein DB-Benutzer erstellen

Im Wordpress-Paket aus GitHub die Datei «wp-config-sample.php» in «wp-config.php» umbenennen und in dieser Datei folgende Werte ersetzen:
* DB_NAME
* DB_USER
* DB_PASSWORD
* DB_HOST (fast immer 'localhost')
* DB_CHARSET bleibt immer auf 'utf8'
* AUTH_KEY, SECURE_AUTH_KEY, LOGGED_IN_KEY, NONCE_KEY, AUTH_SALT, SECURE_AUTH_SALT, LOGGED_IN_SALT, NONCE_SALT können durch https://api.wordpress.org/secret-key/1.1/salt/ automatisch erstellt werden.

Folgende Unterordner aus unser Repository in wordpress/wp-content kopieren.
* plugins	(Tools, die wir immer wieder anwenden)
* themes	(Vorbereiteten Layoutvorlagen)
* uploads	(Leer, wird für Uploads durch WP-Backend benötigt)

Diese Unterordner sollen die bestehende Unterordner überschreiben. Die Defaultordner in der Wordpress-Paket enthalten Dateien bzw. Themes und Plugins, die wir nicht anwenden.

Unterordner «uploads» im Ordner wp-content ist am Anfang immer leer, wird aber immer benötigt.

Der Pfad http://WEBSITE/wp-admin/ aufrufen. (WEBSITE mit der richtigen Domainnamen auswechseln.) Wordpress leitet dich durch das einfache Setup-Prozess.

Select the Child Theme under **Appearance** » **Themes**. (Not the parent theme.)