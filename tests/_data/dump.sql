CREATE TABLE service (
  id   INT AUTO_INCREMENT NOT NULL,
  name VARCHAR(255)       NOT NULL,
  PRIMARY KEY (id)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB;

CREATE TABLE city (
  id       INT AUTO_INCREMENT NOT NULL,
  name     VARCHAR(255)       NOT NULL,
  postcode VARCHAR(5)         NOT NULL,
  UNIQUE INDEX UNIQ_2D5B02345E237E06 (name),
  INDEX postcode_idx (postcode),
  PRIMARY KEY (id)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB;

CREATE TABLE job (
  id               INT AUTO_INCREMENT NOT NULL,
  service_id       INT DEFAULT NULL,
  title            VARCHAR(50)        NOT NULL,
  postcode         VARCHAR(5)         NOT NULL,
  city             VARCHAR(50)        NOT NULL,
  description      LONGTEXT           NOT NULL,
  fulfillment_date DATE               NOT NULL,
  is_canceled      TINYINT(1)         NOT NULL,
  created_at       DATETIME           NOT NULL,
  updated_at       DATETIME           NOT NULL,
  INDEX IDX_FBD8E0F8ED5CA9E6 (service_id),
  PRIMARY KEY (id)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB;

ALTER TABLE job ADD CONSTRAINT FK_FBD8E0F8ED5CA9E6 FOREIGN KEY (service_id) REFERENCES service (id);

INSERT IGNORE INTO service VALUES
(804040,'Sonstige Umzugsleistungen'),
(802030,'Abtransport, Entsorgung und Entrümpelung'),
(411070,'Fensterreinigung'),(402020, 'Holzdielen schleifen'),
(108140, 'Kellersanierung');

INSERT IGNORE INTO city (`postcode`, `name`) VALUES
('10115','Berlin'),
('32457','Porta Westfalica'),
('01623','Lommatzsch'),
('21521','Hamburg'),
('06895','Bülzig'),
('01612','Diesbar-Seußlitz');

INSERT IGNORE INTO job VALUES
(null,804040,'Title1','10115','Berlin','Desc1',DATE_ADD(NOW(), INTERVAL 15 DAY),0,NOW(),NOW()),
(null,802030,'Title2','10115','Berlin','Desc2',DATE_ADD(NOW(), INTERVAL 15 DAY),0,DATE_SUB(NOW(), INTERVAL 15 DAY),DATE_SUB(NOW(), INTERVAL 15 DAY)),
(null,804040,'Title3','21521','Hamburg','Desc3',DATE_ADD(NOW(), INTERVAL 15 DAY),0,NOW(),NOW()),
(null,802030,'Title4','21521','Hamburg','Desc4',DATE_ADD(NOW(), INTERVAL 15 DAY),0,DATE_SUB(NOW(), INTERVAL 15 DAY),DATE_SUB(NOW(), INTERVAL 15 DAY)),
(null,804040,'Title5','10115','Berlin','Desc5',DATE_ADD(NOW(), INTERVAL 15 DAY),0,NOW(),NOW());

