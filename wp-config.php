<?php
/**
 * Podstawowa konfiguracja WordPressa.
 *
 * Ten plik zawiera konfiguracje: ustawień MySQL-a, prefiksu tabel
 * w bazie danych, tajnych kluczy i ABSPATH. Więcej informacji
 * znajduje się na stronie
 * {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Kodeksu. Ustawienia MySQL-a możesz zdobyć
 * od administratora Twojego serwera.
 *
 * Ten plik jest używany przez skrypt automatycznie tworzący plik
 * wp-config.php podczas instalacji. Nie musisz korzystać z tego
 * skryptu, możesz po prostu skopiować ten plik, nazwać go
 * "wp-config.php" i wprowadzić do niego odpowiednie wartości.
 *
 * @package WordPress
 */

// ** Ustawienia MySQL-a - możesz uzyskać je od administratora Twojego serwera ** //
/** Nazwa bazy danych, której używać ma WordPress */
define('DB_NAME', 'jgovh2');

/** Nazwa użytkownika bazy danych MySQL */
define('DB_USER', 'Michal');

/** Hasło użytkownika bazy danych MySQL */
define('DB_PASSWORD', 'Michal123');

/** Nazwa hosta serwera MySQL */
define('DB_HOST', 'localhost:3306');

/** Kodowanie bazy danych używane do stworzenia tabel w bazie danych. */
define('DB_CHARSET', 'utf8');

/** Typ porównań w bazie danych. Nie zmieniaj tego ustawienia, jeśli masz jakieś wątpliwości. */
define('DB_COLLATE', '');

/**#@+
 * Unikatowe klucze uwierzytelniania i sole.
 *
 * Zmień każdy klucz tak, aby był inną, unikatową frazą!
 * Możesz wygenerować klucze przy pomocy {@link https://api.wordpress.org/secret-key/1.1/salt/ serwisu generującego tajne klucze witryny WordPress.org}
 * Klucze te mogą zostać zmienione w dowolnej chwili, aby uczynić nieważnymi wszelkie istniejące ciasteczka. Uczynienie tego zmusi wszystkich użytkowników do ponownego zalogowania się.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'mG^:x;06VK:V ?LO#TN8yAo@:QzR0[eGG-h4o*iG8,@9buNdu+D`-JX+cHnBq;#F');
define('SECURE_AUTH_KEY',  'naNlB([z*|5jYT#ytKf`))PA}P){bYIhUBHt4-%$OcsEVQZ{.]HudNL-v@bvO}p@');
define('LOGGED_IN_KEY',    '|Ww k(-tw8A)sehaVy|l9E{dT|?X86-W-U._1lMdfJzk=c|#]{Vv(9e}3@RQ,|$2');
define('NONCE_KEY',        '8N7MJB@<nIjq{`%lfVHvhngTLwc:jb-Kks/eJMa.W|=(|Z-p1{5@wI43$#rOxV(U');
define('AUTH_SALT',        '`3Ss-R[k,)35~c>~x~&l>8T?d:tJ7lhmD7<4DF(fA~4$6awpamk.?3|9bE@e,(|}');
define('SECURE_AUTH_SALT', '1xHV+*JFeEb12TFD:&r]Y)W_6_v[s)]Me|5c~{yx=Z:@f|<=D+8iwE1mP5uPq|-G');
define('LOGGED_IN_SALT',   'x$gROl&-`NW<=]uB*%Rv[@jh)vW(^Q)iFy5}emu4~ywX)zmgtd<Tqpq!#7=7=w:=');
define('NONCE_SALT',       'F7(m$fR/Ph+dp;G*eWb*Yj@Q )Li(Dy|k.53qh`+B~[*S>6c(G3t4D>[g r)LK!g');

/**#@-*/

/**
 * Prefiks tabel WordPressa w bazie danych.
 *
 * Możesz posiadać kilka instalacji WordPressa w jednej bazie danych,
 * jeżeli nadasz każdej z nich unikalny prefiks.
 * Tylko cyfry, litery i znaki podkreślenia, proszę!
 */
$table_prefix  = 'wp_';

/**
 * Dla programistów: tryb debugowania WordPressa.
 *
 * Zmień wartość tej stałej na true, aby włączyć wyświetlanie ostrzeżeń
 * podczas modyfikowania kodu WordPressa.
 * Wielce zalecane jest, aby twórcy wtyczek oraz motywów używali
 * WP_DEBUG w miejscach pracy nad nimi.
 */
define('WP_DEBUG', false);

/* To wszystko, zakończ edycję w tym miejscu! Miłego blogowania! */

/** Absolutna ścieżka do katalogu WordPressa. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Ustawia zmienne WordPressa i dołączane pliki. */
require_once(ABSPATH . 'wp-settings.php');
