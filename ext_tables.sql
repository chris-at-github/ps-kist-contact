CREATE TABLE tt_address (
	tx_contact_locations int(11) unsigned DEFAULT '0',
	tx_contact_additional_description text
);

CREATE TABLE tx_contact_domain_model_location (
	zip varchar(255) DEFAULT '' NOT NULL,
	contact int(11) unsigned DEFAULT '0',
	country int(11) unsigned DEFAULT '0'
);

CREATE TABLE sys_category (
	tx_contact_zip_regex varchar(255) DEFAULT '' NOT NULL
);
