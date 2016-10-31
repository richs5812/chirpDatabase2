chirpDatabase
=============

Installation instructions:

<ol>
	<li>Set up webserver. If installing locally, you can use XAMPP (https://www.apachefriends.org/index.html)</li>
	<li>download or clone code from https://github.com/richs5812/chirpDatabase2.git and save in web accessible directory</li>
	<li>Create a MySQL database and import existing data. If creating a new database, generate tables with the following command: <code>php bin/console doctrine:schema:update --force</code>. To create new users, see the instructions on https://symfony.com/doc/current/bundles/FOSUserBundle/command_line_tools.html. User management is controlled by the <a href="https://symfony.com/doc/current/bundles/FOSUserBundle/index.html">FOSUserBundle</a></li>
	<li><em>The database application is built using the PHP framework Symfony. The next instructions are from the Symfony deployment directions on https://symfony.com/doc/current/deployment.html</em> Open a command line tool and navigate to the installation folder. Run the command <code>php bin/symfony_requirements</code> to check if your server meets the requirements.</li>
	<li>Install/update vendors by running <code>composer install</code> from the command line (you will need to have <a href="https://getcomposer.org/">Composer</a> installed). The command line will prompt you to enter database credentials during this step, as well as email account information (needed for password retrieval). Either get the currently configured email address from Rich (richs5812@gmail.com), or follow Symfony instructions for setting up emails: https://symfony.com/doc/current/email.html. I found gmail to be the easiest type of account to setup; instructions here: https://symfony.com/doc/current/email/gmail.html. Again, email is only used for the 'forgot password' function for users</li>
	<li>Clear Symfony cache with the command <code>php bin/console cache:clear --env=prod --no-debug</code></li>
</ol>

That should be it!
