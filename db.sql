/*ALTER DATABASE melendres SET timezone TO 'America/Lima';*/

/*Tablas generales*/

CREATE TABLE "person"(
    "perId" SERIAL PRIMARY KEY,
    "perKindDoc" VARCHAR(5),
    "perNumberDoc" varchar(20),
    "perName" VARCHAR,
    "perAddress" VARCHAR,
    "perTel" varchar(10),
    "perEmail" varchar(50),
    "updated_at" timestamp,
    "created_at" timestamp
);

ALTER TABLE person ADD COLUMN "perTel2" VARCHAR(10);

ALTER TABLE person ADD COLUMN "perTel3" VARCHAR(10);

ALTER TABLE person
ADD CONSTRAINT dni_unique UNIQUE ("perNumberDoc");

insert into
    "person" ("perKindDoc", "perNumberDoc", "perName")
values
    (1, 'admin', 'admin')
    /*OTRA TABLA GENERAL ES LA TABLA USERS*/
    /*Start*/
    CREATE TABLE "headquarter"(
        "hqId" SERIAL PRIMARY KEY,
        "hqName" varchar(100),
        "hqRUC" varchar(12),
        "hqAddress" varchar(100),
        "updated_at" timestamp,
        "created_at" timestamp
    );

insert into "headquarter"("hqName") values('Pasco');

CREATE TABLE "category"(
    "catId" SERIAL PRIMARY KEY,
    "catCode" varchar(10),
    "catName" VARCHAR,
    "catNameLong" varchar,
    "catDescription" varchar,
    "catTelReq" int DEFAULT 2,
    /*1=SI, 2=NO*/
    "catLinkBus" int DEFAULT 2,
    /*1=si, 2="no" */
    "catAuth" int DEFAULT 1,
    /*1=NINGUNO, 2=DNI, 3=RUC, 4=CUALQUIERA*/
    /*Categoria Padre*/
    "catIdParent" INTEGER,
    /*Id foraneo de Sede*/
    "hqId" INTEGER,
    "updated_at" timestamp,
    "created_at" timestamp,
    /*referencias*/
    FOREIGN KEY ("catIdParent") REFERENCES category("catId"),
    FOREIGN KEY ("hqId") REFERENCES headquarter("hqId")
);

CREATE TABLE "teller"(
    "tellId" SERIAL PRIMARY KEY,
    /*datos generales*/
    "tellCode" varchar(10),
    "tellName" varchar(50),
    /*datos de control*/
    "tellMaxInWait" int,
    "tellState" int DEFAULT 1,
    /*1=Activo , 2=Inactivo*/
    /*Id foraneo de Sede*/
    "hqId" INTEGER,
    /*Id de usuario*/
    "userId" bigint,
    /*variables de tiempo*/
    "updated_at" timestamp,
    "created_at" timestamp,
    FOREIGN KEY ("userId") REFERENCES users(id),
    FOREIGN KEY ("hqId") REFERENCES headquarter("hqId")
);

CREATE TABLE d_category_teller(
    "dCatTellId" SERIAL PRIMARY KEY,
    "catId" INTEGER,
    "tellId" INTEGER,
    "updated_at" timestamp,
    "created_at" timestamp,
    FOREIGN KEY ("catId") REFERENCES category("catId"),
    FOREIGN KEY ("tellId") REFERENCES teller("tellId")
);

CREATE TABLE "appointment_temp"(
    /*Para sacar cita*/
    "apptmId" SERIAL PRIMARY KEY,
    "apptmTicketCode" varchar(10)
    /*catCode+'01' */,
    "apptmDateTimePrint" timestamp DEFAULT now(),
    "apptmSendFrom" varchar(10),
    /*web, totem, whatsApp*/
    /*datos del cliente*/
    "apptKindClient" int,
    /*2=Persona 1=Negocio*/
    "perId" int,
    "bussId" int,
    /*EL nro de documento y nombre del cliente viaja a esta tabla para un acceso rapido*/
    "apptmNumberDocClient" VARCHAR(12),
    /*RUC, DNI, ETC*/
    "apptmNameClient" varchar(50),
    /*Sede*/
    "hqId" INTEGER,
    /*foreing key para rapido acceso*/
    /*id de ventanilla y id de categoria*/
    "tellId" integer,
    "catId" integer,
    "catCode" varchar(10),
    "apptmNro" int,
    /*Transfer*/
    "apptmTransfer" int,
    "apptmTel" varchar(12),
    "apptmEmail" varchar(50),
    "apptmComment" varchar(200),
    /*Posiblemente en la siguiente tabla original*/
    "tellNameLong" varchar,
    /*atencion en ventanilla*/
    "apptmState" int default 1,
    /*En espera=1, En atención=2, Atendido=3, 4=no atendido 5=cancelado*/
    "apptmNroCalls" int default 0,
    "apptmDateStartAttention" timestamp,
    "apptmDateFinishAttention" timestamp,
    /*Comment client*/
    "apptmScoreClient" int,
    "apptmCommentClient" varchar(200),
    "apptmScoreDateClient" TIMESTAMP,
    "apptmCommentDateClient" TIMESTAMP,
    "updated_at" timestamp,
    "created_at" timestamp,
    FOREIGN KEY ("catId") REFERENCES category("catId"),
    FOREIGN KEY ("tellId") REFERENCES teller("tellId")
);

ALTER TABLE
    appointment_temp
ALTER COLUMN
    created_at
SET
    DEFAULT now();

CREATE TABLE "appointment"(
    /*Para sacar cita*/
    "apptmId" BIGINT PRIMARY KEY,
    "apptmTicketCode" varchar(10),
    /*catCode+'01' */
    "apptmDateTimePrint" timestamp,
    "apptmSendFrom" varchar(10),
    /*web, totem, whatsApp*/
    /*datos del cliente*/
    "apptKindClient" int,
    /*2=Persona 1=Negocio*/
    "perId" int,
    "bussId" int,
    /*EL nro de documento y nombre del cliente viaja a esta tabla para un acceso rapido*/
    "apptmNumberDocClient" VARCHAR(12),
    /*RUC, DNI, ETC*/
    "apptmNameClient" varchar(50),
    /*Sede*/
    "hqId" INTEGER,
    /*foreing key para rapido acceso*/
    /*id de ventanilla y id de categoria*/
    "tellId" integer,
    "catId" integer,
    "catCode" varchar(10),
    "apptmNro" int,
    /*Transfer*/
    "apptmTransfer" int,
    "apptmTel" varchar(12),
    "apptmEmail" varchar(50),
    "apptmComment" varchar(200),
    /*Posiblemente en la siguiente tabla original*/
    "tellNameLong" varchar,
    /*atencion en ventanilla*/
    "apptmState" int,
    /*En espera=1, En atención=2, Atendido=3, 4=no atendido 5=cancelado*/
    "apptmNroCalls" int,
    "apptmDateStartAttention" timestamp,
    "apptmDateFinishAttention" timestamp,
    /*comment client*/
    "apptmScoreClient" int,
    "apptmCommentClient" varchar(200),
    "apptmScoreDateClient" TIMESTAMP,
    "apptmCommentDateClient" TIMESTAMP,
    /*Client*/
    "updated_at" timestamp,
    "created_at" timestamp,
    /*fields with teller */
    "tellName" varchar(50),
    "tellCode" varchar(10),
    /*fields with category */
    "catNameLong" varchar,
    /*fields with user*/
    "userId" bigint,
    "perName" VARCHAR
);


CREATE TABLE "bussines"(
    "bussId" SERIAL PRIMARY KEY,
    "bussKind" varchar(10),
    /*juridica y natural con negocio*/
    "bussName" VARCHAR(500),
    "bussRUC" VARCHAR(12) UNIQUE,
    "bussAddress" varchar(500),
    /*Datos empresa o negocio*/
    "bussSunatUser" varchar(15),
    "bussSunatPass" varchar(15),
    "bussCodeSend" varchar(20),
    "bussCodeRNP" varchar(20),
    /*Bus AFP*/
    "bussAfpUser" varchar(20),
    "bussAfpPass" varchar(20),
    "bussDetractionsPass" varchar(20),
    "bussSimpleCode" varchar(20),
    "bussSisClave" varchar(20),
    /*Fin*/
    /*Fecha de ingreso*/
    "bussDateMembership" date,
    /*e inicio de actividades*/
    "bussDateStartedAct" date,
    "bussState" VARCHAR(5),
    /*Activo, suspendido, renicio */
    "bussStateDate" timestamp,
    /*Archivadores*/
    "bussFileKind" VARCHAR(5),
    /*Archivador y Folder*/
    "bussFileNumber" integer UNIQUE,
    "bussRegime" VARCHAR(5),
    /*Especial y MYPE triburatio y regimen general */
    "bussKindBookAcc" VARCHAR(5),
    /*TIpo de libro = Electronico y computarizado, */
    "bussObservation" text,
    "tellId" integer,
    "perId" INTEGER,
    FOREIGN KEY ("perId") REFERENCES person("perId"),
    "bussTel" varchar(10),
    "bussTel2" varchar(10),
    "bussTel3" varchar(10),
    "bussEmail" varchar(50),
    "updated_at" timestamp,
    "created_at" timestamp
);


create table controlExercise()
/*TRIGGERS*/
CREATE FUNCTION tf_b_i_category() RETURNS TRIGGER LANGUAGE PLPGSQL AS $ $ DECLARE _catNameLong varchar;

BEGIN
select
    "catNameLong" INTO _catNameLong
FROM
    category
WHERE
    "catId" = NEW."catIdParent";

IF _catNameLong is not null THEN 
    NEW."catNameLong": = CONCAT(_catNameLong, '/', NEW."catName");

END IF;

IF _catNameLong is null THEN NEW."catNameLong": = CONCAT('/', NEW."catName");

END IF;

RETURN NEW;

END;

$ $
/*
drop TRIGGER t_b_i_category on category;
drop FUNCTION tf_b_i_category;*/
CREATE TRIGGER t_b_i_category BEFORE
INSERT
    ON category FOR EACH ROW EXECUTE PROCEDURE tf_b_i_category();

/*Funcion y triger update*/




CREATE FUNCTION tf_b_u_category()
   RETURNS TRIGGER
   LANGUAGE PLPGSQL
AS $$
DECLARE
    _catNameLong varchar;
BEGIN

    IF COALESCE(NEW."catIdParent",-1)<>COALESCE(OLD."catIdParent" ,-1) OR NEW."catName"<>OLD."catName" THEN

            select "catNameLong" INTO _catNameLong FROM category WHERE "catId"=NEW."catIdParent";
        IF _catNameLong is not null THEN
            NEW."catNameLong":=CONCAT(_catNameLong,'/',NEW."catName");
        END IF;

        IF _catNameLong is null THEN
            NEW."catNameLong":=CONCAT('/',NEW."catName");
        END IF;

        UPDATE category set "catNameLong"=replace("catNameLong",  OLD."catNameLong", NEW."catNameLong") where "catNameLong" LIKE concat(OLD."catNameLong",'%') AND "catId"<>OLD."catId";
    END IF;
RETURN NEW;
END;
$$



/*
CREATE FUNCTION tf_b_u_category() RETURNS TRIGGER
LANGUAGE PLPGSQL AS
    $$ DECLARE _catNameLong varchar;
    BEGIN IF COALESCE(NEW.",-1)<>COALESCE(OLD.", -1)
    OR NEW."<>OLD." THEN
    select
        "CatNameLong" INTO _catNameLong FROM category WHERE " = NEW.";
            IF _catNameLong is not null THEN
                NEW.": = CONCAT(
            _catNameLong,
            ',NEW.");
            E
END IF;

IF _catNameLong is null THEN NEW."catNameLong": = CONCAT('/', NEW."catName");

END IF;

UPDATE
    category
set
    "catNameLong" = replace(
        "catNameLong",
        OLD."catNameLong",
        NEW."catNameLong"
    )
where
    "catNameLong" LIKE concat(OLD."catNameLong", '%')
    AND "hqId" = NEW."hqId"
    AND "catId" <> OLD."catId";

END IF;

RETURN NEW;

END;

$ $
*/
 CREATE TRIGGER t_b_u_category BEFORE
UPDATE
    ON category FOR EACH ROW EXECUTE PROCEDURE tf_b_u_category();

/*
drop TRIGGER t_b_U_category on category;
drop FUNCTION tf_b_U_category;*/

/*TRIGGERS*/

CREATE FUNCTION tf_b_i_appointment_temp() RETURNS TRIGGER
LANGUAGE PLPGSQL AS
    $$ DECLARE _catNameLong varchar;
    _o decimal;
    _tellId INTEGER;
    _tellState int;
    _maxApptmNro int;
    _
_nroCallPending int;

/*Categoria*/

_catId integer;

_catCode varchar(10);

_catLinkBus int;

_hqId integer;

_nroApptmStatePending int;

_nroApptmStateCurrent int;

_nroApptmStateAttended int;

BEGIN
/*Obtenemos algunos datos de categoria*/
select
    "catCode",
    "catLinkBus",
    "hqId" INTO _catCode,
    _catLinkBus,
    _hqId
from
    category
where
    "catId" = NEW."catId";

NEW."catCode":=_catCode;

NEW."hqId":=_hqId;

/*si esta vinculado a busi*/

IF NEW."tellId" is null THEN
/*seleccioamos aleatoriamente un ventanilla*/
/*select random() AS o, teller."tellId" INTO _o, _tellId from teller
            INNER JOIN d_category_teller
                on teller."tellId"=d_category_teller."tellId"
            INNER JOIN category
                on d_category_teller."catId"=category."catId"
                and (select "catNameLong" from category where "catId"=NEW."catId") like concat("catNameLong",'%') and teller."tellState"=1  ORDER BY o ASC;*/
IF _catLinkBus = 1 THEN
select
    "tellState",
    "tellId" into _tellState,
    _tellId
from
    teller
where
    "tellId" =(
        select
            "tellId"
        from
            bussines
        where
            "bussId" = NEW."bussId"
    );

IF _tellState = 1 THEN NEW."tellId": = _tellId;

END IF;

END IF;

IF NEW."tellId" is null THEN
/*  SELECT o, f."tellId", COALESCE("nroCallPending" ,0) as nroCallPending into _o, _tellId, _nroCallPending
                from (
                    select random() AS o, teller."tellId"  from teller
                        INNER JOIN d_category_teller
                            on teller."tellId"=d_category_teller."tellId"
                        INNER JOIN category
                            on d_category_teller."catId"=category."catId" where
                            (select "catNameLong" from category where "catId"=new."catId") like concat("catNameLong",'%') and teller."tellState"=1 and teller."hqId"=_hqId )  f
                LEFT JOIN (select  "tellId",count(*) AS "nroCallPending" from appointment_temp  where "apptmState"='1'/GROUP BY "tellId")  s on f."tellId"=s."tellId"  ORDER BY nroCallPending ASC, o ASC limit 1;
        */
SELECT
    o,
    f."tellId",
    COALESCE("nroApptmStatePending", 0) as nroApptmStatePending,
    COALESCE("nroApptmStateCurrent", 0) as nroApptmStateCurrent,
    COALESCE("nroApptmStateAttended", 0) as nroApptmStateAttended into _o,
    _tellId,
    _nroApptmStatePending,
    _nroApptmStateCurrent,
    _nroApptmStateAttended
from
    (
        select
            random() AS o,
            teller."tellId"
        from
            teller
            INNER JOIN d_category_teller on teller."tellId" = d_category_teller."tellId"
            INNER JOIN category on d_category_teller."catId" = category."catId"
        where
            (
                select
                    "catNameLong"
                from
                    category
                where
                    "catId" = new."catId"
            ) like concat("catNameLong", '%')
            and teller."tellState" = 1
            and teller."hqId" = _hqId
    ) f
    LEFT JOIN (
        select
            "tellId",
            sum(
                case
                    when "apptmState" = 1 then 1
                    else 0
                end
            ) as "nroApptmStatePending",
            sum(
                case
                    when "apptmState" = 2 then 1
                    else 0
                end
            ) as "nroApptmStateCurrent",
            sum(
                case
                    when "apptmState" = 3 then 1
                    else 0
                end
            ) as "nroApptmStateAttended"
        from
            appointment_temp
        GROUP BY
            "tellId"
    ) s on f."tellId" = s."tellId"
ORDER BY
    nroApptmStatePending ASC,
    nroApptmStateCurrent ASC,
    nroApptmStateAttended,
    o ASC
limit
    1;

/*Verificamos que exi*/

IF _tellId IS NULL THEN 
    RAISE EXCEPTION '<msg>Lo sentimos, en este momento no disponemos de ventanillas para este servicio.<msg>';

END IF;

NEW."tellId":=_tellId;

END IF;

/*select random() AS o, teller."tellId" INTO _o, _tellId from teller
            INNER JOIN d_category_teller
                on teller."tellId"=d_category_teller."tellId"
            INNER JOIN category
                on d_category_teller."catId"=category."catId"
                and (select "catNameLong" from category where "catId"=NEW."catId") like concat("catNameLong",'%') and teller."hqId"=_hqId  ORDER BY o ASC;
                */

/*Verificamos que exi*/

NEW."tellId":=_tellId;

END IF;

/*Generamos el codigo */

select
    COALESCE(max("apptmNro"), 0) into _maxApptmNro
from
    appointment_temp
where
    "catId" = NEW."catId"
    and CAST(created_at as date) = CAST(now() AS date);

NEW."apptmNro":=_maxApptmNro+1;

RETURN NEW;

END;

$ $
/*
drop TRIGGER t_b_i_appointment_temp on appointment_temp;
drop FUNCTION tf_b_i_appointment_temp;*/
CREATE TRIGGER t_b_i_appointment_temp BEFORE
INSERT
    ON appointment_temp FOR EACH ROW EXECUTE PROCEDURE tf_b_i_appointment_temp();

/*TRIGGER ON DELETE APPOINTMENT TEMP*/




CREATE OR REPLACE FUNCTION tf_b_d_appointment_temp()
 RETURNS trigger
 LANGUAGE plpgsql
AS $function$
DECLARE
    
    /*fields with teller */
    _tellName varchar(50);
    _tellCode varchar(10);

    /*fields with category */
    _catNameLong varchar;

    /*fields with user*/
    _userId bigint;
    _perName VARCHAR;
    
BEGIN

    select 
        "tellName", "tellCode", "userId", "perName" 
        into _tellName, _tellCode, _userId, _perName
        from teller inner join users on teller."userId"=users.id 
        left join person on  users."perId"=person."perId" where teller."tellId" = OLD."tellId";
        
    select "catNameLong" into _catNameLong from category where category."catId"=OLD."catId";

    insert into appointment
                (
                    "apptmId",
                    "apptmTicketCode",
                    "apptmDateTimePrint",
                    "apptmSendFrom",

                    "apptKindClient",
                    "perId",
                    "bussId",
    
                    "apptmNumberDocClient",
                    "apptmNameClient",

                    /*Sede*/
                    "hqId",

                    /*id de ventanilla y id de categoria*/
                    "tellId",

                    "catId",
                    "catCode",
                    "apptmNro",
                    /*Transfer*/
                    "apptmTransfer",
                    "apptmTel",
                    "apptmEmail",
                    "apptmComment",

                    /*Posiblemente en la siguiente tabla original*/
                    "tellNameLong",

                    /*atencion en ventanilla*/
                    "apptmState", /*En espera=1, En atención=2, Atendido=3, 4=no atendido 5=cancelado*/
                    "apptmNroCalls",
                    "apptmDateStartAttention",
                    "apptmDateFinishAttention",

                    /*Comment client*/
                    "apptmScoreClient", 
                    "apptmCommentClient",
                    "apptmScoreDateClient",
                    "apptmCommentDateClient",

                    "updated_at",
                    "created_at",


                    /*fields with teller */
                    "tellName",
                    "tellCode",

                    /*fields with category */
                    "catNameLong",

                    /*fields with user*/
                    "userId", 
                    "perName",
                    
                    /*NEW FIELDS*/
                    "apptmEpochInWaiting",
                    "apptmEpochInAtention"
                    ) 
            VALUES  (
                    OLD."apptmId",
                    OLD."apptmTicketCode",
                    OLD."apptmDateTimePrint",
                    OLD."apptmSendFrom",

                    OLD."apptKindClient",
                    OLD."perId",
                    OLD."bussId",
    
                    OLD."apptmNumberDocClient",
                    OLD."apptmNameClient",

                    /*Sede*/
                    OLD."hqId",

                    /*id de ventanilla y id de categoria*/
                    OLD."tellId",

                    OLD."catId",
                    OLD."catCode",
                    OLD."apptmNro",
                    /*Transfer*/
                    OLD."apptmTransfer",
                    OLD."apptmTel",
                    OLD."apptmEmail",
                    OLD."apptmComment",

                    /*Posiblemente en la siguiente tabla original*/
                    OLD."tellNameLong",

                    /*atencion en ventanilla*/
                    OLD."apptmState", /*En espera=1, En atención=2, Atendido=3, 4=no atendido 5=cancelado*/
                    OLD."apptmNroCalls",
                    OLD."apptmDateStartAttention",
                    OLD."apptmDateFinishAttention",

                    /*Comment client*/
                    OLD."apptmScoreClient", 
                    OLD."apptmCommentClient",
                    OLD."apptmScoreDateClient",
                    OLD."apptmCommentDateClient",


                    OLD."updated_at",
                    OLD."created_at",


                    /*fields with teller */
                    _tellName,
                    _tellCode,

                    /*fields with category */
                    _catNameLong,

                    /*fields with user*/
                    _userId, 
                    _perName,

                    /*NEW FIELDS*/
                    OLD."apptmEpochInWaiting",
                    OLD."apptmEpochInAtention"
            );
RETURN OLD;
END;
$function$



 CREATE TRIGGER t_b_d_appointment_temp BEFORE DELETE ON appointment_temp FOR EACH ROW EXECUTE PROCEDURE tf_b_d_appointment_temp();

/*
delete from appointment_temp where "apptmId"=18
drop TRIGGER t_b_d_appointment_temp on appointment_temp;
drop FUNCTION tf_b_d_appointment_temp;*/

CREATE TABLE videos(
    "vidId" SERIAL PRIMARY KEY,
    "vidName" VARCHAR(200),
    "vidLink" VARCHAR(500),
    "vidState" char(5),
    /*Habilitado, Deshabilitado*/
    "updated_at" timestamp,
    "created_at" timestamp
);

CREATE TABLE cards(
    "cardId" SERIAL PRIMARY KEY,
    "cardName" VARCHAR(200),
    "cardPhrases" VARCHAR(500),
    "cardState" char(5),
    /*Habilitado, Deshabilitado*/
    "updated_at" timestamp,
    "created_at" timestamp
);

/**CAMBIOS AL 20/05/2022*/

create table periods(
    "prdsId" SERIAL PRIMARY KEY,
    "prdsNameShort" VARCHAR(10),
    "prdsDescription" varchar(200),
    
    "prdsState" int,
    "updated_at" timestamp,
    "created_at" timestamp 

);

create table d_bussines_periods(
    "dbpId" SERIAL PRIMARY KEY,
    "prdsId" integer,
    "bussId" integer,
    "dbpState" int,

    /**/
    "dbpCost" decimal(12,2) DEFAULT 0.0,
    "dbpCostDate" TIMESTAMP,

    "dbpDebt" decimal(12,2) DEFAULT 0.0,
    "dbpDebtDate" TIMESTAMP,

    "dbpPaid" decimal(12,2) DEFAULT 0.0,
    "dbpPaidDate" TIMESTAMP,
    UNIQUE("prdsId", "bussId"),
    "updated_at" timestamp,
    "created_at" timestamp, 

    FOREIGN KEY ("prdsId") REFERENCES periods("prdsId"), 
    FOREIGN KEY ("bussId") REFERENCES bussines("bussId")
);
/*create table model_of_services(
    "msId" serial primary key,
    "msName" varchar(150),
    "msTimeInterval" varchar(50),
    "updated_at" timestamp,
    "created_at" timestamp
);*/

/*create table services_provided(
    "spId" SERIAL PRIMARY KEY,
    "dbpId" INTEGER,
    "spState" int DEFAULT 1,

    "spTimeInterval" VARCHAR(50),
    "spName" varchar(150),
    "spComment" varchar(200),
    "spCost" decimal(12, 2) DEFAULT 0.0,
    "spCostDate" TIMESTAMP,
    "spDebt" decimal(12, 2) DEFAULT 0.0,
    "spDebtDate" TIMESTAMP,
    "spPaid" decimal(12, 2) DEFAULT 0.0,
    "spPaidDate" TIMESTAMP,
    "spLimitPaymentDate" DATE,
    "spMaxPartToPay" INTEGER DEFAULT 1,
    "created_by" BIGINT,
    "updated_by" BIGINT,
    "updated_at" timestamp,
    "created_at" timestamp
);*/




create table services(
    "svId" serial primary key,
    "svName" varchar(150),
    "svState" varchar(2), /*Habilitado, Deshabilitado*/

    "updated_at" timestamp,
    "created_at" timestamp

);


create table period_payments(
    "ppayId" SERIAL PRIMARY KEY,
    "ppayName" varchar(20),
    "ppayState" int default 1
);  

insert into period_payments
("ppayName") VALUES
('Enero'),
     ('Febrero'),
    ( 'Marzo'),
     ('Abril'),
     ('Mayo'),
     ('Junio'),
     ('Julio'),
     ('Agosto'),
     ('Setiembre'),
     ('Octubre'),
    ('Noviembre'),
    ('Diciembre'),
    ('Anual'),
    ('Ninguno/Otros');

create table services_provided(
    "spId" SERIAL PRIMARY KEY,
    "dbpId"  INTEGER,
    "svId" INTEGER,

    "ppayId" INTEGER,
    /*"spPeriodPayment" INTEGER,*/
    "spName" varchar(150),
        
    "spCost" decimal(12,2) DEFAULT 0.0,
    "spCostDate" TIMESTAMP,

    "spDebt" decimal(12,2) DEFAULT 0.0,
    "spDebtDate" TIMESTAMP,

    "spPaid" decimal(12,2) DEFAULT 0.0,
    "spPaidDate" TIMESTAMP,
        
    "spState" int DEFAULT 1, /*1=Borrador, 2=*/
    "spComment" varchar(200),

    "spLimitPaymentDate" DATE,

    "spMaxPartToPay" INTEGER DEFAULT 1,

    "created_by" BIGINT,
    "updated_by" BIGINT,
    
    "updated_at" timestamp,
    "created_at" timestamp, 
    FOREIGN KEY("svId") REFERENCES services("svId"), 
    FOREIGN KEY("dbpId") REFERENCES d_bussines_periods("dbpId"), 
    FOREIGN KEY ("ppayId") REFERENCES period_payments("ppayId")
);


create table correlative_proof(
    "cpfId" integer PRIMARY KEY,
    "hqId" integer, 
    "cpfKindDoc" int, /*1=Recibo, 2=Boleta, 3=Factura*/
    "cpfNameTypeProof" varchar(30),
    "cpfSerie" varchar(10), 
    "cpfNumber" integer,
    UNIQUE("hqId", "cpfKindDoc"),
    FOREIGN KEY ("hqId") REFERENCES headquarter("hqId")
);
INSERT INTO correlative_proof
("cpfId", "hqId", "cpfKindDoc","cpfNameTypeProof", "cpfSerie", "cpfNumber") VALUES
(1, 1/*Pasco*/, 1/*Ticket*/,'Ticket - Pasco','T001', 1 ),
(2, 1/*Pasco*/, 2/*BOleta*/,'Boleta - Pasco','B001', 1 ),
(3, 1/*Pasco*/, 3/*Factura*/,'Factura - Pasco','F001', 1 );


/*payment_details*/
create table payments(
    "payId" serial PRIMARY KEY,
    "payToken" varchar(40),
    "payState" int NOT NULL,
    /*1=Borrador, 2=Reservado, 3=Facturado*/
    "hqId" INTEGER NOT NULL,
    "apptmId" integer,
    /*sede*/
    "payKindDoc" int not null,
    /*1=Recibo, 2=Boleta, 3=Factura*/
    "paySerie" varchar(10),
    /*Serie */
    "payNumber" integer,
    /*Numero correlativo*/
    "payDatePrint" TIMESTAMP,
    "bussId" INTEGER,
    "tellId" integer,
    /*userId facturado*/
    "userId" BIGINT,
    /*CLientes sin regisgro en base de datos*/
    "payClientName" varchar(150),
    "payClientAddress" varchar(200),
    "payClientTel" varchar(12),
    "payClientEmail" varchar(100),
    "payClientRucOrDni" varchar(12),

    /*Igv 09/06/2022*/
    "payTaxAcumulate" int default 1, /*1=Acumulado, 2=NO acumulado*/
    "paySubPreviusTotal" DECIMAL(12,2) DEFAULT 0.0, /*El total previo*/ 
    /*Igv 09/06/2022*/
    /*Campos para clientes no registrados*/
    "paySubTotal" decimal(12, 2) DEFAULT 0.0,
    "payDiscount" decimal(12, 2) DEFAULT 0.0,
    "payTotalTaxBase" decimal(12, 2) DEFAULT 0.0,
    "payTaxPercent" decimal (12,2) DEFAULT 0.18, 
    "paySalesTax" decimal(12, 2) DEFAULT 0.0,
    "payTotal" decimal(12, 2) DEFAULT 0.0,
    "payTotalInWords" VARCHAR(100),

    "payIsCanceled" int DEFAULT 2,/*1=cancelado, 2=no cancelado*/
    
    "payTicketSN" varchar(50),
    "payInvoiceSN" varchar(50),

    "created_by" BIGINT,
    "updated_by" BIGINT,
    "updated_at" timestamp,
    "created_at" timestamp
);
create table payment_details(
    "pdsId" serial PRIMARY KEY,
    "payId" INTEGER,
    "pdsQuantity" decimal(8, 2) DEFAULT 1,
    "spId" integer,
    /*
        "pdsPeriod" varchar(20),
        "pdsYear" int,*/
    "pdsDescription" varchar(200),
    "pdsUnitPrice" decimal(12, 2) DEFAULT 0.0,
    "pdsAmount" decimal(12, 2) DEFAULT 0.0,

    "pdsIsCanceled" int DEFAULT 2,/*1=cancelado, 2=no cancelado*/
    "created_by" BIGINT,
    "updated_by" BIGINT,
    "updated_at" timestamp,
    "created_at" timestamp, 
    FOREIGN KEY ("payId") REFERENCES payments("payId")
);

create table payment_methods(
    "paymthdsId" SERIAL PRIMARY KEY,
    "paymthdsName" varchar(30),
    "paymthdsState" int,
    /*1=activo, 2=inactivo*/
    "updated_at" timestamp,
    "created_at" timestamp
);

create table d_payments_payment_methods(
    "dppmId" serial PRIMARY KEY,
    "payId" INTEGER,
    "paymthdsId" INTEGER,
    "dppmAmount" decimal(12, 2),
    "dppmDescription" varchar(50),
    "created_by" BIGINT,
    "updated_by" BIGINT,
    "updated_at" timestamp,
    "created_at" timestamp, 
    FOREIGN KEY ("payId") REFERENCES payments("payId"),
    FOREIGN KEY ("paymthdsId") REFERENCES payment_methods("paymthdsId")
  
); 




/*FUNCIONES Y TRIGER*/
/*Numero aleatorio*/
create or replace function random_string(length integer)  returns text as 
$$ 
 declare 
 chars text[] := '{0,1,2,3,4,5,6,7,8,9,A,B,C,D,E,F,G,H,I,J,K,L,M,N,O,P,Q,S,T,U,V,W,X,Y,Z,a,b,c,d,e,f,g,h,i,j,k,l,m,n,o,p,q,r,s,t,u,v,w,x,y,z}'; 
 result text := '';
  i integer := 0;
begin
 if length < 0 then 
    raise exception 'Given length cannot be less than 0'; 
 end if; 
 for i in 1..length loop
  result := result || chars[1+random()*(array_length(chars, 1)-1)]; 
 end loop; 
 return result; 
end; 
$$ language plpgsql;



/*Funcion y trigger payment_details*/
CREATE FUNCTION tf_b_i_payment_details() RETURNS TRIGGER
LANGUAGE PLPGSQL AS
    $$ 
DECLARE
    
    _spCost decimal(12, 2);
    _spDebt decimal(12, 2);
    _spPaid decimal(12, 2); 

    _sumPaidFromPD decimal(12,2);

BEGIN

IF COALESCE(NEW."pdsQuantity",-1)<=0 THEN
    RAISE EXCEPTION '<msg>La cantidad es requerido y tiene que ser mayor a 0<msg>';
END IF;

IF COALESCE(NEW."pdsUnitPrice",-1)<=0 THEN
    RAISE EXCEPTION '<msg>El precio unitario es requerido y tiene que ser mayor a 0<msg>';
END IF;

NEW."pdsAmount":=NEW."pdsUnitPrice"*NEW."pdsQuantity";


RETURN NEW;

END;

$$

/*
drop TRIGGER t_b_i_appointment_temp on appointment_temp;
drop FUNCTION tf_b_i_appointment_temp;*/
CREATE TRIGGER t_b_i_payment_details BEFORE
INSERT
    ON payment_details FOR EACH ROW EXECUTE PROCEDURE tf_b_i_payment_details();



CREATE FUNCTION tf_a_i_payment_details() RETURNS TRIGGER
LANGUAGE PLPGSQL AS
    $$ 
DECLARE
    
    _spCost decimal(12, 2);
    _spDebt decimal(12, 2);
    _spPaid decimal(12, 2); 

    _sumPaidFromPD decimal(12,2);

    _subPreviusTotal decimal(12,2);
    

BEGIN

IF COALESCE(NEW."spId", -1)>0 THEN
    SELECT "spCost", "spDebt", "spPaid" INTO  _spCost, _spDebt, _spPaid FROM services_provided where "spId"=NEW."spId";
    SELECT COALESCE(SUM("pdsAmount"),0) into _sumPaidFromPD FROM payment_details where "spId"=NEW."spId" and "pdsIsCanceled"=2/*NO CANCELADO*/;
    IF _spCost < _sumPaidFromPD THEN 
        RAISE EXCEPTION '<msg>El pago por el servicio no puede superar el costo establecido.<msg>';

    END IF;
    UPDATE services_provided set  "spPaid" =_sumPaidFromPD, "spDebt"= _spCost-_sumPaidFromPD where "spId"=NEW."spId";

END IF;

SELECT sum("pdsAmount") into _subPreviusTotal from payment_details where "payId"=NEW."payId";

UPDATE payments set "paySubPreviusTotal"=_subPreviusTotal where "payId"=NEW."payId";


RETURN NEW;

END;

$$

/*
drop TRIGGER t_a_i_payment_details on payment_details;
drop FUNCTION tf_a_i_payment_details;*/
CREATE TRIGGER t_a_i_payment_details AFTER
INSERT
    ON payment_details FOR EACH ROW EXECUTE PROCEDURE tf_a_i_payment_details();



/*09/06/2022*/
CREATE FUNCTION tf_a_u_payment_details() RETURNS TRIGGER
LANGUAGE PLPGSQL AS
    $$ 
DECLARE
    
    _spCost decimal(12, 2);
    _spDebt decimal(12, 2);
    _spPaid decimal(12, 2); 

    _sumPaidFromPD decimal(12,2);

    _subPreviusTotal decimal(12,2);
BEGIN


IF COALESCE(NEW."spId", -1)>0 AND NEW."pdsIsCanceled"<>OLD."pdsIsCanceled" THEN
    SELECT "spCost", "spDebt", "spPaid" INTO  _spCost, _spDebt, _spPaid FROM services_provided where "spId"=NEW."spId";
    SELECT COALESCE(SUM("pdsAmount"),0) into _sumPaidFromPD FROM payment_details where "spId"=NEW."spId" and "pdsIsCanceled"=2/*NO CANCELADO*/;
    IF _spCost < _sumPaidFromPD THEN 
        RAISE EXCEPTION '<msg>El pago por el servicio no puede superar el costo establecido.<msg>';
    END IF;
    UPDATE services_provided set  "spPaid" =_sumPaidFromPD, "spDebt"= _spCost-_sumPaidFromPD where "spId"=NEW."spId";
END IF;

RETURN NEW;

END;

$$

/*
drop TRIGGER t_a_u_payment_details on payment_details;
drop FUNCTION tf_a_u_payment_details;*/
CREATE TRIGGER t_a_u_payment_details AFTER
UPDATE
    ON payment_details FOR EACH ROW EXECUTE PROCEDURE tf_a_u_payment_details();






/*payments */


CREATE FUNCTION tf_b_i_payments() RETURNS TRIGGER
LANGUAGE PLPGSQL AS
    $$ 
DECLARE
    _payTokenTemp varchar(40);


BEGIN
    select concat(random_string(12),NEW."payId") into _payTokenTemp;
    NEW."payToken":= _payTokenTemp;
RETURN NEW;

END;

$$

/*
drop TRIGGER t_b_i_payments on payments;
drop FUNCTION tf_b_i_payments;*/
CREATE TRIGGER t_b_i_payments BEFORE
INSERT
    ON payments FOR EACH ROW EXECUTE PROCEDURE tf_b_i_payments();





CREATE FUNCTION tf_b_u_payments() RETURNS TRIGGER
LANGUAGE PLPGSQL AS
    $$ 
DECLARE
    
    _spCost decimal(12, 2);
    _spDebt decimal(12, 2);
    _spPaid decimal(12, 2); 

    _sumPaidFromPD decimal(12,2);

    _serie varchar(10);
    _number integer;

    _payTotalInWords varchar(100);
    /*Añadidos 09/06/2022*/
    _paySubTotal decimal(18, 8);
    _payDiscount decimal(18, 8);
    _payTotalTaxBase decimal(18, 8);
    _payTaxPercent decimal (18,8);
    _paySalesTax decimal(18, 8);
    _payTotal decimal(18, 8);

BEGIN
/*Analizamos que no sea una boleta facturada*/
IF NEW."payState" <> 3/*Facturado*/ AND  OLD."payState"=3/*Facturado*/ THEN
    RAISE EXCEPTION '<msg>Lo sentimos, este ticket no esta disponible para modificar.<msg>';
END IF;


IF NEW."payKindDoc"=1/*Tickets*/  AND OLD."payState"<>3/*Facturado*/ THEN
    
    NEW."payTotal":=NEW."paySubTotal";

      
    IF NEW."payTaxAcumulate"=1 /*Acumulado*/ THEN
        _paySubTotal:=(NEW."paySubPreviusTotal"/(1+NEW."payTaxPercent"));
    END IF;
    IF NEW."payTaxAcumulate"=2 /*NO acumulado*/ THEN
        _paySubTotal:="paySubPreviusTotal";
    END IF;
    _payTotalTaxBase:=_paySubTotal-NEW."payDiscount";
    _paySalesTax:=_payTotalTaxBase*(NEW."payTaxPercent");
    _payTotal:=_payTotalTaxBase+_payTotalTaxBase*(NEW."payTaxPercent");

    NEW."paySubTotal":=_paySubTotal;
    NEW."payTotalTaxBase":=_payTotalTaxBase;
    NEW."paySalesTax":= _paySalesTax;
    NEW."payTotal":=_payTotal;
END IF;

IF  NEW."payState"=3 AND OLD."payState"<>3 THEN
    SELECT "cpfSerie", "cpfNumber" into _serie, _number from correlative_proof where "hqId"=new."hqId" and "cpfKindDoc"=new."payKindDoc";
    UPDATE correlative_proof SET "cpfNumber"=(_number+1) where "hqId"=new."hqId" and "cpfKindDoc"=new."payKindDoc";
    NEW."paySerie":=_serie;
    NEW."payNumber":=_number;
    SELECT fu_numero_letras(NEW."payTotal") into _payTotalInWords;
    NEW."payTotalInWords":=_payTotalInWords;
END IF;

IF NEW."payIsCanceled"<>OLD."payIsCanceled" THEN
    UPDATE payment_details set "pdsIsCanceled"=NEW."payIsCanceled" where "payId"=NEW."payId";
END IF;


RETURN NEW;

END;

$$

/*
drop TRIGGER t_b_u_payments on payments;
drop FUNCTION tf_b_u_payments;*/
CREATE TRIGGER t_b_u_payments BEFORE
UPDATE
    ON payments FOR EACH ROW EXECUTE PROCEDURE tf_b_u_payments();

/*services_provided*/

CREATE FUNCTION tf_b_i_services_provided() RETURNS TRIGGER
LANGUAGE PLPGSQL AS
    $$ 
DECLARE
    _spName varchar(150);
    
    _periodName varchar(10);
    _serviceName varchar(150);
    _periodPaymentName varchar(30);

BEGIN

    /*Periodos*/
    select  "prdsNameShort" into _periodName from periods  inner join 
    d_bussines_periods on periods."prdsId"=d_bussines_periods."prdsId" where d_bussines_periods."dbpId"=NEW."dbpId";

    /*subPeriodo*/

    /*Servicio */
    select "svName" into _serviceName from services where "svId"=NEW."svId";
    
    select "ppayName" into _periodPaymentName from period_payments where "ppayId"=NEW."ppayId";
    
    NEW."spName":=CONCAT(COALESCE(_serviceName,'-'), ' / ' ,  COALESCE(_periodPaymentName, '-'),' / ', COALESCE(_periodName, '-'));

    /*inserte monto*/


    IF COALESCE(NEW."spPaid" , 0)> COALESCE(NEW."spCost",0)/*Tickets*/ THEN
        RAISE EXCEPTION '<msg>El costo no puede ser menor al pago.<msg>';
    END IF;
    NEW."spDebt":=COALESCE(NEW."spCost",0)-COALESCE(NEW."spPaid",0);
RETURN NEW;
END;
$$

/*
drop TRIGGER t_b_i_services_provided on services_provided;
drop FUNCTION tf_b_i_services_provided;*/
CREATE TRIGGER t_b_i_services_provided BEFORE
INSERT
    ON services_provided FOR EACH ROW EXECUTE PROCEDURE tf_b_i_services_provided();



CREATE FUNCTION tf_b_u_services_provided() RETURNS TRIGGER
LANGUAGE PLPGSQL AS
    $$ 
DECLARE
    
        _spName varchar(150);
    
    _periodName varchar(10);
    _serviceName varchar(150);
    _periodPaymentName varchar(30);
BEGIN

/*Periodos*/
    select  "prdsNameShort" into _periodName from periods  inner join 
    d_bussines_periods on periods."prdsId"=d_bussines_periods."prdsId" where d_bussines_periods."dbpId"=NEW."dbpId";

    /*subPeriodo*/

    /*Servicio */
    select "svName" into _serviceName from services where "svId"=NEW."svId";
    
    select "ppayName" into _periodPaymentName from period_payments where "ppayId"=NEW."ppayId";
        NEW."spName":=CONCAT(COALESCE(_serviceName,'-'), ' / ' ,  COALESCE(_periodPaymentName, '-'),' / ', COALESCE(_periodName, '-'));

 /*   NEW."spName":=CONCAT(COALESCE(_periodName, '-'),' / ',COALESCE(_periodPaymentName, '-'),' / ',COALESCE(_serviceName,'-'));*/


    IF COALESCE(NEW."spPaid" , 0)> COALESCE(NEW."spCost",0)/*Tickets*/ THEN
        RAISE EXCEPTION '<msg>El costo no puede ser menor al pago.<msg>';
    END IF;
    NEW."spDebt":=COALESCE(NEW."spCost",0)-COALESCE(NEW."spPaid",0);

RETURN NEW;

END;

$$

/*
drop TRIGGER t_b_u_services_provided on services_provided;
drop FUNCTION tf_b_u_services_provided;*/
CREATE TRIGGER t_b_u_services_provided BEFORE
UPDATE
    ON services_provided FOR EACH ROW EXECUTE PROCEDURE tf_b_u_services_provided();


/*Update trigger 01/06/2022*/

CREATE FUNCTION tf_a_i_services_provided() RETURNS TRIGGER
LANGUAGE PLPGSQL AS
    $$ 
DECLARE

    _dbpCost decimal(12,2);
    _dbpPaid decimal(12,2);
BEGIN

    SELECT COALESCE(SUM("spCost"),0) into _dbpCost FROM services_provided where "dbpId"=new."dbpId";
    SELECT COALESCE(SUM("spPaid"),0) into _dbpPaid FROM services_provided where "dbpId"=new."dbpId";
    
    UPDATE d_bussines_periods SET "dbpCost"=_dbpCost, "dbpPaid"=_dbpPaid, "dbpDebt"=_dbpCost-_dbpPaid where "dbpId"=new."dbpId";
    
RETURN NEW;

END;

$$

/*
drop TRIGGER t_a_i_services_provided on services_provided;
drop FUNCTION tf_a_i_services_provided;*/
CREATE TRIGGER t_a_i_services_provided AFTER
INSERT
    ON services_provided FOR EACH ROW EXECUTE PROCEDURE tf_a_i_services_provided();
    



CREATE FUNCTION tf_a_u_services_provided() RETURNS TRIGGER
LANGUAGE PLPGSQL AS
    $$ 
DECLARE

    _dbpCost decimal(12,2);
    _dbpPaid decimal(12,2);
BEGIN

    SELECT COALESCE(SUM("spCost"),0) into _dbpCost FROM services_provided where "dbpId"=new."dbpId";
    SELECT COALESCE(SUM("spPaid"),0) into _dbpPaid FROM services_provided where "dbpId"=new."dbpId";
    
    UPDATE d_bussines_periods SET "dbpCost"=_dbpCost, "dbpPaid"=_dbpPaid, "dbpDebt"=_dbpCost-_dbpPaid where "dbpId"=new."dbpId";
    
RETURN NEW;

END;

$$

/*
drop TRIGGER t_a_u_services_provided on services_provided;
drop FUNCTION tf_a_u_services_provided;*/
CREATE TRIGGER t_a_u_services_provided AFTER
UPDATE
    ON services_provided FOR EACH ROW EXECUTE PROCEDURE tf_a_u_services_provided();
    

    /*NUMERO PARA CONVERTIR DE MONTO A LETRAS*/
CREATE OR REPLACE FUNCTION fu_numero_letras(numero numeric) RETURNS text AS
$body$
DECLARE
     lnEntero INTEGER;
     lcRetorno TEXT;
     lnTerna INTEGER;
     lcMiles TEXT;
     lcCadena TEXT;
     lnUnidades INTEGER;
     lnDecenas INTEGER;
     lnCentenas INTEGER;
     lnFraccion INTEGER;
     lnSw INTEGER;
BEGIN
     lnEntero := FLOOR(numero)::INTEGER;--Obtenemos la parte Entera
     lnFraccion := FLOOR(((numero - lnEntero) * 100))::INTEGER;--Obtenemos la Fraccion del Monto
     lcRetorno := '';
     lnTerna := 1;
     IF lnEntero > 0 THEN
     lnSw := LENGTH(cast(lnEntero as varchar));
     WHILE lnTerna <= lnSw LOOP
        -- Recorro terna por terna
        lcCadena = '';
        lnUnidades = lnEntero % 10;
        lnEntero = CAST(lnEntero/10 AS INTEGER);
        lnDecenas = lnEntero % 10;
        lnEntero = CAST(lnEntero/10 AS INTEGER);
        lnCentenas = lnEntero % 10;
        lnEntero = CAST(lnEntero/10 AS INTEGER);
    -- Analizo las unidades
       SELECT
         CASE /* UNIDADES */
           WHEN lnUnidades = 1 AND lnTerna = 1 THEN 'UNO ' || lcCadena
           WHEN lnUnidades = 1 AND lnTerna <> 1 THEN 'UN ' || lcCadena
           WHEN lnUnidades = 2 THEN 'DOS ' || lcCadena
           WHEN lnUnidades = 3 THEN 'TRES ' || lcCadena
           WHEN lnUnidades = 4 THEN 'CUATRO ' || lcCadena
           WHEN lnUnidades = 5 THEN 'CINCO ' || lcCadena
           WHEN lnUnidades = 6 THEN 'SEIS ' || lcCadena
           WHEN lnUnidades = 7 THEN 'SIETE ' || lcCadena
           WHEN lnUnidades = 8 THEN 'OCHO ' || lcCadena
           WHEN lnUnidades = 9 THEN 'NUEVE ' || lcCadena
           ELSE lcCadena
          END INTO lcCadena;
          /* UNIDADES */
    -- Analizo las decenas
    SELECT
    CASE /* DECENAS */
      WHEN lnDecenas = 1 THEN
        CASE lnUnidades
          WHEN 0 THEN 'DIEZ '
          WHEN 1 THEN 'ONCE '
          WHEN 2 THEN 'DOCE '
          WHEN 3 THEN 'TRECE '
          WHEN 4 THEN 'CATORCE '
          WHEN 5 THEN 'QUINCE '
          ELSE 'DIECI' || lcCadena
        END
      WHEN lnDecenas = 2 AND lnUnidades = 0 THEN 'VEINTE ' || lcCadena
      WHEN lnDecenas = 2 AND lnUnidades <> 0 THEN 'VEINTI' || lcCadena
      WHEN lnDecenas = 3 AND lnUnidades = 0 THEN 'TREINTA ' || lcCadena
      WHEN lnDecenas = 3 AND lnUnidades <> 0 THEN 'TREINTA Y ' || lcCadena
      WHEN lnDecenas = 4 AND lnUnidades = 0 THEN 'CUARENTA ' || lcCadena
      WHEN lnDecenas = 4 AND lnUnidades <> 0 THEN 'CUARENTA Y ' || lcCadena
      WHEN lnDecenas = 5 AND lnUnidades = 0 THEN 'CINCUENTA ' || lcCadena
      WHEN lnDecenas = 5 AND lnUnidades <> 0 THEN 'CINCUENTA Y ' || lcCadena
      WHEN lnDecenas = 6 AND lnUnidades = 0 THEN 'SESENTA ' || lcCadena
      WHEN lnDecenas = 6 AND lnUnidades <> 0 THEN 'SESENTA Y ' || lcCadena
      WHEN lnDecenas = 7 AND lnUnidades = 0 THEN 'SETENTA ' || lcCadena
      WHEN lnDecenas = 7 AND lnUnidades <> 0 THEN 'SETENTA Y ' || lcCadena
      WHEN lnDecenas = 8 AND lnUnidades = 0 THEN 'OCHENTA ' || lcCadena
      WHEN lnDecenas = 8 AND lnUnidades <> 0 THEN 'OCHENTA Y ' || lcCadena
      WHEN lnDecenas = 9 AND lnUnidades = 0 THEN 'NOVENTA ' || lcCadena
      WHEN lnDecenas = 9 AND lnUnidades <> 0 THEN 'NOVENTA Y ' || lcCadena
      ELSE lcCadena
    END INTO lcCadena; /* DECENAS */
    -- Analizo las centenas
    SELECT
    CASE /* CENTENAS */
      WHEN lnCentenas = 1 AND lnUnidades = 0 AND lnDecenas = 0 THEN 'CIEN ' || lcCadena
      WHEN lnCentenas = 1 AND NOT(lnUnidades = 0 AND lnDecenas = 0) THEN 'CIENTO ' || lcCadena
      WHEN lnCentenas = 2 THEN 'DOSCIENTOS ' || lcCadena
      WHEN lnCentenas = 3 THEN 'TRESCIENTOS ' || lcCadena
      WHEN lnCentenas = 4 THEN 'CUATROCIENTOS ' || lcCadena
      WHEN lnCentenas = 5 THEN 'QUINIENTOS ' || lcCadena
      WHEN lnCentenas = 6 THEN 'SEISCIENTOS ' || lcCadena
      WHEN lnCentenas = 7 THEN 'SETECIENTOS ' || lcCadena
      WHEN lnCentenas = 8 THEN 'OCHOCIENTOS ' || lcCadena
      WHEN lnCentenas = 9 THEN 'NOVECIENTOS ' || lcCadena
      ELSE lcCadena
    END INTO lcCadena;/* CENTENAS */
    -- Analizo la terna
    SELECT
    CASE /* TERNA */
      WHEN lnTerna = 1 THEN lcCadena
      WHEN lnTerna = 2 AND (lnUnidades + lnDecenas + lnCentenas <> 0) THEN lcCadena || ' MIL '
      WHEN lnTerna = 3 AND (lnUnidades + lnDecenas + lnCentenas <> 0) AND
        lnUnidades = 1 AND lnDecenas = 0 AND lnCentenas = 0 THEN lcCadena || ' MILLON '
      WHEN lnTerna = 3 AND (lnUnidades + lnDecenas + lnCentenas <> 0) AND
        NOT (lnUnidades = 1 AND lnDecenas = 0 AND lnCentenas = 0) THEN lcCadena || ' MILLONES '
      WHEN lnTerna = 4 AND (lnUnidades + lnDecenas + lnCentenas <> 0) THEN lcCadena || ' MIL MILLONES '
      ELSE ''
    END INTO lcCadena;/* TERNA */

    --Retornamos los Valores Obtenidos
    lcRetorno = lcCadena  || lcRetorno;
    lnTerna = lnTerna + 1;
    END LOOP;
  END IF;
  IF lnTerna = 1 THEN
    lcRetorno := 'CERO';
  END IF;
  lcRetorno := RTRIM(lcRetorno) || ' CON ' || LPAD(LTRIM(cast(lnFraccion as varchar))::text, 2,'0') || '/100 SOLES';
RETURN lcRetorno;
END;
$body$
LANGUAGE 'plpgsql' VOLATILE CALLED ON NULL INPUT SECURITY INVOKER;
/**/


/*UPDATE payments set "payIsCanceled"=1 where "payId"=12*/

/*MODIFICACIPOINES EN TABLAS 13/05/2022*/
ALTER TABLE appointment_temp 
    ALTER COLUMN "apptmNameClient" TYPE  VARCHAR(500);

ALTER TABLE appointment
    ALTER COLUMN "apptmNameClient" TYPE  VARCHAR(500);

    
/*Añadir FOREIGN KEY in payment_details*/
ALTER TABLE payment_details ADD FOREIGN KEY ("spId") REFERENCES services_provided("spId");

ALTER TABLE bussines ALTER COLUMN "bussState" set DEFAULT 1 
UPDATE bussines SET "bussState"=1 where "bussState" is null;


ALTER TABLE teller ALTER COLUMN "tellState" set DEFAULT 2 

/*Modificaciones de appointment-temp*/



create table audits(
    "adtId" serial PRIMARY KEY,

    "adtUserAgent" varchar(300),
    "adtMethod" varchar(30),
    "adtURL" varchar(300),
    "adtIP" VARCHAR(100),

    "adtNameTable" varchar(100),
    
    "adtKeyWord" VARCHAR(50), 
    "adtSubject" varchar(300),

    "adtDataOld" text,
    "adtDataNew" text,

    "adtSystem" varchar(50),
    "adtHostname" varchar(100),
    
    "created_by" BIGINT,
    "updated_by" BIGINT,
    "updated_at" timestamp,
    "created_at" timestamp
);

/*20/06/2022*/
UPDATE correlative_proof set "cpfSerie"='R001' WHERE "hqId"=1 and "cpfKindDoc"=1; 
UPDATE period_payments SET "ppayName"=UPPER("ppayName");
UPDATE period_payments set "ppayName"='NINGUNO' WHERE "ppayId"=14;

insert into period_payments("ppayName") values('OTRO')
/*Ejectuar trigger in serviced_provided*/





/* colours 07/07/2022*/

ALTER TABLE services_provided ADD COLUMN "spCommentColourText" VARCHAR(20) DEFAULT '#000000';
ALTER TABLE payments ADD COLUMN "payReceiptHonorarySN" VARCHAR(50);

/*select * from services_provided*/



/*Modificaciones al 25/07/2022*/

CREATE FUNCTION tf_a_d_services_provided() RETURNS TRIGGER
LANGUAGE PLPGSQL AS
    $$ 
DECLARE
    _dbpCost decimal(12,2);
    _dbpPaid decimal(12,2);
BEGIN
    SELECT COALESCE(SUM("spCost"),0) into _dbpCost FROM services_provided where "dbpId"=old."dbpId";
    SELECT COALESCE(SUM("spPaid"),0) into _dbpPaid FROM services_provided where "dbpId"=old."dbpId";
    
    UPDATE d_bussines_periods SET "dbpCost"=_dbpCost, "dbpPaid"=_dbpPaid, "dbpDebt"=_dbpCost-_dbpPaid where "dbpId"=old."dbpId";

RETURN OLD;

END;
$$

 CREATE TRIGGER t_a_d_services_provided AFTER DELETE ON services_provided FOR EACH ROW EXECUTE PROCEDURE tf_a_d_services_provided();
/*FALTA EN PRODUCCION**/

/*Cambios 28 de Julio 2022*/

ALTER TABLE videos ADD COLUMN "vidChannelTitle" VARCHAR(200);
ALTER TABLE videos ADD COLUMN "vidDescription" VARCHAR(500);
ALTER TABLE videos ADD COLUMN "vidImgLinkDefault" VARCHAR(200);
ALTER TABLE videos ADD COLUMN "vidImgLinkMedium" VARCHAR(200);
ALTER TABLE videos ADD COLUMN "vidImgLinkHigh" VARCHAR(200);


ALTER TABLE category   ADD COLUMN "catState" int DEFAULT 1; 

ALTER TABLE headquarter ADD COLUMN "hqTel" VARCHAR(200);

ALTER TABLE headquarter ADD COLUMN "hqEmail" VARCHAR(200);

/*Cambios de DB*/

/*
select "apptmDateTimePrint",
"apptmDateStartAttention",
"apptmDateFinishAttention",
 extract(EPOCH from "apptmDateStartAttention"::timestamp - "apptmDateTimePrint"::timestamp) as "apptmEpochInWaiting", 
 extract(EPOCH from "apptmDateFinishAttention"::timestamp - "apptmDateStartAttention"::timestamp) as "apptmEpochInAtention"
  from appointment*/

/*30 / 08 /2022*/

ALTER TABLE appointment_temp ADD COLUMN "apptmEpochInWaiting" decimal(20,8);
ALTER TABLE appointment_temp ADD COLUMN "apptmEpochInAtention" decimal(20,8);

ALTER TABLE appointment ADD COLUMN "apptmEpochInWaiting" decimal(20,8);
ALTER TABLE appointment ADD COLUMN "apptmEpochInAtention" decimal(20,8);





CREATE FUNCTION tf_b_u_appointment_temp()
   RETURNS TRIGGER
   LANGUAGE PLPGSQL
AS $$
DECLARE
    _catNameLong varchar;
BEGIN

    IF NEW."apptmDateStartAttention" IS NOT NULL THEN 
        NEW."apptmEpochInWaiting":= extract(EPOCH from NEW."apptmDateStartAttention"::timestamp - NEW."apptmDateTimePrint"::timestamp) as "apptmEpochInWaiting";
    END IF;


    IF NEW."apptmDateFinishAttention" IS NOT NULL THEN 
        NEW."apptmEpochInAtention":=extract(EPOCH from NEW."apptmDateFinishAttention"::timestamp - NEW."apptmDateStartAttention"::timestamp) as "apptmEpochInAtention";
    END IF;




RETURN NEW;
END;
$$

CREATE TRIGGER t_b_u_appointment_temp BEFORE
UPDATE
    ON appointment_temp FOR EACH ROW EXECUTE PROCEDURE tf_b_u_appointment_temp();




/*
drop TRIGGER t_b_u_appointment_temp on appointment_temp;
drop FUNCTION tf_b_u_appointment_temp;*/

update appointment_temp set "apptmDateFinishAttention"="apptmDateStartAttention" where "apptmDateFinishAttention" is null
update appointment set"apptmDateFinishAttention"="apptmDateStartAttention"  where "apptmDateFinishAttention" is null

update appointment_temp set "apptmEpochInAtention"=extract(EPOCH from "apptmDateFinishAttention"::timestamp - "apptmDateStartAttention"::timestamp);
UPDATE appointment_temp set "apptmEpochInWaiting"= extract(EPOCH from "apptmDateStartAttention"::timestamp - "apptmDateTimePrint"::timestamp) ;


update appointment set "apptmEpochInAtention"=extract(EPOCH from "apptmDateFinishAttention"::timestamp - "apptmDateStartAttention"::timestamp) ;
UPDATE appointment set "apptmEpochInWaiting"= extract(EPOCH from "apptmDateStartAttention"::timestamp - "apptmDateTimePrint"::timestamp) ;




/*modificaciones en servidor 27/09/2022*/
ALTER TABLE appointment_temp ADD COLUMN "apptmTokenToQualify" VARCHAR(200);

ALTER TABLE appointment ADD COLUMN "apptmTokenToQualify" varchar(200);

/*modificado 15/10/2022*/
ALTER TABLE bussines ADD COLUMN "bussComment" varchar(500);
ALTER TABLE bussines ADD COLUMN "bussCommentColor" varchar(20);
ALTER TABLE d_bussines_periods ADD FOREIGN KEY ("bussId") REFERENCES bussines("bussId");
ALTER TABLE  bussines ALTER COLUMN "bussState" SET DEFAULT 1;
/*ALTER TABLE  bussines ALTER COLUMN "bussState" SET DEFAULT 1;
ALTER TABLE  bussines ALTER COLUMN "buss" SET DEFAULT 1;*/


/*Cambios incrustados 26/10/2022*/


CREATE FUNCTION tf_b_i_bussines() 
    RETURNS TRIGGER 
    LANGUAGE PLPGSQL 
AS $$ 
DECLARE 
    _bussFileNumber integer;
    _bussName varchar(300);
BEGIN
    select
        "bussFileNumber", "bussName" INTO _bussFileNumber, _bussName
    FROM
        bussines
    WHERE
        "bussFileNumber" = NEW."bussFileNumber" AND "bussState" IN ('1'/*Activo */, '2'/*Suspendido*/);

    if _bussFileNumber is not null AND NEW."bussState"<>'3' THEN
        RAISE EXCEPTION '<msg>Lo sentimos, este número de archivador esta en uso por un otro cliente.<msg>';
    END IF;

RETURN NEW;

END;

$$

CREATE TRIGGER t_b_i_bussines BEFORE
INSERT
    ON bussines FOR EACH ROW EXECUTE PROCEDURE tf_b_i_bussines();

/*
drop TRIGGER t_b_i_bussines on bussines;
drop FUNCTION tf_b_i_bussines;*/

CREATE FUNCTION tf_b_u_bussines()
   RETURNS TRIGGER
   LANGUAGE PLPGSQL
AS $$
DECLARE
    _bussFileNumber integer;
    _bussName varchar(300);

BEGIN


  select
        "bussFileNumber", "bussName" INTO _bussFileNumber, _bussName
    FROM
        bussines
    WHERE
        "bussRUC"<> NEW."bussRUC" AND "bussFileNumber" = NEW."bussFileNumber" AND  "bussState" IN ('1'/*Activo */, '2'/*Suspendido*/) ;

    if _bussFileNumber is not null AND NEW."bussState"<>'3' THEN
        RAISE EXCEPTION '<msg>Lo sentimos, este número de archivador esta en uso por un otro cliente (%).<msg>',_bussName;
    END IF;

RETURN NEW;
END;
$$

 CREATE TRIGGER t_b_u_bussines BEFORE
UPDATE
    ON bussines FOR EACH ROW EXECUTE PROCEDURE tf_b_u_bussines();

    /*
drop TRIGGER t_b_u_bussines on bussines;
drop FUNCTION tf_b_u_bussines;*/



    /*ALTER TABLE bussines DROP CONSTRAINT bussines_bussFileNumber_key;*/


    /*SELECT conname
FROM pg_constraint
WHERE conrelid =
    (SELECT oid 
    FROM pg_class
    WHERE relname LIKE 'd_busines');*/

    /*SELECT *
FROM information_schema.constraint_table_usage
WHERE table_name = 'customers'*/

/*ALTER TABLE customers DROP CONSTRAINT customers_dni_key;

*/

/*19/11/2022*/
ALTER TABLE services ADD COLUMN "svNumberOfOrder" int;
