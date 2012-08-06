-- ********************************************************
-- *                                                      *
-- * IMPORTANT NOTE                                       *
-- *                                                      *
-- * Do not import this file manually but use the Contao  *
-- * install tool to create and maintain database tables! *
-- *                                                      *
-- ********************************************************

-- 
-- Changes are needed to connect Contao and Roundcube
--
-- add the member number as new column, to get an reference value
ALTER TABLE `rcb_contacts` ADD `rsc_member_number` INT( 4 ) UNSIGNED;
UPDATE rcb_contacts SET rsc_member_number = (SELECT xt_club_membernumber FROM tl_member WHERE rcb_contacts.name = CONCAT(tl_member.firstname, " ", tl_member.lastname));

-- add view that are expected from contao
CREATE OR REPLACE VIEW rcb2cto_contactgroups (id, name, tstamp) AS SELECT contactgroup_id, name, changed FROM rcb_contactgroups;
CREATE OR REPLACE VIEW rcb2cto_users (id, name, tstamp) AS SELECT user_id, username, created FROM rcb_users;