

/**
 * Database schema required by \yii\i18n\DbMessageSource.
 *
 * @author Dmitry Naumenko <d.naumenko.a@gmail.com>
 * @link http://www.yiiframework.com/
 * @copyright 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 * @since 2.0.7
 */


DROP TABLE IF EXISTS "WS_SOURCE_MESSAGE";
DROP TABLE IF EXISTS "message";

CREATE TABLE "WS_SOURCE_MESSAGE"
(
    "ID"          INTEGER NOT NULL PRIMARY KEY,
	"CATEGORY"    VARCHAR(255),
	"MESSAGE"     CLOB
);
CREATE SEQUENCE "WS_SOURCE_MESSAGE_SEQ";

CREATE TABLE "MESSAGE"
(
    "ID"          INTEGER NOT NULL,
	"LANGUAGE"    VARCHAR(16) NOT NULL,
	"TRANSLATION" CLOB,
	PRIMARY KEY ("ID", "LANGUAGE"),
	FOREIGN KEY ("ID") references "WS_SOURCE_MESSAGE" ("ID") ON DELETE CASCADE
);

CREATE INDEX idx_MESSAGE_LANGUAGE ON "MESSAGE"("LANGUAGE");
CREATE INDEX idx_WS_SOURCE_MESSAGE_CATEGORY ON "WS_SOURCE_MESSAGE"("CATEGORY");