# CSP Learning Portal
CSP Learning Portal: A Web-based Student Information and Learning Materials System

# README!!

How to run this system

Step 1: Install PHP and Composer
If you don't have PHP and Composer installed on your local machine, Install PHP, Composer, and the Laravel installer on Windows:
- Open Windows Powershell (As administrator)
- Run this: Set-ExecutionPolicy Bypass -Scope Process -Force; [System.Net.ServicePointManager]::SecurityProtocol = [System.Net.ServicePointManager]::SecurityProtocol -bor 3072; iex ((New-Object System.Net.WebClient).DownloadString('https://php.new/install/windows/8.4'))
- after installing, restart your powershell

Step 2: Install Laravel
- Open Windows Powershell (as administrator)
- Run this: composer global require laravel/installer
- wait to install the laravel, and voila! Laravel Installed on your computer

Next, is downloading the files, updated database is in database/dbupdated
- Open XAMPP APACHE and MYSQL
- Go to localhost/phpmyadmin
- Add new database named csp_learning_portal
- Import database and done

Lastly, test the system
- Open VSCode
- File > Open Folder > silms (incase you missed it, you should put the silms folder into htdocs)
- Press Ctrl + Shift + X ; then search "Laravel"; install this extensions
-> Laravel Extra Intellisense
-> Laravel goto view
-> Laravel Create view
-> Laravel Blade Wrapper
-> Laravel (Official VSCode for Laravel)
-> Laravel-goto-components
-> Laravel Blade Snippets
-> Laravel Extension Pack
-> Laravel Snippets
-> Laravel-jump-controller
-> Laravel Artisan
-> Laravel Blade Formatter
- After installing;
- Open Terminal in VSCode
- Run php artisan migrate
- next, php artisan db:seed
- lastly, php artisan serve (this will run the ipaddress and the system)

Import Github file

- git pull origin master - pulls file from github

That's All! :>

If you commit changes, use git commands, example;

"git add ." - selects changes files
"git commit -m "describe your changes" - saves updated version
"git push origin master" - uploads to github
