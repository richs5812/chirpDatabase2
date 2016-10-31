chirpDatabase
=============

Database application designed for the Brightmoor Connection food pantry. Installation instructions:

<ol>
	<li>This database requires a webserver in order to operate. If installing locally, you can use <a href="https://www.apachefriends.org/index.html">XAMPP</a></li>
	<li>download or clone code from https://github.com/richs5812/chirpDatabase2.git and save in web accessible directory (this will be www folder on most web servers, or htdocs folder in XAMPP)</li>
	<li>Create a MySQL database and import existing data; or, if creating a new database, generate tables with the following command: <code>php bin/console doctrine:schema:update --force</code>. Create and/or manage users with the instructions on https://symfony.com/doc/current/bundles/FOSUserBundle/command_line_tools.html. FYI, user management is controlled by the <a href="https://symfony.com/doc/current/bundles/FOSUserBundle/index.html">FOSUserBundle</a></li>
	<li><em>The database application is built using the PHP framework Symfony. The following instructions are adapted from the Symfony deployment directions on https://symfony.com/doc/current/deployment.html</em> Open a command line tool (e.g. Terminal on Mac, Command Prompt on Windows) and navigate to the installation folder. Run the command <code>php bin/symfony_requirements</code> to check if your server meets the requirements.</li>
	<li>Install/update vendors by running <code>composer install</code> from the command line (you will need to have <a href="https://getcomposer.org/">Composer</a> installed). The command line will prompt you to enter the MySQL database credentials during this step, as well as email account information (email account needed for user password retrieval). Either get the currently configured email address from Rich (richs5812@gmail.com), or follow Symfony instructions for setting up emails: https://symfony.com/doc/current/email.html. Symfony recommends using a gmail account; instructions here: https://symfony.com/doc/current/email/gmail.html.</li>
	<li>Clear Symfony cache with the command <code>php bin/console cache:clear --env=prod --no-debug</code></li>
</ol>

That should be it!
