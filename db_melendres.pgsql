
/*Start*/

CREATE TABLE "category"(
    "catId" SERIAL PRIMARY KEY,
    "catCode" varchar(10),
    "catName" VARCHAR,
    "catNameLong" varchar,
    "catDescription" varchar,

    "catAuth" varchar(10) DEFAULT 'Ninguno', /*DNI, RUC*/
    "catIdParent" INTEGER,

    "updated_at" timestamp,
    "created_at" timestamp,
    FOREIGN KEY ("catIdParent") REFERENCES category("catId")
);

CREATE TABLE "teller"(
    "tellId" SERIAL PRIMARY KEY,

    "tellCode" varchar(10),
    "tellName" varchar(50)
);



CREATE TABLE "appointmentTemp"(
    /*Para sacar cita*/
    "apptmId" SERIAL PRIMARY KEY,
    "apptmTicketCode" varchar(10) /*catCode+'01' */,
    "apptmDateTImePrint" timestamp,
    "apptmSendFrom" varchar(10), /*web, totem, whatsApp*/

    /*datos del cliente*/
    "apptKindClient" varchar(10), /*P=Persona N=Negocio*/
    "perId" int,
    "bussId" int,
    /*EL nro de documento y nombre del cliente viaja a esta tabla para un acceso rapido*/
    "apptmNumberDOcClient" VARCHAR(12),/*RUC, DNI, ETC*/
    "apptmNameClient" varchar(50),

    /*id de ventanilla y id de categoria*/
    "tellId" integer,

    "catId" integer,

    /*Transfer*/
    apptmTransfer int,
    apptmTel varchar(12),

    /*Posiblemente en la siguiente tabla original*/
    tellNameLong varchar,
    catNameLOng varchar,

    /*atencion en ventanilla*/
    apptmState varchar(10) /*En espera, Atendido, Cancelado*/
);

CREATE TABLE appointment(

);

CREATE TABLE "person"(
    "perId" SERIAL PRIMARY KEY,
    "perKindDoc" VARCHAR(1),
    "perNumberDoc" varchar(20),
    "perName" VARCHAR,
    "perAddress" VARCHAR,

    "perTel" varchar(10),
    "perEmail" varchar(50),

    "updated_at" timestamp,
    "created_at" timestamp
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








insert into test1('CampoUnO', campoDos) values('casa', 'algo mas')
create table test1(
    "CampoUnO" varchar(10),
    campoDos varchar(23)
)

