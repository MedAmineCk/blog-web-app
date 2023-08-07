SET FOREIGN_KEY_CHECKS = 0;
SET FOREIGN_KEY_CHECKS = 1;

truncate product_images;
truncate products_categories;
truncate variants;
truncate products;

drop table orders;

drop database ecomlocal;
drop table deliveries;
drop table invoices;
drop table product_images;

truncate products_categories;
drop table products_categories;

truncate clients_packs;
truncate orders;

truncate variants;
drop table variants;

update orders set status = 'Pending' where true;
