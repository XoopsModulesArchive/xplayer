CREATE TABLE xplayer_files (
    id        INT(11)      NOT NULL AUTO_INCREMENT,
    filename  TEXT         NOT NULL,
    added     DATETIME     NOT NULL DEFAULT '0000-00-00 00:00:00',
    title     VARCHAR(30)  NOT NULL DEFAULT '',
    artist    VARCHAR(30)  NOT NULL DEFAULT '',
    album     VARCHAR(30)  NOT NULL DEFAULT '',
    year      INT(4)       NOT NULL DEFAULT '',
    comment   VARCHAR(255) NOT NULL DEFAULT '',
    track     INT(3)       NOT NULL DEFAULT '',
    genre     VARCHAR(50)  NOT NULL DEFAULT '',
    length    VARCHAR(50)  NOT NULL DEFAULT '',
    bitrate   VARCHAR(50)  NOT NULL DEFAULT '',
    frequence VARCHAR(50)  NOT NULL DEFAULT '',
    PRIMARY KEY (id)
)
    ENGINE = ISAM
    AUTO_INCREMENT = 4;
CREATE TABLE xplayer_player (
    id        INT(11)      NOT NULL AUTO_INCREMENT,
    name      VARCHAR(128) NOT NULL DEFAULT '',
    html_code MEDIUMTEXT   NOT NULL,
    PRIMARY KEY (id)
)
    ENGINE = ISAM
    AUTO_INCREMENT = 4;
INSERT INTO `xplayer_player`
VALUES (1, 'Windows Media Player',
        '<object id="mediaplayer" classid="CLSID:22D6F312-B0F6-11D0-94AB-0080C74C7E95" codebase="http://activex.microsoft.com/activex/controls/mplayer/en/nsmp2inf.cab#Version=5,1,52,701" standby="Lade MP3-Datei..." type="audio/mpeg" width="400" height="45">\r\n <param name="FileName" value="<@mp3file@>">\r\n <param name="animationatStart" value="true">\r\n <param name="autoStart" value="true">\r\n <param name="PlayCount" value="1">\r\n <param name="Volume" value="50">\r\n <param name="ShowControls" value="true">\r\n <param name="ShowDisplay" value="false">\r\n</object>\r\n');
INSERT INTO `xplayer_player`
VALUES (2, 'Flash Player',
        '<object id="flash" classid="CLSID:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0" width="400" height="50">\r\n <param name="movie" value="onlamp.swf?mp3file=<@mp3file@>">\r\n <param name="quality" value="high">\r\n <param name="bgcolor" value="#708090">\r\n <embed src="onlamp.swf?mp3file=<@mp3file@>" quality="high" bgcolor="#708090" width="400" height="50" name="onlamp" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer">\r\n </embed>\r\n</object>');
INSERT INTO `xplayer_player`
VALUES (3, 'realOne Player',
        '<object id="realone" classid="CLSID:CFCDAA03-8BE4-11cf-B84B-0020AFBBCCFA" height="36" width="400">\r\n <param name="CONTROLS" value="ControlPanel">\r\n <param name="AUTOSTART" value="true">\r\n <param name="SRC" value="<@mp3file@>">\r\n <embed height="36" width="400" controls="ControlPanel" src="<@mp3file@>" type="audio/mpeg" autostart="true">\r\n </embed>\r\n</object>');
