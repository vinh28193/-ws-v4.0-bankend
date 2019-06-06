/**
 * Database schema required by \yii\rbac\DbManager.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @author Alexander Kochetov <creocoder@gmail.com>
 * @link http://www.yiiframework.com/
 * @copyright 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 * @since 2.0
 */

-- drop table "WS_AUTH_ASSIGNMENT";
-- drop table "WS_AUTH_ITEM_CHILD";
-- drop table "WS_AUTH_ITEM";
-- drop table "WS_AUTH_RULE";

-- create new auth_rule table
create table "WS_AUTH_RULE"
(
   "NAME"  varchar(64) not null,
   "DATA"  clob,
   "CREATED_AT"           integer,
   "UPDATED_AT"           integer,
    primary key ("NAME")
);

-- create auth_item table
create table "WS_AUTH_ITEM"
(
   "NAME"                 varchar(64) not null,
   "TYPE"                 smallint not null,
   "DESCRIPTION"          varchar(1000),
   "RULE_NAME"            varchar(64),
   "DATA"                 clob,
	 "CREATED_AT"           integer,
   "UPDATED_AT"           integer,
        foreign key ("RULE_NAME") references "WS_AUTH_RULE"("NAME") on delete set null,
        primary key ("NAME")
);
-- adds oracle specific index to auth_item
CREATE INDEX AUTH_TYPE_INDEX ON "WS_AUTH_ITEM"("TYPE");

create table "WS_AUTH_ITEM_CHILD"
(
   "PARENT"               varchar(64) not null,
   "CHILD"                varchar(64) not null,
   primary key ("PARENT","CHILD"),
   foreign key ("PARENT") references "WS_AUTH_ITEM"("NAME") on delete cascade,
   foreign key ("CHILD") references "WS_AUTH_ITEM"("NAME") on delete cascade
);

create table "WS_AUTH_ASSIGNMENT"
(
   "ITEM_NAME"            varchar(64) not null,
   "USER_ID"              varchar(64) not null,
   "CREATED_AT"           integer,
   primary key ("ITEM_NAME","USER_ID"),
   foreign key ("ITEM_NAME") references "WS_AUTH_ITEM" ("NAME") on delete cascade
);

CREATE INDEX AUTH_ASSIGNMENT_USER_ID_IDX ON "WS_AUTH_ASSIGNMENT" ("USER_ID");
