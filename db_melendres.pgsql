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
    "apptKindClient"  int, /*2=Persona 1=Negocio*/
    "perId" int,
    "bussId" int,
    /*EL nro de documento y nombre del cliente viaja a esta tabla para un acceso rapido*/
    "apptmNumberDocClient" VARCHAR(12),/*RUC, DNI, ETC*/
    "apptmNameClient" varchar(50),

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
    "apptmState" int default 1, /*En espera=1, En atención=2, Atendido=3, 4=no atendido 5=cancelado*/
    "apptmNroCalls" int default 0,
    "apptmDateStartAttention" timestamp,
    "apptmDateFinishAttention"  timestamp,

     "updated_at" timestamp,
    "created_at" timestamp,
    
    FOREIGN KEY ("catId") REFERENCES category("catId"),
    FOREIGN KEY ("tellId") REFERENCES teller("tellId")

);


ALTER TABLE appointment_temp ALTER COLUMN created_at SET DEFAULT now();


insert into appointment_temp("catId", "apptmNumberDocClient") values(42, '70224418')



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
    
    "tellId" integer,
    "perId" INTEGER,
    FOREIGN KEY ("perId") REFERENCES person("perId"),

    "bussTel" varchar(10),
    "bussEmail" varchar(50),

    "updated_at" timestamp,
    "created_at" timestamp
);

insert into bussines( "bussName", "bussRUC") values('RIcardo Solis','10702244191')

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
    /**/
    _catNameLong varchar;
    _o decimal;
    _tellId INTEGER;
    _tellState int;
    _maxApptmNro int;
    _nroCallPending int;

    /*Categoria*/
    _catId integer;
    _catCode varchar(10);
    _catLinkBus int;
    
BEGIN
    /*Obtenemos algunos datos de categoria*/
    select "catCode", "catLinkBus" INTO _catCode, _catLinkBus from category where "catId"=NEW."catId";
    NEW."catCode"=_catCode;
    /*si esta vinculado a busi*/
    
    
    IF NEW."tellId" is null THEN
        /*seleccioamos aleatoriamente un ventanilla*/
        /*select random() AS o, teller."tellId" INTO _o, _tellId from teller 
            INNER JOIN d_category_teller 
                on teller."tellId"=d_category_teller."tellId"
            INNER JOIN category
                on d_category_teller."catId"=category."catId" 
                and (select "catNameLong" from category where "catId"=NEW."catId") like concat("catNameLong",'%') and teller."tellState"=1  ORDER BY o ASC;*/
        IF _catLinkBus=1 THEN 
            select "tellState", "tellId" into _tellState, _tellId   from teller where "tellId"=(select "tellId" from bussines where "bussId"=NEW."bussId");   
            IF _tellState=1 THEN
                NEW."tellId"=_tellId;
            END IF;
        END IF;

        IF NEW."tellId" is null  THEN
            SELECT o, f."tellId", COALESCE("nroCallPending" ,0) as nroCallPending into _o, _tellId, _nroCallPending from (select random() AS o, teller."tellId"  from teller 
                INNER JOIN d_category_teller 
                    on teller."tellId"=d_category_teller."tellId"
                INNER JOIN category
                    on d_category_teller."catId"=category."catId" where
                    (select "catNameLong" from category where "catId"=new."catId") like concat("catNameLong",'%') and teller."tellState"=1/*Activo*/ )  f
                
                LEFT JOIN (select  "tellId",count(*) AS "nroCallPending" from appointment_temp  where "apptmState"='1'/*ACTIVO*/GROUP BY "tellId")  s on f."tellId"=s."tellId"  ORDER BY nroCallPending ASC, o ASC limit 1;
            
            /*Verificamos que exi*/
            IF _tellId IS NULL THEN
                RAISE EXCEPTION '<msg>Lo sentimos, en este momento no disponemos de ventanillas para este servicio.<msg>';
            END  IF;
            NEW."tellId":=_tellId;   
        END IF;
    END IF;
    /*Generamos el codigo */
    select COALESCE(max("apptmNro"), 0) into _maxApptmNro from appointment_temp where "catId"=NEW."catId" and CAST(created_at as date)=CAST(now() AS date);
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


/*
        select random() AS o, teller."tellId",*  from teller 
            INNER JOIN d_category_teller 
                on teller."tellId"=d_category_teller."tellId"
            INNER JOIN category
                on d_category_teller."catId"=category."catId" where
                 (select "catNameLong" from category where "catId"=5) like concat("catNameLong",'%') and teller."tellState"=1 ORDER BY o ASC;

select  count(*) AS nro_call_pending from appointment_temp  where "apptmState"='1'GROUP BY "tellId"


     SELECT * from (select random() AS o, teller."tellId"  from teller 
            INNER JOIN d_category_teller 
                on teller."tellId"=d_category_teller."tellId"
            INNER JOIN category
                on d_category_teller."catId"=category."catId" where
                 (select "catNameLong" from category where "catId"=5) like concat("catNameLong",'%') and teller."tellState"=1  ORDER BY o ASC)  f
            
             LEFT JOIN (select  "tellId",count(*) AS nro_call_pending from appointment_temp  where "apptmState"='1' GROUP BY "tellId")  s on f."tellId"=s."tellId"



                and (select "catNameLong" from category where "catId"=5) like concat("catNameLong",'%') and teller."tellState"=1  ORDER BY o ASC;


*/

select *,  EXTRACT(EPOCH FROM current_timestamp-"apptmDateTimePrint") as "elapsedSeconds" from appointment_temp where "apptmState"=1


      select teller.*, person.*, (select count(*) from appointment_temp where "apptmState"=1 and "tellId"=teller."tellId" ) as "callPending" from teller left join users on teller."userId"=users."id" left join person on users.id=person."perId"
select teller.*, person.*, (select count(*) from appointment_temp where "apptmState"=1 and "tellId"=teller."tellId" ) as "callPending" from appointment_temp where "tellId"=teller."tellId") from teller left join users on teller."userId"=users."id" left join person on users.id=person."perId"


SELECT o, f."tellId", COALESCE("nroCallPending" ,0) as nroCallPending from (select random() AS o, teller."tellId"  from teller 
                INNER JOIN d_category_teller 
                    on teller."tellId"=d_category_teller."tellId"
                INNER JOIN category
                    on d_category_teller."catId"=category."catId" where
                    (select "catNameLong" from category where "catId"=new."catId") like concat("catNameLong",'%') and teller."tellState"=1/*Activo*/ )  f
                
                LEFT JOIN (select  "tellId",count(*) AS "nroCallPending" from appointment_temp  where "apptmState"='1'/*ACTIVO*/GROUP BY "tellId")  s on f."tellId"=s."tellId"  ORDER BY nroCallPending ASC, o ASC limit 1;
            

            select * from category