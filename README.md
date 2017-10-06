chirpDatabase
=============

my test remote commit

Database application designed for the Brightmoor Connection food pantry.

<h2>Installation instructions</h2>
<p><strong>Current installation instructions can be found here: <a href="https://richs5812.github.io/chirpDatabase2/">https://richs5812.github.io/chirpDatabase2</a>. Previous, less detailed instructions are below.</strong></p>


<ol>
	<li>This database requires a webserver in order to operate. If installing locally, you use <a href="https://www.apachefriends.org/index.html">XAMPP</a> or a similar application, or set up PHP, MySQL and Apache on your computer.</li>
	<li>Download or clone code from https://github.com/richs5812/chirpDatabase2.git and save in web accessible directory (this will be www folder on most web servers, or htdocs folder in XAMPP)</li>
	<li>Create a MySQL database and import existing data; or, if creating a blank database, generate tables with the following command: <code>php bin/console doctrine:schema:update --force</code>. Create and/or manage users with the instructions on https://symfony.com/doc/current/bundles/FOSUserBundle/command_line_tools.html. FYI, user management is controlled by the <a href="https://symfony.com/doc/current/bundles/FOSUserBundle/index.html">FOSUserBundle</a></li>
	<li><em>The database application is built using the PHP framework Symfony. The following instructions are adapted from the Symfony deployment directions on https://symfony.com/doc/current/deployment.html</em> Open a command line tool (e.g. Terminal on Mac, Command Prompt on Windows) and navigate to the installation folder. Run the command <code>php bin/symfony_requirements</code> to check if your server meets the requirements.</li>
	<li>Install/update vendors by running <code>composer install</code> from the command line (you will need to have <a href="https://getcomposer.org/">Composer</a> installed). The command line will prompt you to enter the MySQL database credentials during this step, as well as email account information (email account needed for user password retrieval). Either get the currently configured email address from Rich (richs5812@gmail.com), or follow Symfony instructions for setting up emails: https://symfony.com/doc/current/email.html. Symfony recommends using a gmail account; instructions here: https://symfony.com/doc/current/email/gmail.html.</li>
	<li>Clear Symfony cache with the command <code>php bin/console cache:clear --env=prod --no-debug</code></li>
</ol>

That should be it! You can access the home page by opening a web browser and going to (your installation directory)/chirpdatabase2/web

Other info:

<ul>
	<li>You can generate dummy data to test the application; this is managed by <a href="https://github.com/hautelook/AliceBundle#documentation">AliceBundle</a>. The <code>php bin/console hautelook_alice:doctrine:fixtures:load</code> command will generate the data, but <strong>note</strong> that this will clear all existing data in the database including users. The .yml files in src/AppBundle/DataFixtures/ORM control the dummy data, aka 'data fixtures'. See <a href="https://github.com/fzaninotto/Faker">Faker</a> to understand how these work.</li>
</ul>

my test local commit
