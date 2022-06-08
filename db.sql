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

drop table appointment CREATE TABLE "bussines"(
	"bussId" SERIAL PRIMARY KEY,
	"bussKind" varchar(10),
	/*juridica y natural con negocio*/
	"bussName" VARCHAR,
	"bussRUC" VARCHAR(12),
	"bussAddress" varchar(200),
	/*Datos empresa o negocio*/
	"bussSunatUser" varchar(15),
	"bussSunatPass" varchar(15),
	"bussCodeSend" varchar(20),
	"bussCodeRNP" varchar(20),
	/*Bus AFP*/
	"bussAfpUser" varchar(20),
	"bussAfpPass" varchar(20),
	/*Fin*/
	/*Fecha de ingreso*/
	"bussDateMembership" date,
	/*e inicio de actividades*/
	"bussDateStartedAct" date,
	"bussState" char(5),
	/*Activo, suspendido, renicio */
	"bussStateDate" timestamp,
	/*Archivadores*/
	"bussFileKind" char(2),
	/*Archivador y Folder*/
	"bussFileNumber" integer,
	"bussRegime" char(2),
	/*Especial y MYPE triburatio y regimen general */
	"bussKindBookAcc" char(2),
	/*TIpo de libro = Electronico y computarizado, */
	"bussObservation" text,
	"tellId" integer,
	"perId" INTEGER,
	FOREIGN KEY ("perId") REFERENCES person("perId"),
	"bussTel" varchar(10),
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

IF _catNameLong is not null THEN NEW."catNameLong": = CONCAT(_catNameLong, '/', NEW."catName");

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

IF _tellId IS NULL THEN RAISE EXCEPTION '<msg>Lo sentimos, en este momento no disponemos de ventanillas para este servicio.<msg>';

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

CREATE FUNCTION tf_b_d_appointment_temp() RETURNS TRIGGER
LANGUAGE PLPGSQL AS
	$ $ DECLARE
"apptmSendFrom", "apptKindClient", "perId", "bussId"
, "apptmNumberDocClient", "apptmNameClient",
/*Sede*/
"hqId", /*id de ventanilla y id de categoria*/
"tellId", "catId", "catCode", "apptmNro",
/*Transfer*/
"apptmTransfer", "apptmTel", "apptmEmail", "apptmComment"
, /*Posiblemente en la siguiente tabla original*/
"tellNameLong",
/*atencion en ventanilla*/
"apptmState",
/*En espera=1, En atención=2, Atendido=3, 4=no atendido 5=cancelado*/
"apptmNroCalls", "apptmDateStartAttention", "apptmDateFinishAttention"
, /*Comment client*/
"apptmScoreClient", "apptmCommentClient", "apptmScoreDateClient"
, "apptmCommentDateClient", "updated_at", "created_at"
, /*fields with teller */
"tellName", "tellCode",
/*fields with category */
"catNameLong",
/*fields with user*/
"userId", "perName") VALUES(OLD."apptmId", OLD."apptmTicketCode"
, OLD."apptmDateTimePrint", OLD."apptmSendFrom", OLD
."apptKindClient", OLD."perId", OLD."bussId", OLD.
"apptmNumberDocClient", OLD."apptmNameClient",
/*Sede*/
OLD."hqId",
/*id de ventanilla y id de categoria*/
OLD."tellId", OLD."catId", OLD."catCode", OLD."apptmNro"
, /*Transfer*/
OLD."apptmTransfer", OLD."apptmTel", OLD."apptmEmail"
, OLD."apptmComment",
/*Posiblemente en la siguiente tabla original*/
OLD."tellNameLong",
/*atencion en ventanilla*/
OLD."apptmState",
/*En espera=1, En atención=2, Atendido=3, 4=no atendido 5=cancelado*/
OLD."apptmNroCalls", OLD."apptmDateStartAttention"
, OLD."apptmDateFinishAttention",
/*Comment client*/
OLD."apptmScoreClient", OLD."apptmCommentClient",
OLD."apptmScoreDateClient", OLD."apptmCommentDateClient"
, OLD."updated_at", OLD."created_at",
/*fields with teller */
_tellName, _tellCode,
/*fields with category */
_catNameLong,
/*fields with user*/
_userId, _perName) ;

RETURN OLD;

END;

$ $ CREATE TRIGGER t_b_d_appointment_temp BEFORE DELETE ON appointment_temp FOR EACH ROW EXECUTE PROCEDURE tf_b_d_appointment_temp();

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
	"created_at" timestamp
);
create table model_of_services(
	"msId" serial primary key,
	"msName" varchar(150),
	"msTimeInterval" varchar(50),
	"updated_at" timestamp,
	"created_at" timestamp
);

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
    "created_at" timestamp
);




drop table payments

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
	"payClientName" varchar(80),
	"payClientAddress" varchar(40),
	"payClientTel" varchar(12),
	"payClientEmail" varchar(40),
	"payClientRucOrDni" varchar(12),
	/*Campos para clientes no registrados*/
	"paySubTotal" decimal(12, 2) DEFAULT 0.0,
	"payDiscount" decimal(12, 2) DEFAULT 0.0,
	"paySalesTax" decimal(12, 2) DEFAULT 0.0,
	"payTotal" decimal(12, 2) DEFAULT 0.0,
	"payTotalInWords" VARCHAR(100),
	"created_by" BIGINT,
	"updated_by" BIGINT,
	"updated_at" timestamp,
	"created_at" timestamp
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
	"created_at" timestamp
) 
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
	"created_by" BIGINT,
	"updated_by" BIGINT,
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
    ('Diciembre');

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




CREATE FUNCTION tf_b_i_payment_details() RETURNS TRIGGER
LANGUAGE PLPGSQL AS
	$$ 
DECLARE
	
	_spCost decimal(12, 2);
	_spDebt decimal(12, 2);
	_spPaid decimal(12, 2); 

	_sumPaidFromPD decimal(12,2);

BEGIN
/*Obtenemos algunos datos de categoria*/


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

	_subTotal decimal(12,2);

BEGIN
/*Obtenemos algunos datos de categoria*/



/*Si es un servicio de un cliente X, verificamos que este correcto*/
IF COALESCE(NEW."spId", -1)>0 THEN
	SELECT "spCost", "spDebt", "spPaid" INTO  _spCost, _spDebt, _spPaid FROM services_provided where "spId"=NEW."spId";
	SELECT COALESCE(SUM("pdsAmount"),0) into _sumPaidFromPD FROM payment_details where "spId"=NEW."spId";
	IF _spCost < _sumPaidFromPD THEN 
		RAISE EXCEPTION '<msg>El pago por el servicio no puede superar el costo establecido.<msg>';

	END IF;
	UPDATE services_provided set  "spPaid" =_sumPaidFromPD, "spDebt"= _spCost-_sumPaidFromPD where "spId"=NEW."spId";

END IF;

SELECT sum("pdsAmount") into _subTotal from payment_details where "payId"=NEW."payId";

UPDATE payments set "paySubTotal"=_subTotal where "payId"=NEW."payId";

RETURN NEW;

END;

$$

/*
drop TRIGGER t_a_i_payment_details on payment_details;
drop FUNCTION tf_a_i_payment_details;*/
CREATE TRIGGER t_a_i_payment_details AFTER
INSERT
	ON payment_details FOR EACH ROW EXECUTE PROCEDURE tf_a_i_payment_details();



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
BEGIN
/*Analizamos que no sea una boleta facturada*/
IF NEW."payState" <> 3/*Facturado*/ AND  OLD."payState"=3/*Facturado*/ THEN
	RAISE EXCEPTION '<msg>Lo sentimos, este ticket no esta disponible para modificar.<msg>';
END IF;

IF NEW."payKindDoc"=1/*Tickets*/ THEN
	NEW."payTotal":=NEW."paySubTotal";
END IF;

IF  NEW."payState"=3 AND OLD."payState"<>3 THEN
	SELECT "cpfSerie", "cpfNumber" into _serie, _number from correlative_proof where "hqId"=new."hqId" and "cpfKindDoc"=new."payKindDoc";
    UPDATE correlative_proof SET "cpfNumber"=(_number+1) where "hqId"=new."hqId" and "cpfKindDoc"=new."payKindDoc";
	NEW."paySerie":=_serie;
	NEW."payNumber":=_number;
	SELECT fu_numero_letras(NEW."payTotal") into _payTotalInWords;
	NEW."payTotalInWords":=_payTotalInWords;
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
	
	NEW."spName":=CONCAT(COALESCE(_periodName, '-'),' / ',COALESCE(_periodPaymentName, '-'),' / ',COALESCE(_serviceName,'-'));

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
	
	NEW."spName":=CONCAT(COALESCE(_periodName, '-'),' / ',COALESCE(_periodPaymentName, '-'),' / ',COALESCE(_serviceName,'-'));


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
  lcRetorno := RTRIM(lcRetorno) || ' CON ' || LTRIM(cast(lnFraccion as varchar)) || '/100 SOLES';
RETURN lcRetorno;
END;
$body$
LANGUAGE 'plpgsql' VOLATILE CALLED ON NULL INPUT SECURITY INVOKER;
 
