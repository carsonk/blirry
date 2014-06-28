blirry
=======

Tools
----------------

**Server-Side**
- PHP over CakePHP
- MySQL

**Client-Side**
- Bootstrap
- jQuery
- jQuery UI (themed features stripped)

Setup
----------------
- Setup a local LAMP environment, if you do not already have one.
- Create a app/Config/database.php. You can also do this by copying database.default.php into database.php, if the default settings are fine with you.

Schema Changes
----------------
- Any time you make changes to the database schema, please remember to update the schema file before you commit. You can do so by running the following on the command line: 
```
app/Console/cake schema generate
git commit -a -m 'Commit message.'
```
- Remember to check for schema changes when getting latest and update to them if necessary.
```
git pull
app/Console/Cake schema create
app/Console/Cake schema update
```