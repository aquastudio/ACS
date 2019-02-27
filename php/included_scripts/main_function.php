<?php
/*
inclu par :
    [Tous les fichiers]
définition :
    Définit les fonctions
                variables
                constantes
                autres...
*/

    // http ou https :
    $path = $_SERVER["REQUEST_SCHEME"] . "://";

    // si on est sur un serveur local ou pas :
    if($_SERVER["HTTP_HOST"] == "localhost" OR $_SERVER["HTTP_HOST"] == "127.0.0.1") {
        $php_self = $_SERVER["REQUEST_URI"];
        $nblen_php_self = strlen($php_self);
        $php_self = substr($_SERVER["PHP_SELF"], 1, $nblen_php_self - 1);
        $nblen_php_self = strlen($php_self);
        $pos_first_slash = strpos($php_self, "/");
        
        $path .= $_SERVER["HTTP_HOST"] . "/" . substr($php_self, 0, $pos_first_slash - $nblen_php_self);
    } else {
        $path .= $_SERVER["REQUEST_URI"];
    }
    
    $_SERVER["HTTP_ROOT"] = $path;

    define("BR", "<br/>");

    setlocale(LC_TIME, "fr");

    function redirect($page) {
        echo("<script type='text/javascript'> document.location.href='".$page."';</script>");
    }

    function do_song($song = false) {
        if($song) {
        ?>
            <script type="text/javascript">
                do_song("<?php echo $song; ?>");
            </script>
        <?php
        } else {
            return false;
        }
    }

    function asciispecialchars($str, $html = false) {

        if(isset($str) AND gettype($str) == "string") {
             
            $chars = array("!",     "$",     "%",     "'",     "(",     ")",     "*",     "+",     ",",     "-",     ".",     "?",     "@",     "[",     "\\",    "]",     "^",     "_",     "`",     "¡",       "¢",      "£",       "¤",        "¥",     "¦",        "§",      "¨",     "©",      "ª",      "«",       "¬",     "®",     "¯",      "°",     "±",        "²",      "³",      "´",       "µ",       "¶",      "·",        "¸",       "¹",      "º",      "»",       "¼",        "½",        "¾",        "¿",        "À",        "Á",        "Â",       "Ã",        "Ä",      "Å",       "Æ",       "Ç",        "È",        "É",        "Ê",       "Ë",      "Ì",        "Í",        "Î",       "Ï",      "Ð",     "Ñ",        "Ò",        "Ó",        "Ô",       "Õ",        "Ö",      "×",       "Ø",        "Ù",        "Ú",        "Û",       "Ü",       "Ý",       "Þ",       "ß",       "à",        "á",        "â",       "ã",        "ä",      "å",       "æ",       "ç",        "è",        "é",        "ê",       "ë",      "ì",        "í",        "î",       "ï",      "ð",     "ñ",        "ò",        "ó",        "ô",        "õ",       "ö",       "÷",       "ø",        "ù",        "ú",        "û",       "ü",      "ý",        "þ",       "ÿ",      "Œ",      "œ",      "Š",      "š",      "Ÿ",      "ƒ",       "–",       "—",       "‘",       "’",       "‚",       "“",       "”",       "„",       "†",       "‡",       "•",       "…",       "‰",       "€",       "™");
            $ascii = array("&#33;", "&#36;", "&#37;", "&#39;", "&#40;", "&#41;", "&#42;", "&#43;", "&#44;", "&#45;", "&#46;", "&#63;", "&#64;", "&#91;", "&#92;", "&#93;", "&#94;", "&#95;", "&#96;", "&iexcl;", "&cent;", "&pound;", "&curren;", "&yen;", "&brvbar;", "&sect;", "&uml;", "&copy;", "&ordf;", "&laquo;", "&not;", "&reg;", "&macr;", "&deg;", "&plusmn;", "&sup2;", "&sup3;", "&acute;", "&micro;", "&para;", "&middot;", "&cedil;", "&sup1;", "&ordm;", "&raquo;", "&frac14;", "&frac12;", "&frac34;", "&iquest;", "&Agrave;", "&Aacute;", "&Acirc;", "&Atilde;", "&Auml;", "&Aring;", "&AElig;", "&Ccedil;", "&Egrave;", "&Eacute;", "&Ecirc;", "&Euml;", "&Igrave;", "&Iacute;", "&Icirc;", "&Iuml;", "&ETH;", "&Ntilde;", "&Ograve;", "&Oacute;", "&Ocirc;", "&Otilde;", "&Ouml;", "&times;", "&Oslash;", "&Ugrave;", "&Uacute;", "&Ucirc;", "&Uuml;", "&Yacute;", "&THORN;", "&szlig;", "&agrave;", "&aacute;", "&acirc;", "&atilde;", "&auml;", "&aring;", "&aelig;", "&ccedil;", "&egrave;", "&eacute;", "&ecirc;", "&euml;", "&igrave;", "&iacute;", "&icirc;", "&iuml;", "&eth;", "&ntilde;", "&ograve;", "&oacute;", "&ocirc;", "&otilde;", "&ouml;", "&divide;", "&oslash;", "&ugrave;", "&uacute;", "&ucirc;", "&uuml;", "&yacute;", "&thorn;", "&yuml;", "&#338;", "&#339;", "&#352;", "&#353;", "&#376;", "&#402;", "&#8211;", "&#8212;", "&#8216;", "&#8217;", "&#8218;", "&#8220;", "&#8221;", "&#8222;", "&#8224;", "&#8225;", "&#8226;", "&#8230;", "&#8240;", "&#8364;", "&#8482;");

            if(!$html) {
                array_push($chars, "<",     "=",     ">",     '"',     "/",     ":");
                array_push($ascii, "&#60;", "&#61;", "&#62;", "&#34;", "&#47;", "&#58;");
            }
            return str_replace($chars, $ascii, $str);
        } else {
            return false;
        }
    }

    function get_file_extension($fileName) {
        if(!empty($fileName) AND gettype($fileName) == "string") {
            return strtolower(substr(strrchr($fileName, "."), 1));
        } else {
            return false;
        }
    }
    function get_file_name($fileName) {
        if(!empty($fileName) AND gettype($fileName) == "string") {
            return strtolower(str_replace(strrchr($fileName, "."), "", $fileName));
        } else {
            return false;
        }
    }

    function parse_emoji($str) {
        if(!empty($str) AND gettype($str) == "string") { 

            $chars = array("#angry;", "#bespectacled;", "#cool;", "#cry;", "#cute;", "#disappointed;", "#doubtful;", "#enthusiastic;", "#gamer;", "#great;", "#illtempered;", "#impressed;", "#jaded;", "#lover;", "#moustached;", "#mute;", "#noreaction;", "#pleasant;", "#sad;", "#smile;", "#thug;", "#unhappy;",  "#weary;", "#wink;", "#world;");
            $emojis = array();

                  
            foreach ($chars as $value) {
                $current_emoji_char = substr($value, 1, -1);
                array_push($emojis, "<img class=\"emojis\" src=\"" . $_SERVER["HTTP_ROOT"]. "/ui/img/emojis/" . $current_emoji_char . ".png\" width=\"17\" height=\"17\"/>");
            }

            return str_replace($chars, $emojis, $str);
            
        } else {
            return false;
        }
    }
?>