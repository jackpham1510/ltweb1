/*
  Update sold and quantity when insert orders_detail
*/
drop trigger if exists auto_update_product_when_insert_order;

DELIMITER $$

create trigger auto_update_product_when_insert_order
after insert on ORDERS_DETAIL
for each row
begin
  update PRODUCT set quantity = quantity - NEW.quantity, sold = sold + NEW.quantity where product_id = NEW.product_id;
end$$

DELIMITER ;

/*
  Update sold and quantity when update orders_detail
*/

drop trigger if exists auto_update_product_when_update_order;

DELIMITER $$

create trigger auto_update_product_when_update_order
after update on ORDERS_DETAIL
for each row
begin
  declare orderStatus int;

  set @orderStatus := (select status from ORDERS where order_id = NEW.order_id);
  
  if (@status < 0)
  then
    update PRODUCT set quantity = quantity - NEW.quantity, sold = sold + NEW.quantity where product_id = NEW.product_id;
  end if;
end$$

DELIMITER ;