<?php

class Product
{

  // Connection
  private $conn;

  // Table
  private $db_table = "products";

  // Columns
  public $id_product;
  public $thumbnail;
  public $title;
  public $sub_title;
  public $description;
  public $price;
  public $compared_price;
  public $has_variants;
  public $quantity;
  public $type;
  public $is_published;
  public $meta_title;
  public $meta_description;
  public $meta_keywords;
  public $id_category;

  // Db connection


  public function __construct($db)
  {
    $this->conn = $db;
  }

  // GET ALL
  public function getProducts()
  {
    $sqlQuery = "SELECT * FROM products";
    $stmt = $this->conn->prepare($sqlQuery);
    $stmt->execute();
    return $stmt;
  }

  // GET ALL
  public function getCategoryProducts()
  {
    $sqlQuery = "SELECT p.*
                  FROM products p
                         INNER JOIN products_categories pc ON p.id_product = pc.id_product
                         INNER JOIN categories c ON pc.id_category = c.id_category
                  WHERE c.id_category = ?;";
    $stmt = $this->conn->prepare($sqlQuery);
    $stmt->bindParam(1, $this->id_category);

    $stmt->execute();
    return $stmt;
  }

  // CREATE
  public function createProduct()
  {
    $sqlQuery = "INSERT INTO
                        products
                    SET
                        thumbnail = :thumbnail,
                        title = :title,
                        sub_title = :sub_title,
                        description = :description,
                        price = :price,
                        compared_price = :compared_price,
                        has_variants = :has_variants,
                        quantity = :quantity,
                        type = :type,
                        is_published = :is_published,
                        meta_title = :meta_title,
                        meta_description = :meta_description,
                        meta_keywords = :meta_keywords
                        ";

    $stmt = $this->conn->prepare($sqlQuery);

    // bind data
    $stmt->bindParam(":thumbnail", $this->thumbnail);
    $stmt->bindParam(":title", $this->title);
    $stmt->bindParam(":sub_title", $this->sub_title);
    $stmt->bindParam(":description", $this->description);
    $stmt->bindParam(":compared_price", $this->compared_price);
    $stmt->bindParam(":has_variants", $this->has_variants);
    $stmt->bindParam(":quantity", $this->quantity);
    $stmt->bindParam(":price", $this->price);
    $stmt->bindParam(":type", $this->type);
    $stmt->bindParam(":is_published", $this->is_published);
    $stmt->bindParam(":meta_title", $this->meta_title);
    $stmt->bindParam(":meta_description", $this->meta_description);
    $stmt->bindParam(":meta_keywords", $this->meta_keywords);

    if ($stmt->execute()) {
      return $this->conn->lastInsertId();
    } else {
      return "product not created!";
    }
  }

  // GET SINGLE
  public function getSingleProduct()
  {
    $sqlQuery = "SELECT * FROM products WHERE id_product = ?";

    $stmt = $this->conn->prepare($sqlQuery);

    $stmt->bindParam(1, $this->id_product);

    if ($stmt->execute()) {

      return $stmt->fetch(PDO::FETCH_ASSOC);

    } else {
      return false;
    }
  }

  //GET IMAGES
  public function getProductImages()
  {
    $sqlQuery = "SELECT * FROM product_images WHERE id_product = ?";

    $stmt = $this->conn->prepare($sqlQuery);

    $stmt->bindParam(1, $this->id_product);

    if ($stmt->execute()) {

      return $stmt;

    } else {
      return false;
    }
  }


  //GET VARIANTS
  public function getProductVariants()
  {
    $sqlQuery = "SELECT * FROM variants WHERE id_product = ?";

    $stmt = $this->conn->prepare($sqlQuery);

    $stmt->bindParam(1, $this->id_product);

    if ($stmt->execute()) {

      return $stmt;

    } else {
      return false;
    }
  }

  // UPDATE
  public function updateProduct()
  {
    $sqlQuery = "UPDATE
                        products
                    SET
                        thumbnail = IF(LENGTH(:thumbnail)=0, thumbnail, :thumbnail),
                        title = IF(LENGTH(:title)=0, title, :title),
                        description = IF(LENGTH(:description)=0, description, :description),
                        price = IF(LENGTH(:price)=0, price, :price),
                        compared_price = IF(LENGTH(:compared_price)=0, compared_price, :compared_price)
                    WHERE
                        id_product = :id_product";

    $stmt = $this->conn->prepare($sqlQuery);

    // sanitize
    $this->thumbnail = htmlspecialchars(strip_tags($this->thumbnail));
    $this->title = htmlspecialchars(strip_tags($this->title));
    $this->description = htmlspecialchars(strip_tags($this->description));
    $this->price = htmlspecialchars(strip_tags($this->price));
    $this->compared_price = htmlspecialchars(strip_tags($this->compared_price));

    // bind data
    $stmt->bindParam(":thumbnail", $this->thumbnail);
    $stmt->bindParam(":title", $this->title);
    $stmt->bindParam(":description", $this->description);
    $stmt->bindParam(":price", $this->price);
    $stmt->bindParam(":compared_price", $this->compared_price);

    if ($stmt->execute()) {
      return true;
    }
    return false;
  }

  // DELETE
  public function deleteProduct()
  {
    $sqlQuery = "DELETE FROM products WHERE id_product = ?";
    $stmt = $this->conn->prepare($sqlQuery);

    $this->id_product = htmlspecialchars(strip_tags($this->id_product));

    $stmt->bindParam(1, $this->id_product);

    if ($stmt->execute()) {
      return true;
    }
    return false;
  }

}
