SET FOREIGN_KEY_CHECKS = 0;
SET FOREIGN_KEY_CHECKS = 1;
/*----- users ------*/
INSERT INTO `ecomlocal`.`users` (`id_user`, `email`, `password`, `role`)
VALUE (1, 'root', '0000', 'admin');

/*----- categories ------*/
INSERT INTO `ecomlocal`.categories (category, thumbnail, description)
  VALUES
    ('all', 'placeholder.jpg', 'this category for all products'),
    ('clothes', 'placeholder.jpg', 'this category for clothes products'),
    ('watches', 'placeholder.jpg', 'this category for watches products');

insert into areas (area, shipping, return_price)
values
  ('oujda', 20, 0),
  ('taourirt', 30, 10),
  ('berkane', 25, 5);

insert into clients_packs (id_area, client_name, client_phone, client_address, label)
value ()
