-- ******************************
-- * Add marker_types table.
-- ******************************

CREATE SEQUENCE iaf_marker_type_id;
CREATE TABLE marker_types (typeId integer default nextval('iaf_marker_type_id') NOT NULL, typeName character varying(20), typeImage character varying(200), typeDescription character varying(200), PRIMARY KEY (typeId));
INSERT INTO marker_types (typeId, typeName, typeImage, typeDescription) VALUES (1, 'Pest', 'img/yellow.png', 'Pest related crop loss.');
INSERT INTO marker_types (typeId, typeName, typeImage, typeDescription) VALUES (2, 'Drought', 'img/dark_green.png', 'General drought/lack of water without extensive thermal events');
INSERT INTO marker_types (typeId, typeName, typeImage, typeDescription) VALUES (3, 'Flood', 'img/blue.png', 'Flood event(damn break/unexplained raised water table)');
INSERT INTO marker_types (typeId, typeName, typeImage, typeDescription) VALUES (4, 'Hail', 'img/magenta.png', 'Hail damage');
INSERT INTO marker_types (typeId, typeName, typeImage, typeDescription) VALUES (5, 'Cold', 'img/orange.png', 'Cold/Freeze');
INSERT INTO marker_types (typeId, typeName, typeImage, typeDescription) VALUES (6, 'Hot', 'img/red.png', 'Extremem heatwaves, exceeding typical zone conditions and can include drought  as reason for crop loss');
INSERT INTO marker_types (typeId, typeName, typeImage, typeDescription) VALUES (7, 'Storm', 'img/gray.png', 'Extremem weather event(hurricane, blizzard).');
INSERT INTO marker_types (typeId, typeName, typeImage, typeDescription) VALUES (8, 'Wind', 'img/green.png', 'Wind event without other symptoms. ');
INSERT INTO marker_types (typeId, typeName, typeImage, typeDescription) VALUES (16, 'Moisture', 'img/brown.png', 'General loss from moisture/rot/extra rain without "extreme" symptoms. (');
-- ******************************
-- * Alter iaf_markers, add new column/fk
-- ******************************
ALTER TABLE
    iafmap.public.iaf_markers ADD COLUMN dateAdded DATE;
ALTER TABLE
    iafmap.public.iaf_markers ALTER COLUMN dateAdded SET DEFAULT now();
ALTER TABLE
    iafmap.public.iaf_markers ADD COLUMN YEAR INTEGER;
ALTER TABLE
    iafmap.public.iaf_markers RENAME COLUMN type TO type_old;
ALTER TABLE
    iafmap.public.iaf_markers RENAME COLUMN DATE TO contentDate;
ALTER TABLE
    iafmap.public.iaf_markers ADD COLUMN typeID INTEGER;

ALTER TABLE
    iafmap.public.iaf_markers ADD CONSTRAINT iaf_markers_type_fk FOREIGN KEY (typeID) REFERENCES
    iafmap.public.marker_types (typeid);
    
-- ******************************
-- * Add view for type/icon info.
-- ******************************

create or replace view v_iaf_markers as             
SELECT
    public.iaf_markers.id,
    public.iaf_markers.url,
    public.iaf_markers.title,
    public.iaf_markers.lat,
    public.iaf_markers.lng,
    public.iaf_markers.contentdate,
    public.iaf_markers.dateadded,
    public.iaf_markers.year,
    public.iaf_markers.typeid,
    public.marker_types.typename as "type",
    public.marker_types.typeimage,
    public.marker_types.typedescription
FROM
    public.iaf_markers 
LEFT JOIN
    public.marker_types
ON
    (
        public.iaf_markers.typeid = public.marker_types.typeid) ;
        

-- ******************************
-- * Update fact table with dimension keys
-- ******************************        
-- update iaf_markers set typeid=(select typeid from marker_types where initcap(iaf_markers.type)=initcap(marker_types.typename));        


-- Final cleanup!
-- Do we have records with no matching marker_type record? (If yes, there are a couple of free colors for the flag icon set.)
-- SELECT distinct type_old from iaf_markers where type_old<>'' and initcap(type_old) not in(select typename from marker_types);

-- Once marker_types is complete and iaf_markers.typeid is correct for all rows, the "type_old" column can be dropped.
-- ALTER TABLE iaf_markers DROP COLUMN type_old;
