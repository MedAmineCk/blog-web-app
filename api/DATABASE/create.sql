USE blog;

# Users Table
CREATE TABLE users (
                        id_user  int auto_increment primary key,
                        role     varchar(45) null comment 'admin | assistant',
                        email    varchar(45) null,
                        password varchar(45) null
);

# Articles Table
CREATE TABLE articles (
                          id INT AUTO_INCREMENT PRIMARY KEY,
                          title VARCHAR(255) NOT NULL,
                          subtitle VARCHAR(255),
                          content TEXT,
                          thumbnail_url VARCHAR(255),
                          is_public BOOLEAN DEFAULT 0,
                          is_pinned BOOLEAN DEFAULT 0,
                          tags VARCHAR(255),
                          created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                          updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

# Categories Table
CREATE TABLE categories (
                            id INT AUTO_INCREMENT PRIMARY KEY,
                            name VARCHAR(255) NOT NULL,
                            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

# ArticleCategories Table
CREATE TABLE article_categories (
                                    article_id INT,
                                    category_id INT,
                                    FOREIGN KEY (article_id) REFERENCES articles(id) ON DELETE CASCADE,
                                    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE,
                                    PRIMARY KEY (article_id, category_id)
);

# Tags Table
CREATE TABLE tags (
                      id INT AUTO_INCREMENT PRIMARY KEY,
                      name VARCHAR(255) NOT NULL,
                      created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                      updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

# ArticleTags Table
CREATE TABLE article_tags (
                              article_id INT,
                              tag_id INT,
                              FOREIGN KEY (article_id) REFERENCES articles(id) ON DELETE CASCADE,
                              FOREIGN KEY (tag_id) REFERENCES tags(id) ON DELETE CASCADE,
                              PRIMARY KEY (article_id, tag_id)
);
