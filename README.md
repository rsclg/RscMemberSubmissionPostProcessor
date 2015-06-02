[![Latest Version on Packagist](http://img.shields.io/packagist/v/rsclg/member-submission-post-processor.svg?style=flat)](https://packagist.org/packages/rsclg/member-submission-post-processor)
[![Installations via composer per month](http://img.shields.io/packagist/dm/rsclg/member-submission-post-processor.svg?style=flat)](https://packagist.org/packages/rsclg/member-submission-post-processor)
[![Installations via composer total](http://img.shields.io/packagist/dt/rsclg/member-submission-post-processor.svg?style=flat)](https://packagist.org/packages/rsclg/member-submission-post-processor)

Contao Extension: RscMemberSubmissionPostProcessor
==================================================

Perform additional actions after registration of a new member in the RSC web system.


Installation
------------

Install the extension via composer: [rsclg/member-submission-post-processor](https://packagist.org/packages/rsclg/member-submission-post-processor).

If you prefer to install it manually, download the latest release here: https://github.com/rsclg/RscMemberSubmissionPostProcessor/releases


### Database modification

Execute the following database script to ensure Contao to Roundcube connection:

```
-- add the member number as new column, to get an reference value
ALTER TABLE rcb_contacts ADD rsc_member_number INT( 4 ) UNSIGNED;
UPDATE rcb_contacts SET rsc_member_number = (SELECT xt_club_membernumber FROM tl_member WHERE rcb_contacts.name = CONCAT(tl_member.firstname, " ", tl_member.lastname));

-- add view that are expected from contao
CREATE OR REPLACE VIEW rcb2cto_contactgroups (id, name, tstamp) AS SELECT contactgroup_id, name, changed FROM rcb_contactgroups;
CREATE OR REPLACE VIEW rcb2cto_contacts (id, tstamp, name, firstname, lastname, email, member_number, user_id) AS SELECT contact_id, changed, name, firstname, surname, email, rsc_member_number, user_id FROM rcb_contacts WHERE del = 0;
CREATE OR REPLACE VIEW rcb2cto_users (id, name, tstamp) AS SELECT user_id, username, created FROM rcb_users;
```


Tracker
-------

https://github.com/rsclg/RscMemberSubmissionPostProcessor/issues


Compatibility
-------------

- min. Contao version: >= 3.3.0
- max. Contao version: <  3.5.0


Dependency
----------

- This extension is dependent on the following extensions: [[contao-legacy/associategroups]](https://legacy-packages-via.contao-community-alliance.org/packages/contao-legacy/associategroups), [[rsclg/club-member-fields]](https://packagist.org/packages/rsclg/club-member-fields), [[cliffparnitzky/user-member-bridge]](https://packagist.org/packages/cliffparnitzky/user-member-bridge)