-- income table
DROP TABLE IF EXISTS incomes;
CREATE TABLE incomes (
	income_id INT UNSIGNED NOT NULL AUTO_INCREMENT,
	income_date DATE NOT NULL,
	price DECIMAL(7,2) UNSIGNED NOT NULL DEFAULT 0.00,
	invoice_id VARCHAR(10),
	description VARCHAR(200),
	PRIMARY KEY(income_id)
);

-- testing data
INSERT INTO incomes (income_date, price, invoice_id, description) VALUES
('2014-01-01', 4400, '01', 'VCARS'),
('2014-02-01', 3400, '02', 'VCARS - last');

-- expanses table for future extending
DROP TABLE IF EXISTS expenses;
CREATE TABLE expenses (
	expense_id INT UNSIGNED NOT NULL AUTO_INCREMENT,
	expense_date DATETIME NOT NULL,
	price DECIMAL(7,2) UNSIGNED NOT NULL DEFAULT 0.00,
	type_id TINYINT UNSIGNED NOT NULL DEFAULT 0,
	description VARCHAR(200),
	PRIMARY KEY(expense_id)
);

-- expense type
DROP TABLE IF EXISTS expense_types;
CREATE TABLE expense_types (
	type_id TINYINT UNSIGNED NOT NULL AUTO_INCREMENT,
	title VARCHAR(20),
	PRIMARY KEY(type_id)
);

INSERT INTO expense_types(title) VALUES ('petrol'),('home');
