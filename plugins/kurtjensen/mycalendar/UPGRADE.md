# Upgrade guide

- [Upgrading to 1.0.10 from 1.0.9](#upgrade-1.0.10)
- [Upgrading to 1.0.18](#upgrade-1.0.18)
- [Upgrading to 1.1.5 from 1.1.4](#upgrade-1.1.5)
- [Upgrading to 1.1.8 from 1.1.7](#upgrade-1.1.8)

<a name="upgrade-1.1.8"></a>
## Upgrading To 1.1.8

**This is an important update that contains breaking changes.**

The Super Simple Calendar is Growing Up. 
Several customers have requested the ability to have recurring dates on MyCalendar. This feature has now been added and I want to make sure existing customers understand how this update will effect them and their current installation. Details about the changes and upgrade instructions can be found at: http://firemankurt.com/mycalendar

<a name="upgrade-1.1.5"></a>
## Upgrading To 1.1.5

**This is an important update that contains breaking changes.**

 MyCalendar will now be using **Passage Permission Keys** plugin for permissions.

 You will need to install that plugin using code __"kurtjensen.passage"__.

 You should follow directions in Passage Permission Keys plugin to transfer your permissions over from the Roles plugin.  No other changes should be needed for existing MyCalendar data to work as long as the "id" of the permissions in Passage Permission Keys matches the "id" old of the permissions in Roles.

 If you do not install Passage Permission Keys plugin then your protected events may not show or may be visible to anyone who visits your site until you do add PassagePermission Keys plugin.

 This change was due to some unresolved issues in the Roles plugin that made it unreliable and out of our control to fix.

 **IF YOU USE PERMISSIONS, DO NOT UPGRADE TO 1.1.5 UNLESS YOU ARE GOING TO INSTALL Passage Permission Keys PLUGIN.**

 This update also added raw data element to event array and applied config date/time to all components.


<a name="upgrade-1.0.18"></a>
## Upgrading To 1.0.18

The database has been changed to the more common and easier to use date field instead of separate year,month, and day fields.
There is very good reason for using one date type field and I should have thought that through better prior to original design. If you are ever thinking of breaking dates across multiple DB fields in one of your own projects, DON'T DO IT. Dates are a nightmare already without adding a non-standard method of storage to the problem.

Your data should transfer to this new date field automagicaly on upgrade and you should not need to change any data.

Please review instructions as some things have changed for displaying "Event Lists".  This should be much easier now.

I have discovered that some items in Backend still do not use translation and those will areas be addressed in upcoming updates.  Thank you for your patients.

<a name="upgrade-1.0.10"></a>
## Upgrading To 1.0.10

The Month Component now includes a "Next" and "Previous" link to allow users to scroll through months.
You will need to add parameters to your page URL to accept these parameters.
Example:

    /calendarpage/:month?/:year?

This will make the parameters optional and default to current month and year.