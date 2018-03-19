CREATE TABLE
    marker_types
    (
        typeId bigint NOT NULL AUTO_INCREMENT,
        typeName VARCHAR(20),
        typeImage VARCHAR(200),
        typeDescription VARCHAR(200),
        PRIMARY KEY (typeId)
    )
    ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO marker_types (typeId, typeName, typeImage, typeDescription) VALUES (1, 'Pest', 'img/yellow.png', 'Pest related crop loss.');
INSERT INTO marker_types (typeId, typeName, typeImage, typeDescription) VALUES (2, 'Drought', 'img/dark_green.png', 'General drought/lack of water without extensive thermal events');
INSERT INTO marker_types (typeId, typeName, typeImage, typeDescription) VALUES (3, 'Flood', 'img/blue.png', 'Flood event(damn break/unexplained raised water table)');
INSERT INTO marker_types (typeId, typeName, typeImage, typeDescription) VALUES (4, 'Hail', 'img/magenta.png', 'Hail damage');
INSERT INTO marker_types (typeId, typeName, typeImage, typeDescription) VALUES (5, 'Cold', 'img/orange.png', 'Cold/Freeze');
INSERT INTO marker_types (typeId, typeName, typeImage, typeDescription) VALUES (6, 'Hot', 'img/red.png', 'Extremem heatwaves, exceeding typical zone conditions and can include drought  as reason for crop loss');
INSERT INTO marker_types (typeId, typeName, typeImage, typeDescription) VALUES (7, 'Storm', 'img/gray.png', 'Extremem weather event(hurricane, blizzard).');
INSERT INTO marker_types (typeId, typeName, typeImage, typeDescription) VALUES (8, 'Wind', 'img/green.png', 'Wind event without other symptoms. ');
INSERT INTO marker_types (typeId, typeName, typeImage, typeDescription) VALUES (16, 'Moisture', 'img/brown.png', 'General loss from moisture/rot/extra rain without "extreme" symptoms. (');

CREATE TABLE
    markers
    (
        id INT NOT NULL AUTO_INCREMENT,
        name VARCHAR(60) NOT NULL,
        address VARCHAR(80) NOT NULL,
        lat FLOAT(10,6) NOT NULL,
        lng FLOAT(10,6) NOT NULL,
        YEAR INT(4),
        typeId bigint,
        PRIMARY KEY (id),
        INDEX markersTypeIdFK (typeId)
    )
    ENGINE=InnoDB DEFAULT CHARSET=latin1;
    
CREATE VIEW
    v_markers
    (
        markerId,
        name,
        address,
        lat,
        lng,
        type,
        image,
        YEAR
    ) AS
SELECT
    `a`.`id`        AS `markerId`,
    `a`.`name`      AS `name`,
    `a`.`address`   AS `address`,
    `a`.`lat`       AS `lat`,
    `a`.`lng`       AS `lng`,
    `b`.`typeName`  AS `type`,
    `b`.`typeImage` AS `image`,
    `a`.`year`      AS `year`
FROM
    (`markers` `a`
JOIN
    `marker_types` `b`
ON
    ((
            `a`.`typeId` = `b`.`typeId`)));
