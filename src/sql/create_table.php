

-----

CREATE TABLE bills (id INT(11) NOT NULL AUTO_INCREMENT,month INT(11),sum INT(11),PRIMARY KEY (id));
CREATE TABLE bill_lines (id INT(11) NOT NULL AUTO_INCREMENT,date INT(11),sum INT(11),PRIMARY KEY (id));
CREATE TABLE category (id INT(11) NOT NULL AUTO_INCREMENT,month INT(11),sum INT(11),PRIMARY KEY (id));
CREATE TABLE shop (id INT(11) NOT NULL AUTO_INCREMENT,name TEXT,default_category_id INT(11),PRIMARY KEY (id), foreign key(default_category_id) references category(id));
CREATE TABLE card (id INT(11) NOT NULL AUTO_INCREMENT,name TEXT,start_row_num INT(11), date_column_num INT(11),shop_column_num INT(11), file_prefix TEXT, sum_column_num INT(11), sum_row_num INT(11), japanege_name TEXT,PRIMARY KEY (id));

CREATE TABLE user (id INT(11) NOT NULL AUTO_INCREMENT,name TEXT, default_payment INT(11), additional_ratio,PRIMARY KEY (id));

-----

CREATE USER housekeeper IDENTIFIED BY 'housekeeper';
GRANT ALL PRIVILEGES ON housekeeping.* TO housekeeper@localhost IDENTIFIED BY 'housekeeper';

-----



INSERT INTO card
(name, start_row_num, date_column_num, shop_column_num, file_prefix, sum_column_num, sum_row_num)
VALUES
('コストコ', 10, 0, 1, 'COSTCO', 2, 3);

ALTER TABLE card DROP COLUMN month_row_num;
ALTER TABLE card DROP COLUMN month_column_num;

ALTER TABLE card
CHANGE COLUMN show_column_num shop_column_num INT(11);

ALTER TABLE card CHARSET=utf8;

ALTER TABLE card DROP COLUMN japanege_name;

INSERT INTO card
(name, start_row_num, date_column_num, shop_column_num, file_prefix, sum_column_num, sum_row_num)
VALUES
('DC', 9, 0, 3, 'DC', 6, 2);


ALTER TABLE bill_lines ADD COLUMN shop_id INT(11);
ALTER TABLE bill_lines ADD COLUMN category_id INT(11);
ALTER TABLE bill_lines DROP COLUMN sum;
ALTER TABLE bill_lines ADD COLUMN money INT(11);
ALTER TABLE bill_lines ADD COLUMN row INT(11);

ALTER TABLE bill_lines add foreign key (shop_id) references shop(id);
ALTER TABLE bill_lines add foreign key (category_id) references category(id);
ALTER TABLE bill_lines CHANGE COlUMN shop_id shop_id int(11) not null;
ALTER TABLE bill_lines CHANGE COlUMN category_id category_id int(11) not null;
//配列にアルファベットで入るため、型変更
ALTER TABLE card CHANGE COlUMN sum_column_num sum_column_num TEXT;

UPDATE card
set date_column_num = "A", shop_column_num = "B", sum_column_num="C"
where id=2;

//category
ALTER TABLE category DROP COLUMN sum,month;


INSERT INTO card
(name, start_row_num, date_column_num, shop_column_num, file_prefix, sum_column_num, sum_row_num,payment_column_num)
VALUES
('UFJ銀行引き落とし', 3, 'A', 'C', 'UFJ', 'A', 1,'D');


INSERT INTO card
(name, start_row_num, date_column_num, shop_column_num, file_prefix, sum_column_num, sum_row_num,payment_column_num)
VALUES
('美幸立て替え', 3, 'A', 'C', 'MIYUKI', 'A', 1,'D');


年の、カテゴリ別集計を出すsql

select category.name, sum(bill_lines.payment) from category inner join bill_lines on category.id = bill_lines.category_id inner join bills on bills.id = bill_lines.bill_id where bills.month like '2012%' group by category_id;

