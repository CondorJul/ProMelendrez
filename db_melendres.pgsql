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
insert into "person" ("perKindDoc",  "perNumberDoc",  "perName"   )
            values (1,'admin', 'admin' )

/*OTRA TABLA GENERAL ES LA TABLA USERS*/

/*Start*/
CREATE TABLE "headquarters"(
    "hqId" SERIAL PRIMARY KEY,
    "hqName" varchar(100),
    "hqRuc" varchar(12),
    "hqAddress"  varchar(100)
);
insert into "headquarters"("hqName") values('Pasco');


CREATE TABLE "category"(
    "catId" SERIAL PRIMARY KEY,
    "catCode" varchar(10),
    "catName" VARCHAR,
    "catNameLong" varchar,
    "catDescription" varchar,
    "catTelReq" int DEFAULT 2, /*1=SI, 2=NO*/
    "catLinkBus" int DEFAULT 2,  /*1=si, 2="no" */
    "catAuth" int DEFAULT 1, /*1=NINGUNO, 2=DNI, 3=RUC, 4=CUALQUIERA*/
    /*Categoria Padre*/
    "catIdParent" INTEGER,

    /*Id foraneo de Sede*/
    "hqId" INTEGER,

    "updated_at" timestamp,
    "created_at" timestamp,

    /*referencias*/
    FOREIGN KEY ("catIdParent") REFERENCES category("catId"),
    FOREIGN KEY ("hqId") REFERENCES headquarters("hqId")
);



CREATE TABLE "teller"(
    "tellId" SERIAL PRIMARY KEY,

    /*datos generales*/
    "tellCode" varchar(10),
    "tellName" varchar(50),

    /*datos de control*/
    "tellMaxInWait" int,
    "tellState" int DEFAULT 1, /*1=Activo , 2=Inactivo*/


    /*Id foraneo de Sede*/
    "hqId" INTEGER,
    /*Id de usuario*/
    "userId" bigint,

    /*variables de tiempo*/
    "updated_at" timestamp,
    "created_at" timestamp,

    FOREIGN KEY ("userId") REFERENCES users(id),

    FOREIGN KEY ("hqId") REFERENCES headquarters("hqId")

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
    "apptmTicketCode" varchar(10) /*catCode+'01' */,
    "apptmDateTimePrint" timestamp DEFAULT now() ,
    "apptmSendFrom" varchar(10), /*web, totem, whatsApp*/

    /*datos del cliente*/
    "apptKindClient" varchar(5), /*P=Persona N=Negocio*/
    "perId" int,
    "bussId" int,
    /*EL nro de documento y nombre del cliente viaja a esta tabla para un acceso rapido*/
    "apptmNumberDocClient" VARCHAR(12),/*RUC, DNI, ETC*/
    "apptmNameClient" varchar(50),

    /*id de ventanilla y id de categoria*/
    "tellId" integer,

    "catId" integer,
    "apptmNro" int,
    /*Transfer*/
    apptmTransfer int,
    apptmTel varchar(12),

    /*Posiblemente en la siguiente tabla original*/
    tellNameLong varchar,
    catNameLOng varchar,

    /*atencion en ventanilla*/
    apptmState varchar(10), /*En espera, Atendido, Cancelado*/

    "updated_at" timestamp,
    "created_at" timestamp,

    FOREIGN KEY ("catId") REFERENCES category("catId"),
    FOREIGN KEY ("tellId") REFERENCES teller("tellId")

);


ALTER TABLE appointment_temp ALTER COLUMN created_at SET DEFAULT now();


insert into appointment_temp("catId", "apptmNumberDocClient") values(42, '70224418');

CREATE TABLE appointment(

);

CREATE TABLE "bussines"(
    "bussId" SERIAL PRIMARY KEY,
    "bussKind" varchar(10), /*juridica y natural con negocio*/
    "bussName" VARCHAR,
    "bussRUC" VARCHAR(12),
    "bussAddress" varchar(200),

    /*Datos empresa o negocio*/
    "bussSunatUser" varchar(15),
    "bussSunatPass" varchar(15),

    "bussCodeSend" varchar(20),
    "bussCodeRNP" varchar(20),
    /*Fin*/


    /*Fecha de ingreso*/
    "bussDateMembership" date,

    /*e inicio de actividades*/
    "bussDateStartedAct" date,

    "bussState" char(5), /*Activo, suspendido, renicio */
    "bussStateDate" timestamp,

    /*Archivadores*/
    "bussFileKind" char(2), /*Archivador y Folder*/
    "bussFileNumber" integer,
    "bussRegime" char(2), /*Especial y MYPE triburatio y regimen general */
    "bussKindBookAcc" char(2), /*TIpo de libro = Electronico y computarizado, */

    "bussObservation" text,

    "perId" INTEGER,
    FOREIGN KEY ("perId") REFERENCES person("perId"),

    "bussTel" varchar(10),
    "bussEmail" varchar(50),

    "updated_at" timestamp,
    "created_at" timestamp
);

create table controlExercise(


)


/*Tablas de practica*/
insert into test1('CampoUnO', campoDos) values('casa', 'algo mas')
create table test1(
    "CampoUnO" varchar(10),
    campoDos varchar(23)
)



/*TRIGGERS*/
CREATE FUNCTION tf_b_i_category()
   RETURNS TRIGGER
   LANGUAGE PLPGSQL
AS $$
DECLARE
    _catNameLong varchar;
BEGIN

    select "catNameLong" INTO _catNameLong FROM category WHERE "catId"=NEW."catIdParent";
    IF _catNameLong is not null THEN
        NEW."catNameLong":=CONCAT(_catNameLong,'/',NEW."catName");
    END IF;

    IF _catNameLong is null THEN
        NEW."catNameLong":=CONCAT('/',NEW."catName");
    END IF;

RETURN NEW;
END;
$$
/*

drop TRIGGER t_b_i_category on category;
drop FUNCTION tf_b_i_category;*/



CREATE TRIGGER t_b_i_category
   BEFORE INSERT
   ON category
   FOR EACH ROW
   EXECUTE PROCEDURE tf_b_i_category();



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



CREATE TRIGGER t_b_u_category
   BEFORE UPDATE
   ON category
   FOR EACH ROW
   EXECUTE PROCEDURE tf_b_u_category();


/*
drop TRIGGER t_b_U_category on category;
drop FUNCTION tf_b_U_category;*/



/*TRIGGERS*/
CREATE FUNCTION tf_b_i_appointment_temp()
   RETURNS TRIGGER
   LANGUAGE PLPGSQL
AS $$
DECLARE
    _catNameLong varchar;
    _o decimal;
    _tellId INTEGER;
    _maxApptmNro int;

BEGIN

    IF NEW."tellId" is null THEN
        /*seleccioamos aleatoriamente un ventanilla*/
        select random() AS o, teller."tellId" INTO _o, _tellId from teller
            INNER JOIN d_category_teller
                on teller."tellId"=d_category_teller."tellId"
            INNER JOIN category
                on d_category_teller."catId"=category."catId"
                and (select "catNameLong" from category where "catId"=NEW."catId") like concat("catNameLong",'%')  ORDER BY o ASC;

        /*Verificamos que exi*/
        NEW."tellId":=_tellId;
    END IF;
    /*Generamos el codigo */
    select COALESCE(max("apptmNro"), 0) into _maxApptmNro from appointment_temp where "catId"=NEW."catId";
    NEW."apptmNro"=_maxApptmNro+1;

RETURN NEW;
END;
$$


/*
drop TRIGGER t_b_i_appointment_temp on appointment_temp;
drop FUNCTION tf_b_i_appointment_temp;*/



CREATE TRIGGER t_b_i_appointment_temp
   BEFORE INSERT
   ON appointment_temp
   FOR EACH ROW
   EXECUTE PROCEDURE tf_b_i_appointment_temp();

/*
select * from teller

select * from category
    inner join d_category_teller
        on category."catId"=d_category_teller."catId"
    inner join teller
        on d_category_teller."tellId" =teller."tellId"

teller on teller.tellId=d_category_teller

select * from category
    where  (select "catNameLong" from category where "catId"=42) like concat("catNameLong",'%')

select random() AS o, teller."tellId" from teller
    INNER JOIN d_category_teller
        on teller."tellId"=d_category_teller."tellId"
    INNER JOIN category
        on d_category_teller."catId"=category."catId"
        and (select "catNameLong" from category where "catId"=42) like concat("catNameLong",'%')  ORDER BY o ASC*/

CREATE TABLE videos(
    "vidId" SERIAL PRIMARY KEY,
    "vidName" VARCHAR(200),
    "vidLink" VARCHAR(500),
    "vidState" char(5), /*Habilitado, Deshabilitado*/

    "updated_at" timestamp,
    "created_at" timestamp
);

CREATE TABLE cards(
    "cardId" SERIAL PRIMARY KEY,
    "cardName" VARCHAR(200),
    "cardPhrases" VARCHAR(500),
    "cardState" char(5), /*Habilitado, Deshabilitado*/

    "updated_at" timestamp,
    "created_at" timestamp
);
