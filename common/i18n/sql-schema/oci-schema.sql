/**
 * Database schema required by \yii\i18n\DbMessageSource.
 *
 * @author Dmitry Naumenko <d.naumenko.a@gmail.com>
 * @link http://www.yiiframework.com/
 * @copyright 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 * @since 2.0.7
 */


/**
 * Database schema required by \yii\i18n\DbMessageSource.
 *
 * @author Dmitry Naumenko <d.naumenko.a@gmail.com>
 * @link http://www.yiiframework.com/
 * @copyright 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 * @since 2.0.7
 */

DROP TABLE "WS_MESSAGE";
DROP TABLE "WS_SOURCE_MESSAGE";
DROP SEQUENCE "WS_SOURCE_MESSAGE_SEQ";

CREATE TABLE "WS_SOURCE_MESSAGE"
(
    "ID"       NUMBER(10) NOT NULL PRIMARY KEY,
    "CATEGORY" VARCHAR(255),
    "MESSAGE"  VARCHAR2(1000)
);
CREATE SEQUENCE "WS_SOURCE_MESSAGE_SEQ" START WITH 1;

CREATE TABLE "WS_MESSAGE"
(
    "ID"          NUMBER(10) NOT NULL,
    "LANGUAGE"    VARCHAR(16) NOT NULL,
    "TRANSLATION" VARCHAR2(1000),
    PRIMARY KEY ("ID", "LANGUAGE"),
    FOREIGN KEY ("ID") REFERENCES "WS_SOURCE_MESSAGE" ("ID") ON DELETE CASCADE
);

CREATE INDEX IDX_WS_MESSAGE_LANGUAGE ON "WS_MESSAGE" ("LANGUAGE");
CREATE INDEX IDX_WS_SOURCE_MESSAGE_CATEGORY ON "WS_SOURCE_MESSAGE" ("CATEGORY");


CREATE OR REPLACE TRIGGER WS_SOURCE_MESSAGE_SEQ
    BEFORE INSERT ON WS_SOURCE_MESSAGE
    FOR EACH ROW

BEGIN
    SELECT WS_SOURCE_MESSAGE_SEQ.NEXTVAL
           INTO   :NEW.ID
    FROM   DUAL;
END;