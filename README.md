# ShareX Uploader
Created by scripthead.

## What is it?
This is a lightweight php upload script for uploading files to a web server. This is probably as lightweight as it is going to get. Simply drop the file in, do some light configuration, configure ShareX to upload to it, and off you go.

## Configuring the uploader itself
Open up the upload.php file, or whatever you named it to. On the 2nd line of the file there is a line called ``$secret_key``, change this. Preferably make it completely random. This is the key used to authorize uploads.

Next we have to look at the path variables:
``$domain_url `` is the url path to the files.

``$folder_destination`` is the path to the files on the file system.
When it comes to folder destination, leaving it blank will cause it to put uploaded files in the same folder as the upload script.

#### Scenario One
If your upload file is in the root directory folder, meaning it's accessible from the web at https://example.com or https://subdomain.example.com, then you do not need to change the ``$domain_url``. ``$_SERVER['HTTP_HOST']`` grabs the domain and subdomain (if you are on one) of the url you used to upload the image, and will automatically use that domain. 

Uploader path: https://example.com/upload.php
Path where files are accessible: https://example.com/file.ext
Filesystem uploader path: `/var/www/html/upload.php`
Filesystem file storage path: `/var/www/html`
Configuration:
``$domain_url = "https://".$_SERVER["HTTP_HOST"];`` 
``$folder_destination = '';``

#### Scenario Two

If for whatever reason the upload url domain that you use to upload files is different from the one you want people to use to access the files and images from, replace the ``$domain_url`` with ``"https://whateverurlyou.want"``. Do not leave a trailing forward slash or the url returned will become ``https://whateverurlyou.want//name.ext``

Uploader path: https://example.com/upload.php
Path where files are accessible: https://files.example.com/file.ext
Filesystem uploader path: `/var/www/html/upload.php`
Filesystem file storage path: `/var/www/files`
Configuration:
``$domain_url = "https://files.example.com";`` 
``$folder_destination = '/var/www/files/';``

#### Scenario Three
If you do not want your files in the root directory of the domain / subdomain, simply modify the ``$folder_destination`` variable. If for instance your upload file is in the root directory of the domain, and can be accessed from ``https://example.com/upload.php`` but you want your files accessed like ``https://example.com/files/file.ext``, simply put ``files/`` in the ``$folder_destination`` and add ``."/files"`` after the domain string. Make sure you include the trailing slash or else your files will upload at ``https://example.com/filesfile.ext``.


Example:

Uploader path: https://example.com/upload.php
Path where files are accessible: https://example.com/files/file.ext
Filesystem uploader path: `/var/www/html/upload.php`
Filesystem file storage path: `/var/www/html/files`
Configuration:
``$domain_url = "https://".$_SERVER["HTTP_HOST"]."/files";`` 
``$folder_destination = "files/";``

#### Scenario Four
Your domain you use to upload is different from the one you share, and on top of that the files are not in the root directory of the shared domain. On the file system, the uploader is located at ``/var/www/html/uploader.php``, but the filesystem path of the file system is located at ``/var/www/fileserver/bruh``.

Example:

Uploader path: https://127.0.0.1/uploader.php
Path where files are accessible: https://fucking.fuck-o.ff/bruh/file.ext
Filesystem uploader path: `/var/www/local/uploader.php`
Filesystem file storage path: `/var/www/fuckingfuckoff/bruh`
Configuration:
``$domain_url = "https://fucking.fuck-o.ff/bruh";``
``$folder_destination = '/var/www/fuckingfuckoff/bruh/';``

## Configuring ShareX

After you have configured the upload script it is time to configure the upload script. Simply the steps below:

For your after capture tasks, make sure the only thing ticked is upload image to host:
![](https://i.imgur.com/SKQrihC.png)

Next, for after upload tasks, make sure only copy url to clipboard is ticked:
![](https://i.imgur.com/uqZwnKt.png)

Next, in destinations, set your image uploader and file uploader to "Custom image uploader"
![](https://i.imgur.com/EjOEnLC.png)
After that click on custom uploader settings. Go to import and import the sxcu provided in the repository from the file.
![](https://i.imgur.com/apxR2pz.png)

You should see something like this:

![](https://i.imgur.com/gi9k4vs.png)
Set your url to the url of your upload script, and set the secret to the key you set in the uploader script.

After that, if it didn't automatically do this set the image and file uploader to custom uploader and press "test". If it worked, then everything was setup right. If you are receiving a failed upload error, look below.

## Upload failed error

If you receive a message stating ``File upload failed - The maximum file size limit is``... etc, there are a few things that could of happened. If the size of your file is larger than the maximum post size or upload file size, then you need to increase the limit in your php config.

Go into the config (on my install it was located in ``/etc/php/7.4/apache2`` and find the ``post_max_size`` variable, set it to something like `128M`. Then go to the `upload_max_filesize ` variable and set it to `128M` as well or whatever you want, just as longer as it's bigger than what you are trying to or planning to upload in the future. After this, restart apache, nginx, or whatever web server you are running.

If you are still getting the error then the next thing that could of gone wrong is that there are permission issues. On ubuntu (which is what I use) you need to make sure that the user apache is running on can write to the folder. You can give ownership to the apache user by running ``chown -R www-data:www-data /var/www``. This grants ownership to the apache user to everything in the www-data folder, which can sometimes get messed up if you modify the folder with other users. On other linux distros the apache user might be ``apache2``.
