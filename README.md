# Extension HRIS - Human Resource Information System

A local web-based HR management system built with Laravel. This system tracks user documents, signatures, and roles (Admin, HR, Employee).

---

## 🌐 How to Access the Website

### Requirements:
- PHP >= 8.1
- Composer
- MySQL
- Laravel
- XAMPP (optional)

### Steps:

1. **Clone or Download the Project**

If you haven't cloned it yet:

```bash
git clone https://github.com/morenajoy2/extension-hris.git
cd extension-hris
```

2. **Install Dependencies**
```bash
composer install
npm install
```

3. **Create .env File**
```bash
cp .env.example .env
```

4. **Generate App Key**
```bash
php artisan key:generate
```

5. **Set Up Database**
- Open .env and configure your DB settings:
```bash
APP_NAME=HRIS

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=extension-hris
DB_USERNAME=root
DB_PASSWORD=
```

- Run migrations with seeder:
```bash
php artisan migrate --seed
```

6. **Run the App**
```bash
php artisan serve
```
or
```bash
composer run dev
```


7. **Then go to your browser and visit:**
```bash
http://127.0.0.1:8000
```

## 📌 Key Features:
📁 Document Tracker per user

✍️ Signature upload, view, and delete

👤 User profile management with photo support

🔐 Role-based access (Admin / HR / Employee)

📄 Requirement management with or without signature

## 🛠 Developer Notes
- Uploads are stored in: storage/app/public/{user_id}/requirements/
- Profile photos stored in: storage/app/public/profile_photos/
- Be sure to run:
```bash
php artisan storage:link
```
to make uploaded files accessible via browser.
