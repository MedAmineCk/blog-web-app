drop database ecomlocal;
create database ecomlocal;

# `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
# `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
# tracking	Date	Client	Items	Total	City	Status
select
  orders.id_order,
  orders.created_at,
  orders.items_quantity,
  orders.total,
  orders.status,
  areas.area,
  clients_packs.client_name
from orders, areas , clients_packs
where
  orders.id_client_pack = clients_packs.id_client_pack
and
  clients_packs.id_area = areas.id_area;

SELECT status, COUNT(*) AS count
FROM orders
GROUP BY status;

SELECT
  deliverer_packs.id_deliverer_pack AS pack_id,
  CONCAT(deliverers.first_name, ' ', deliverers.last_name) AS deliverer_name,
  areas.area AS area_name,
  COUNT(orders.id_order) AS order_count,
  deliverer_packs.status AS pack_status,
  deliverer_packs.created_at AS created_date
FROM
  deliverer_packs
    INNER JOIN deliverers ON deliverer_packs.id_deliverer = deliverers.id_deliverer
    INNER JOIN areas ON deliverers.id_area = areas.id_area
    LEFT JOIN orders ON deliverer_packs.id_deliverer_pack = orders.id_deliverer_pack
GROUP BY
  deliverer_packs.id_deliverer_pack;

SELECT
  orders.id_order,
  orders.created_at,
  orders.items_quantity,
  orders.total,
  orders.status,
  clients_packs.client_name,
  clients_packs.client_phone,
  clients_packs.client_address,
  areas.area,
  deliverer_packs.id_deliverer_pack
FROM orders
       INNER JOIN clients_packs ON orders.id_client_pack = clients_packs.id_client_pack
       INNER JOIN deliverer_packs ON orders.id_deliverer_pack = deliverer_packs.id_deliverer_pack
       INNER JOIN areas ON clients_packs.id_area = areas.id_area
where orders.id_deliverer_pack = 1;

SELECT
  orders.id_order,
  orders.items_quantity,
  orders.total,
  orders.status,
  products.title,
  products.price,
  clients_packs.client_name,
  clients_packs.client_address,
  clients_packs.client_phone,
  areas.area
FROM
  orders
    JOIN products ON orders.id_product = products.id_product
    JOIN clients_packs ON orders.id_client_pack = clients_packs.id_client_pack
    JOIN areas on clients_packs.id_area = areas.id_area
where orders.id_order = 1;

select * from deliveries where role_deliverer = 'Deliverer' and id_deliverer = ?;

select 'admin_balance' as type, IFNULL(sum(total), 0) as balance from orders where status = 'Deliver'
union
select 'deliverer_balance' as type, IFNULL(sum(invoices.credit), 0) as balance from invoices where status = 'Paid' and type = 'deliverer'
union
select 'confirmer_balance' as type, IFNULL(sum(invoices.credit), 0) as balance from invoices where status = 'Paid' and type = 'confirmer';

ALTER TABLE orders
  DROP FOREIGN KEY fk_orders_products1,
  ADD CONSTRAINT fk_orders_products1
    FOREIGN KEY (id_product)
      REFERENCES products(id_product)
      ON DELETE SET NULL
      ON UPDATE NO ACTION;


alter table categories add `is_published` BLOB NULL DEFAULT false;

SELECT p.*
FROM products p
       INNER JOIN products_categories pc ON p.id_product = pc.id_product
       INNER JOIN categories c ON pc.id_category = c.id_category
WHERE c.id_category = 2;
