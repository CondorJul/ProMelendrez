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

    "catAuth" varchar(10) DEFAULT 'Ninguno', /*DNI, RUC*/
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
    "apptmDateTimePrint" timestamp,
    "apptmSendFrom" varchar(10), /*web, totem, whatsApp*/

    /*datos del cliente*/
    "apptKindClient" varchar(10), /*P=Persona N=Negocio*/
    "perId" int,
    "bussId" int,
    /*EL nro de documento y nombre del cliente viaja a esta tabla para un acceso rapido*/
    "apptmNumberDocClient" VARCHAR(12),/*RUC, DNI, ETC*/
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

