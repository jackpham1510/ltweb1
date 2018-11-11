drop table if exists BRANCH;

drop table if exists CATEGORY;

drop table if exists ORDERS;

drop table if exists ORDERS_DETAIL;

drop table if exists PRODUCT;

drop table if exists USERS;

/*==============================================================*/
/* Table: BRANCH                                                */
/*==============================================================*/
create table BRANCH
(
   BRANCH_ID            int not null,
   NAME                 varchar(40),
   primary key (BRANCH_ID)
);

/*==============================================================*/
/* Table: CATEGORY                                              */
/*==============================================================*/
create table CATEGORY
(
   CATEGORY_ID          int not null,
   NAME                 varchar(40),
   primary key (CATEGORY_ID)
);

/*==============================================================*/
/* Table: ORDERS                                                */
/*==============================================================*/
create table ORDERS
(
   ID                   int not null,
   USERNAME             char(50),
   ORDER_DATE           datetime,
   RECEIVE_DATE         datetime,
   STATUS               int,
   AMOUNT               decimal,
   primary key (ID)
);

/*==============================================================*/
/* Table: ORDERS_DETAIL                                         */
/*==============================================================*/
create table ORDERS_DETAIL
(
   ID                   int not null,
   PRODUCT_ID           int not null,
   QUANTITY             int not null,
   primary key (ID, PRODUCT_ID)
);

/*==============================================================*/
/* Table: PRODUCT                                               */
/*==============================================================*/
create table PRODUCT
(
   PRODUCT_ID           int not null,
   BRANCH_ID            int,
   CATEGORY_ID          int,
   NAME                 varchar(100),
   AMOUNT               decimal,
   VIEW                 decimal,
   DESCRIPTION          varchar(255),
   QUANTITY             int,
   primary key (PRODUCT_ID)
);

/*==============================================================*/
/* Table: USERS                                                 */
/*==============================================================*/
create table USERS
(
   USERNAME             char(50) not null,
   PASSWORD             char(50) not null,
   NAME                 varchar(50),
   PHONE                char(11),
   ADDRESS              varchar(255),
   TYPE                 int,
   primary key (USERNAME)
);

alter table ORDERS add constraint FK_USER_ORDER foreign key (USERNAME)
      references USERS (USERNAME) on delete restrict on update restrict;

alter table ORDERS_DETAIL add constraint FK_ORDERS_DETAIL foreign key (ID)
      references ORDERS (ID) on delete restrict on update restrict;

alter table ORDERS_DETAIL add constraint FK_ORDERS_DETAIL2 foreign key (PRODUCT_ID)
      references PRODUCT (PRODUCT_ID) on delete restrict on update restrict;

alter table PRODUCT add constraint FK_BRANCH_PRODUCT foreign key (BRANCH_ID)
      references BRANCH (BRANCH_ID) on delete restrict on update restrict;

alter table PRODUCT add constraint FK_CATEGORY_PRODUCT foreign key (CATEGORY_ID)
      references CATEGORY (CATEGORY_ID) on delete restrict on update restrict;
