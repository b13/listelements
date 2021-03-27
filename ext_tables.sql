#
# Table structure for table 'tt_content'
#
CREATE TABLE tt_content (
	list text
);

#
# Table structure for table 'listitems'
#
CREATE TABLE listitems (

# Reference fields (basically same as MM table)
	sorting_foreign int(11) DEFAULT '0' NOT NULL,
	uid_foreign int(11) DEFAULT '0' NOT NULL,

# local usage fields
	tablename varchar(64) DEFAULT 'tt_content' NOT NULL,
	fieldname varchar(64) DEFAULT 'list' NOT NULL,
	header varchar(255) DEFAULT '' NOT NULL,
	subheader varchar(255) DEFAULT '' NOT NULL,
	layout varchar(255) DEFAULT '' NOT NULL,
	text mediumtext,
	bodytext mediumtext,
	image int(11) unsigned DEFAULT '0' NOT NULL,
	assets int(11) unsigned DEFAULT '0' NOT NULL,
	link varchar(1024) DEFAULT '' NOT NULL,
	linklabel varchar(255) DEFAULT '' NOT NULL,
	linkconfig varchar(255) DEFAULT '' NOT NULL
);
