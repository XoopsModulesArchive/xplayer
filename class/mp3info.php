<?php
/********************************************************************************/

/* KLASSE: mp3info  */
/* VERSION: 0.2.2  */
/* AUTOR: Mark Lubkowitz  */
/* KONTAKT: mail@mark-lubkowitz.de  */
/* COPYRIGHT: © 2005 Mark Lubkowitz, darf jedoch beliebig verwendet und */
/* ver‰ndert werden.  */
/* BESCHREIBUNG: PHP-Klasse zum Lesen eines ID3TagV1.1 und des MP3-Headers. */
/*   */
/********************************************************************************/
/* VERSIONSHISTORIE:  */
/* [0.1.3] Einlesen des ID3v1 bzw. ID3v11-Tags */
/* [0.1.4] Ermitteln des Genre-Namens (alle 125) */
/* [0.1.5] Ausgabe des TAGs als Tabelle  */
/* [0.2.0] Einlesen des MP3-Headers  */
/* [0.2.1] Stringausgabe anstelle von Integer  */
/* [0.2.2] Formatierung der Ausgabe verbessert */
/*   */
/********************************************************************************/
/* GEPLANTE ERWEITERUNGEN:  */
/* [1] Schreiben des ID3v1-/ID3v11-Tags  */
/* [2] Lesen des ID3v2-Tags  */
/* [3] Schreiben des ID3v2-Tags  */
/* [4] Erweiterung/Verbesserung der Fehlerbehandlung */
/* [5] Optimierung des Quelltextes  */
/*   */
/********************************************************************************/
/* BEKANNTE PROBLEME:  */
/* [1] Bitrate und L‰nge des St¸ckes werden bei variabler Bitrate nicht rich- */
/* tig interpretiert  */
/*   */
/********************************************************************************/

/*** Konstantendefinition ***/
// Klassenversion
define('MP3INFO_VERSION', '0.2.2');
// TagVersionen
define('MP3INFO_TV_NONE', 0);
define('MP3INFO_TV_V1', 1);
define('MP3INFO_TV_V11', 2);
define('MP3INFO_TV_V2', 3);
// MPeg-Versionen
define('MP3INFO_MV_MPEG25', 0);
define('MP3INFO_MV_UNKNOWN', 1);
define('MP3INFO_MV_MPEG2', 2);
define('MP3INFO_MV_MPEG1', 3);
// Layer-Versionen
define('MP3INFO_LY_UNKNOWN', 0);
define('MP3INFO_LY_LAYER3', 1);
define('MP3INFO_LY_LAYER2', 2);
define('MP3INFO_LY_LAYER1', 3);
// Kan‰le
define('MP3INFO_CH_STEREO', 0);
define('MP3INFO_CH_JOINTSTEREO', 1);
define('MP3INFO_CH_DUALCHANNEL', 2);
define('MP3INFO_CH_MONO', 3);
// Emphasis
define('MP3INFO_EM_NONE', 0);
define('MP3INFO_EM_5015MS', 1);
define('MP3INFO_EM_UNNAMED', 2);
define('MP3INFO_EM_CCITTJ17', 3);

/*** Klassendefinition ***/
class mp3info
{
    // Allgemeine Informationen
    public $filename; // Dateiname
    public $size; // Dateigrˆe
    // ID3v1/ID3v1.1 Tag
    public $title; // Titel
    public $artist; // Interpret
    public $album; // Album
    public $year; // Jahr
    public $comment; // Kommentar
    public $track; // Track
    public $genreID; // Genrenummer
    public $genreName; // Genrename
    public $tagVersion; // ID3-Tag-Version (MP3INFO_TV_NONE,MP3INFO_TV_V1,MP3INFO_TV_V11,MP3INFO_TV_V2)
    // Header
    public $headerFoundAt; // Offset des MP3-Headers
    public $mpegVersion; // MPeg-Version (MP3INFO_MV_...)
    public $layer; // Layer (MP3INFO_LY_...)
    public $bitrate; // Bitrate
    public $crc; // CRC
    public $frequence; // Sample-Rate
    public $channels; // Kan‰le (MP3INFO_CH_...)
    public $copyright; // urheberrechlich gesch¸tzt?
    public $orignal; // Original?
    public $frames; // Frameanzahl
    public $length; // L‰nge in Sekunden
    public $emphasis; // Emphasis

    // Klassen-Konstruktor dem der Dateiname der MP3-Datei ¸bergeben werden muss

    public function __construct($filename)
    {
        if (!file_exists($filename)) {
            die("Datei $filename existiert nicht!");
        }

        //if(filesize($filename) != '') die("Datei $filename ist kleiner als 128 Byte!");

        $this->filename = $filename;

        $this->size = filesize($this->filename);

        $this->getID3Tag();

        $this->getHeader();
    }

    // Liest den ID3 Version1 oder Version1.1 Tag aus

    public function getID3Tag()
    {
        //***

        $genreNames = [
            0 => 'Blues',
            1 => 'Classic Rock',
            2 => 'Country',
            3 => 'Dance',
            4 => 'Disco',
            5 => 'Funk',
            6 => 'Grunge',
            7 => 'Hip-Hop',
            8 => 'Jazz',
            9 => 'Metal',
            10 => 'New Age',
            11 => 'Oldies',
            12 => 'Other',
            13 => 'Pop',
            14 => 'R&B',
            15 => 'Rap',
            16 => 'Reggae',
            17 => 'Rock',
            18 => 'Techno',
            19 => 'Industrial',
            20 => 'Alternative',
            21 => 'Ska',
            22 => 'Death Metal',
            23 => 'Pranks',
            24 => 'Soundtrack',
            25 => 'Euro-Techno',
            26 => 'Ambient',
            27 => 'Trip-Hop',
            28 => 'Vocal',
            29 => 'Jazz+Funk',
            30 => 'Fusion',
            31 => 'Trance',
            32 => 'Classical',
            33 => 'Instrumental',
            34 => 'Acid',
            35 => 'House',
            36 => 'Game',
            37 => 'Sound Clip',
            38 => 'Gospel',
            39 => 'Noise',
            40 => 'AlternRock',
            41 => 'Bass',
            42 => 'Soul',
            43 => 'Punk',
            44 => 'Space',
            45 => 'Mediative',
            46 => 'Instrumental Pop',
            47 => 'Instrumental Rock',
            48 => 'Ethnic',
            49 => 'Gothic',
            50 => 'Darkwave',
            51 => 'Techno-Industrial',
            52 => 'Electronic',
            53 => 'Pop-Folk',
            54 => 'Eurodance',
            55 => 'Dream',
            56 => 'Southern Rock',
            57 => 'Comedy',
            58 => 'Cult',
            59 => 'Gangsta',
            60 => 'Top 40',
            61 => 'Christian Rap',
            62 => 'Pop/Funk',
            63 => 'Jungle',
            64 => 'Native American',
            65 => 'Cabaret',
            66 => 'New Wave',
            67 => 'Psychedelic',
            68 => 'Rave',
            69 => 'Showtunes',
            70 => 'Trailer',
            71 => 'Lo-Fi',
            72 => 'Tribal',
            73 => 'Acid Punk',
            74 => 'Acid Jazz',
            75 => 'Polka',
            76 => 'Retro',
            77 => 'Musical',
            78 => 'Rock & Roll',
            79 => 'Hard Rock',
            80 => 'Folk',
            81 => 'Folk-Rock',
            82 => 'National Folk',
            83 => 'Swing',
            84 => 'Fast Fusion',
            85 => 'Bebob',
            86 => 'Latin',
            87 => 'Revival',
            88 => 'Celtic',
            89 => 'Bluegrass',
            90 => 'Avantgarde',
            91 => 'Gothic Rock',
            92 => 'Progressive Rock',
            93 => 'Psychedelic Rock',
            94 => 'Symphonic Rock',
            95 => 'Slow Rock',
            96 => 'Big Hand',
            97 => 'Chorus',
            98 => 'Easy Listening',
            99 => 'Acoustic',
            100 => 'Humour',
            101 => 'Speech',
            102 => 'Chanson',
            103 => 'Opera',
            104 => 'Chamber Music',
            105 => 'Sonata',
            106 => 'Symphony',
            107 => 'Booty Bass',
            108 => 'Primus',
            109 => 'Porn Groove',
            110 => 'Satire',
            111 => 'Slow Jam',
            112 => 'Club',
            113 => 'Tango',
            114 => 'Samba',
            115 => 'Folklore',
            116 => 'Ballad',
            117 => 'Power Ballad',
            118 => 'Rhythmic Soul',
            119 => 'Free Style',
            120 => 'Duet',
            121 => 'Punk Rock',
            123 => 'A capella',
            124 => 'Euro-House',
            125 => 'Dance Hall',
        ];

        //***

        $this->tagVersion = MP3INFO_TV_NONE;

        $fh = fopen($this->filename, 'rb');

        if ($fh) {
            fseek($fh, -128, SEEK_END);

            $buffer = fread($fh, 128);

            fclose($fh);

            if ('TAG' == mb_substr($buffer, 0, 3)) {
                $this->tagVersion = MP3INFO_TV_V1;

                $this->title = trim(mb_substr($buffer, 3, 30));

                $this->artist = trim(mb_substr($buffer, 33, 30));

                $this->album = trim(mb_substr($buffer, 63, 30));

                $this->year = trim(mb_substr($buffer, 93, 4));

                if ((0 == ord(mb_substr($buffer, 125, 1))) && (0 != ord(mb_substr($buffer, 126, 1)))) {
                    $this->comment = trim(mb_substr($buffer, 97, 28));

                    $this->track = ord(mb_substr($buffer, 126, 1));

                    $this->tagVersion = MP3INFO_TV_V11;
                } else {
                    $this->comment = trim(mb_substr($buffer, 97, 30));
                }

                $this->genreID = ord(mb_substr($buffer, 127, 1));

                $this->genreName = $genreNames[$this->genreID];
            }
        }
    }

    // Liest den Header der MP3-Datei aus

    public function getHeader()
    {
        //***

        $bitmask = [];

        $bitrates = [];

        $bitrates[] = [0, 32, 64, 96, 128, 160, 192, 224, 256, 288, 320, 352, 384, 416, 448, 0];

        $bitrates[] = [0, 32, 48, 56, 64, 80, 96, 112, 128, 160, 192, 224, 256, 320, 384, 0];

        $bitrates[] = [0, 32, 40, 48, 56, 64, 80, 96, 112, 128, 160, 192, 224, 256, 320, 0];

        $bitrates[] = [0, 32, 64, 96, 128, 160, 192, 224, 256, 288, 320, 352, 384, 416, 448, 0];

        $bitrates[] = [0, 32, 48, 56, 64, 80, 96, 112, 128, 160, 192, 224, 256, 320, 384, 0];

        $bitrates[] = [0, 8, 16, 24, 32, 64, 80, 56, 64, 128, 160, 112, 128, 256, 320, 0];

        $frequencies = [];

        $frequencies[] = [44100, 48000, 32000, 0];

        $frequencies[] = [22050, 24000, 16000, 0];

        //***

        $fh = fopen($this->filename, 'rb');

        if ($fh) {
            $h1 = -1;

            $h2 = -1;

            $offset = -1;

            do {
                $offset++;

                fseek($fh, $offset);

                $h1 = fread($fh, 1);

                $h2 = fread($fh, 1);

                if ((255 == ord($h1)) && (251 == ord($h2))) {
                    break;
                }
            } while ($offset < ($this->size - 4));

            if ($offset > ($this->size - 4)) {
                break;
            }

            $this->headerFoundAt = $offset;

            fseek($fh, $offset);

            $buffer = fread($fh, 4);

            fclose($fh);

            for ($i = 0, $iMax = mb_strlen($buffer); $i < $iMax; $i++) {
                $buf = mb_substr($buffer, $i, 1);

                if (128 == (ord($buf) & 128)) {
                    $bitmask[($i * 8)] = 1;
                } else {
                    $bitmask[($i * 8)] = 0;
                }

                if (64 == (ord($buf) & 64)) {
                    $bitmask[($i * 8) + 1] = 1;
                } else {
                    $bitmask[($i * 8) + 1] = 0;
                }

                if (32 == (ord($buf) & 32)) {
                    $bitmask[($i * 8) + 2] = 1;
                } else {
                    $bitmask[($i * 8) + 2] = 0;
                }

                if (16 == (ord($buf) & 16)) {
                    $bitmask[($i * 8) + 3] = 1;
                } else {
                    $bitmask[($i * 8) + 3] = 0;
                }

                if (8 == (ord($buf) & 8)) {
                    $bitmask[($i * 8) + 4] = 1;
                } else {
                    $bitmask[($i * 8) + 4] = 0;
                }

                if (4 == (ord($buf) & 4)) {
                    $bitmask[($i * 8) + 5] = 1;
                } else {
                    $bitmask[($i * 8) + 5] = 0;
                }

                if (2 == (ord($buf) & 2)) {
                    $bitmask[($i * 8) + 6] = 1;
                } else {
                    $bitmask[($i * 8) + 6] = 0;
                }

                if (1 == (ord($buf) & 1)) {
                    $bitmask[($i * 8) + 7] = 1;
                } else {
                    $bitmask[($i * 8) + 7] = 0;
                }
            }

            // Mpeg-Version

            $x = 0;

            if ($bitmask[11]) {
                $x += 2;
            }

            if ($bitmask[12]) {
                $x++;
            }

            $this->mpegVersion = $x;

            // Layer

            $x = 0;

            if ($bitmask[13]) {
                $x += 2;
            }

            if ($bitmask[14]) {
                $x++;
            }

            $this->layer = $x;

            // Bitrate

            $x = 0;

            if (((MP3INFO_MV_MPEG1 == $this->mpegVersion) || (MP3INFO_MV_UNKNOWN == $this->mpegVersion)) && (MP3INFO_LY_LAYER1 == $this->layer)) {
                $y = 0;
            }

            if (((MP3INFO_MV_MPEG1 == $this->mpegVersion) || (MP3INFO_MV_UNKNOWN == $this->mpegVersion)) && (MP3INFO_LY_LAYER2 == $this->layer)) {
                $y = 1;
            }

            if (((MP3INFO_MV_MPEG1 == $this->mpegVersion) || (MP3INFO_MV_UNKNOWN == $this->mpegVersion)) && (MP3INFO_LY_LAYER3 == $this->layer)) {
                $y = 2;
            }

            if (((MP3INFO_MV_MPEG2 == $this->mpegVersion) || (MP3INFO_MV_MPEG25 == $this->mpegVersion)) && (MP3INFO_LY_LAYER1 == $this->layer)) {
                $y = 3;
            }

            if (((MP3INFO_MV_MPEG2 == $this->mpegVersion) || (MP3INFO_MV_MPEG25 == $this->mpegVersion)) && (MP3INFO_LY_LAYER2 == $this->layer)) {
                $y = 4;
            }

            if (((MP3INFO_MV_MPEG2 == $this->mpegVersion) || (MP3INFO_MV_MPEG25 == $this->mpegVersion)) && (MP3INFO_LY_LAYER3 == $this->layer)) {
                $y = 5;
            }

            if ($bitmask[16]) {
                $x += 8;
            }

            if ($bitmask[17]) {
                $x += 4;
            }

            if ($bitmask[18]) {
                $x += 2;
            }

            if ($bitmask[19]) {
                $x += 1;
            }

            $this->bitrate = $bitrates[$y][$x];

            // prot_bit

            $this->crc = $bitmask[15];

            // Frequenz

            $x = 0;

            $y = 0;

            if ($bitmask[20]) {
                $x += 2;
            }

            if ($bitmask[21]) {
                $x++;
            }

            if (!$bitmask[12]) {
                $y++;
            }

            $this->frequence = $frequencies[$y][$x];

            // Kan‰le

            $x = 0;

            if ($bitmask[24]) {
                $x += 2;
            }

            if ($bitmask[25]) {
                $x++;
            }

            $this->channels = $x;

            // Copyright

            $this->copyright = $bitmask[28];

            // Original

            $this->original = $bitmask[29];

            // Emphasis

            $x = 0;

            if ($bitmask[30]) {
                $x += 2;
            }

            if ($bitmask[31]) {
                $x++;
            }

            $this->emphasis = $x;

            // Framesize

            if ((MP3INFO_MV_MPEG1 == $this->mpegVersion) || (MP3INFO_MV_UNKNOWN == $this->mpegVersion)) {
                $y = 0;
            }

            if ((MP3INFO_MV_MPEG2 == $this->mpegVersion) || (MP3INFO_MV_25 == $this->mpegVersion)) {
                $y = 1;
            }

            if ($this->frequence > 0) {
                $x = floor(($this->bitrate * 144000) / $this->frequence);
            } else {
                $x = floor(($this->bitrate * 144000) / 44100);
            }

            if (!$bitmask[15]) {
                $x++;
            }

            $framesize = $x;

            // Frameanzahl

            if ((MP3INFO_TV_V1 == $this->tagVersion) || (MP3INFO_TV_V11 == $this->tagVersion)): $y = 128; else: $y = 0;

            endif;

            $x = floor(($this->size - $y - $this->headerFoundAt) / $framesize);

            $this->frames = $x;

            // L‰nge

            $x = floor(($this->size - $y - $this->headerFoundAt) / ($this->bitrate * 1000 / 8));

            $this->length = $x;
        }
    }

    // Gibt den Header ‰hnlich der phpinfo()-Funktion aus

    public function printInfo()
    {
        //***

        $tagversions = ['none', 'ID3v1', 'ID3v1.1', 'ID3v2'];

        $mpegversions = ['Mpeg 2.5', 'unknown', 'Mpeg 2.0', 'Mpeg 1.0'];

        $layers = ['unknown', 'Layer 3', 'Layer 2', 'Layer 1'];

        $channels = ['Stereo', 'Joint Stereo', 'Dualchannel', 'Mono'];

        $emphasises = ['none', '50/15 ms', 'unnamed', 'CCITT j1.7'];

        $yes_no = ['no', 'yes'];

        //***

        echo '<div style="border:solid 1px #000000; padding:3px; width:50%">';

        echo '<table style="width:100%; font-family:Verdana" cellpadding="0" cellspacing="0">';

        echo '<tr><td width="100%" style="background-color:#003366;padding:3px;color:#FFFFFF;" colspan="2">ID3 (v1/v1.1)</td></tr>';

        echo '<tr><td width="50%" style="background-color:#C1D2EE;padding:3px;font-size:12px">Titel:</td><td width="50%" style="background-color:#EEEEEE; padding:3px; font-size:12px">' . $this->title . '</td></tr>';

        echo '<tr><td width="50%" style="background-color:#C1D2EE;padding:3px;font-size:12px">Interpret:</td><td width="50%" style="background-color:#EEEEEE; padding:3px; font-size:12px">' . $this->artist . '</td></tr>';

        echo '<tr><td width="50%" style="background-color:#C1D2EE;padding:3px;font-size:12px">Album:</td><td width="50%" style="background-color:#EEEEEE; padding:3px; font-size:12px">' . $this->album . '</td></tr>';

        echo '<tr><td width="50%" style="background-color:#C1D2EE;padding:3px;font-size:12px">Jahr:</td><td width="50%" style="background-color:#EEEEEE; padding:3px; font-size:12px">' . $this->year . '</td></tr>';

        echo '<tr><td width="50%" style="background-color:#C1D2EE;padding:3px;font-size:12px">Kommentar:</td><td width="50%" style="background-color:#EEEEEE; padding:3px; font-size:12px">' . $this->comment . '</td></tr>';

        if (MP3INFO_TV_V11 == $this->tagVersion) {
            echo '<tr><td style="background-color:#C1D2EE; padding:3px; font-size:12px">Track:</td><td style="background-color:#EEEEEE; padding:3px; font-size:12px">' . $this->track . '</td></tr>';
        }

        echo '<tr><td style="background-color:#C1D2EE; padding:3px; font-size:12px">Genre:</td><td style="background-color:#EEEEEE; padding:3px; font-size:12px">' . $this->genreName . ' (' . $this->genreID . ')</td></tr>';

        echo '</table>';

        echo '<table style="width:100%; font-family:Verdana" cellpadding="0" cellspacing="0">';

        echo '<tr><td width="100%" style="background-color:#003366;padding:3px;color:#FFFFFF" colspan="2">Headerdetails</td></tr>';

        echo '<tr><td width="50%" style="background-color:#C1D2EE;padding:3px;font-size:12px">Headerposition:</td><td width="50%" style="background-color:#EEEEEE; padding:3px; font-size:12px">' . $this->headerFoundAt . ' Byte (Offset: ' . sprintf('0x%X', $this->headerFoundAt) . ')</td></tr>';

        echo '<tr><td width="50%" style="background-color:#C1D2EE;padding:3px;font-size:12px">Mpeg-Version:</td><td width="50%" style="background-color:#EEEEEE; padding:3px; font-size:12px">' . $mpegversions[$this->mpegVersion] . '</td></tr>';

        echo '<tr><td width="50%" style="background-color:#C1D2EE;padding:3px;font-size:12px">Layer:</td><td width="50%" style="background-color:#EEEEEE; padding:3px; font-size:12px">' . $layers[$this->layer] . '</td></tr>';

        echo '<tr><td width="50%" style="background-color:#C1D2EE;padding:3px;font-size:12px">Bitrate:</td><td width="50%" style="background-color:#EEEEEE; padding:3px; font-size:12px">' . $this->bitrate . ' kbit/s</td></tr>';

        echo '<tr><td width="50%" style="background-color:#C1D2EE;padding:3px;font-size:12px">CRC:</td><td width="50%" style="background-color:#EEEEEE; padding:3px; font-size:12px">' . $yes_no[$this->crc] . '</td></tr>';

        echo '<tr><td width="50%" style="background-color:#C1D2EE;padding:3px;font-size:12px">Frequenz:</td><td width="50%" style="background-color:#EEEEEE; padding:3px; font-size:12px">' . $this->frequence . ' Hz</td></tr>';

        echo '<tr><td width="50%" style="background-color:#C1D2EE;padding:3px;font-size:12px">Kan‰le:</td><td width="50%" style="background-color:#EEEEEE; padding:3px; font-size:12px">' . $channels[$this->channels] . '</td></tr>';

        echo '<tr><td width="50%" style="background-color:#C1D2EE;padding:3px;font-size:12px">Copyright:</td><td width="50%" style="background-color:#EEEEEE; padding:3px; font-size:12px">' . $yes_no[$this->copyright] . '</td></tr>';

        echo '<tr><td width="50%" style="background-color:#C1D2EE;padding:3px;font-size:12px">Original:</td><td width="50%" style="background-color:#EEEEEE; padding:3px; font-size:12px">' . $yes_no[$this->original] . '</td></tr>';

        echo '<tr><td width="50%" style="background-color:#C1D2EE;padding:3px;font-size:12px">Frames:</td><td width="50%" style="background-color:#EEEEEE; padding:3px; font-size:12px">' . $this->frames . '</td></tr>';

        echo '<tr><td width="50%" style="background-color:#C1D2EE;padding:3px;font-size:12px">L‰nge:</td><td width="50%" style="background-color:#EEEEEE; padding:3px; font-size:12px">' . $this->length . ' s</td></tr>';

        echo '<tr><td width="50%" style="background-color:#C1D2EE;padding:3px;font-size:12px">Emphasis:</td><td width="50%" style="background-color:#EEEEEE; padding:3px; font-size:12px">' . $emphasises[$this->emphasis] . '</td></tr>';

        echo '</table>';

        echo '</div>';
    }
}
