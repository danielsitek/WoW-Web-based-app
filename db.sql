
CREATE TABLE auctions (
    auc bigint(20),
    item bigint(20),
    owner varchar(40),
    buyout bigint(20),
    quantity bigint(20),
    PRIMARY KEY (auc)
);

CREATE TABLE status (
    realm varchar(21)
);

INSERT INTO status
VALUES (123);
