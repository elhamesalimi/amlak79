ALTER TABLE `estates` ADD fields_facilities varchar(500) AS (JSON_EXTRACT(fields, '$.facilities'));
CREATE INDEX estates_fields_facilities_ix ON estates(fields_facilities)

