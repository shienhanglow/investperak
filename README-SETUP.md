# investperak.com – website and CMS setup (cPanel)

The website reads all its text, lists and photos from your MySQL database. You edit everything at **investperak.com/admin**, with English and 中文 side by side.

Requirements: PHP 8.0 or newer and MySQL or MariaDB. Any normal cPanel plan has these.

## 1. Back up the current site
1. In cPanel, open **File Manager**.
2. Open **public_html**, select everything in it, and choose **Compress**.
3. Download the zip so you can restore the old site if needed.
4. Then move the old files into a folder such as `public_html/old-site`.

## 2. Create the database
1. In cPanel, open **MySQL® Databases**.
2. Under **Create New Database**, create a database, e.g. `investperak`. cPanel adds a prefix, giving something like `cpuser_investperak`.
3. Under **MySQL Users**, add a new user with a strong password.
4. Under **Add User To Database**, pick that user and database, tick **ALL PRIVILEGES**, and save.

## 3. Upload the files
1. In File Manager, open **public_html** and **Upload** `investperak-cms.zip`.
2. Right-click the zip and choose **Extract**.
3. Check that `index.php`, `admin`, `assets`, `inc` and `uploads` now sit directly inside `public_html`. If they are inside an extra folder, move them up one level.
4. Set the **uploads** folder permissions to **755**. Right-click it and choose **Change Permissions**.

## 4. Connect the database
1. Copy `config.sample.php` and name the copy **`config.php`**.
2. Edit `config.php` and fill in:
   - `db_name`, `db_user` and `db_pass` from step 2.
   - `notify_email`: where new enquiries are emailed. It is already set to low.alphront@gmail.com.
   - `mail_from`: a mailbox on your domain. Create `no-reply@investperak.com` in cPanel **Email Accounts** so the notifications are delivered reliably.

## 5. Run the installer
1. Visit **https://investperak.com/install.php**.
2. Choose your admin username and a password of 10 or more characters, then click **Install**.
3. **Delete `install.php`** in File Manager straight away.

## 6. Sign in and edit
Go to **https://investperak.com/admin** and sign in.

| To change… | Go to |
|---|---|
| Phone, WhatsApp, email, address, company number | Site settings → Contact details |
| Home page headline and buttons | Home → Page text |
| Key figures, sectors, process steps | Home → the matching list |
| Regulations for foreigners | Investor Guide → Sections, MM2H tiers |
| Photos | Gallery → Gallery photos → + Add new (upload JPG/PNG/WebP up to 6 MB) |
| Partner firms (name, address, logo, services) | Partners → Partners |
| FAQ | FAQ → FAQ questions |
| Contact form wording and dropdown options | Contact → Page text & form |
| Website enquiries | Enquiries (also emailed to you; download as CSV) |
| Your password, or extra admin logins | Click your username at the top right |

How editing works:
- Saving makes the change live immediately.
- In every list you can add, edit, delete, hide or show entries, and use ↑ ↓ to set their order on the website.
- If a 中文 box is left empty, the English text is shown on the Chinese site.
- Write `**text**` to make words bold, for example in the Investor Guide.
- Gallery entries without a photo show a built-in illustration. Upload your own photos to replace them.

## Security notes
- The admin area locks for 15 minutes after 8 wrong passwords.
- It signs you out after 2 hours of inactivity.
- The uploads folder cannot run scripts, and `config.php` cannot be opened from a browser.
- `.htaccess` sends all visitors to HTTPS. If your SSL certificate is not active yet, activate it in cPanel **SSL/TLS Status** (AutoSSL) first. You can instead remove the three "Force HTTPS" lines in `.htaccess`.
- Back up regularly. In cPanel use **Backup**, or export the database in **phpMyAdmin** and download the `uploads` folder.

## Troubleshooting
- **"Could not connect to the database"**: check the database name, user and password in `config.php`. The cPanel prefix is part of the name.
- **Blank page or error 500**: set `'debug' => true` in `config.php`, reload the page to see the error, and set it back to `false` afterwards.
- **Photo upload fails**: the file may be larger than your hosting's upload limit. Resize it, or raise `upload_max_filesize` in cPanel **MultiPHP INI Editor**.
- **No enquiry emails**: check your spam folder, make sure `mail_from` is a real mailbox on investperak.com, and remember that every enquiry is still saved in the admin inbox.
