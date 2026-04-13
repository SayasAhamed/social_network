# 🌐 Social Hub – PHP Social Networking Platform

A fully functional **social media web application** built using **PHP, MySQL, HTML, CSS, and JavaScript**, featuring real-time messaging, user interaction, and a complete admin moderation system.

---

## 📌 Overview

**Social Hub** is a full-stack social networking platform where users can:

* Connect and interact
* Share posts (text + images)
* Comment and engage
* Chat in real-time
* Manage profiles
* Report issues

Admins can monitor and control the entire system via a dedicated dashboard.

---

## 🔥 Core Features

### 🔐 Authentication System

* User registration & login
* Session-based authentication
* Password recovery using security questions
* **Admin auto-detection & redirect system**

---

### 👤 Profile System

* Profile & cover image upload
* Bio and personal details
* Edit profile
* Public user profiles

---

### 📝 Post System

* Create posts (text + image)
* Edit & delete posts
* Timeline feed
* Single post view

---

### 💬 Comment System

* Add comments
* View comments per post
* Linked with users

---

### 📩 Messaging System

* One-to-one chat
* Send & receive messages
* Dynamic message loading

---

### 🔍 Search System

* Search users
* Search posts

---

### 📄 Pagination

* Optimized feed loading

---

### 🚨 Report System

* Report users/issues
* Admin moderation system

---

## 👑 Admin Panel

A powerful backend dashboard for full platform control:

* 👥 Manage users
* 📝 Moderate posts
* 💬 Control comments
* 🚨 Handle reports
* ⚙️ Dashboard overview

---

## 🔐 Default Login Credentials

### 👑 Admin Account

* **Email:** [admin@example.com](mailto:admin@example.com)
* **Password:** admin123

👉 Logging in with this account will **automatically redirect to the Admin Dashboard**

---

### 👤 User

* Register a new account from signup page

---

## 🛠️ Tech Stack

* 🧠 PHP (Backend)
* 🗄️ MySQL (Database)
* 🎨 HTML5 / CSS3
* ⚙️ JavaScript / jQuery
* 🧩 Bootstrap

---

## 📁 Project Structure

```bash id="z4qg8n"
social_network/
│
├── admin/                 # Admin Dashboard
├── functions/             # Core backend logic
├── includes/              # DB connection & shared components
├── users/                 # Profile images
├── imagepost/             # Post images
├── cover/                 # Cover photos
├── report_image/          # Report uploads
├── images/                # UI assets
│
├── home.php               # Feed
├── profile.php            # Profile page
├── user_profile.php       # Public profile
├── messages.php           # Chat system
│
├── login.php / signup.php
├── logout.php
├── forgot_password.php
├── change_password.php
│
├── single.php             # Single post
├── members.php            # User search
├── results.php
├── main.php
│
└── MySQL Database/
    └── social_network.sql.gz
```

---

## ⚙️ Installation & Setup

### 1️⃣ Clone Repository

```bash id="4qj0d3"
git clone https://github.com/yourusername/social-hub.git
```

---

### 2️⃣ Move to XAMPP

```id="w5jvse"
C:\xampp\htdocs\
```

---

### 3️⃣ Setup Database

1. Open **phpMyAdmin**
2. Create a new database:

```
social_network
```

3. Import the file:

```id="c1o7fj"
MySQL Database/social_network.sql.gz
```

---

### 4️⃣ Configure Database Connection

Edit:

```id="rq7j0r"
includes/connection.php
```

```php id="7f1l3z"
$conn = mysqli_connect("localhost","root","","social_network");
```

---

### 5️⃣ Run the Project

Open in browser:

```id="3vhlfp"
http://localhost/social_network/
```

---

## ⚡ System Workflow

```bash id="9c6x9r"
User → Register/Login
      ↓
Create Post → Feed → Comment
      ↓
Messaging System → Interaction
      ↓
Report Issues → Admin Dashboard → Moderation
```

---

## 📸 Screenshots

> Add:

* Login page
* Home feed
* Profile page
* Chat interface
* Admin dashboard

---

## ⚠️ Known Issues

* ❌ SQL injection risks (some raw queries)
* ❌ No CSRF protection
* ❌ File upload validation missing
* ❌ Weak delete handling (GET-based)

---

## 🚧 Future Improvements

* 🔐 Use prepared statements everywhere
* 🔑 bcrypt password hashing
* 📡 Convert to REST API
* 🔔 Real-time notifications
* 📱 Mobile responsive UI
* ☁️ Cloud deployment

---

## 👨‍💻 Author

**M.M. Sayas Ahamed**
🎓 BICT Undergraduate
💻 Full Stack Developer
🎥 Tech Creator

---

## ⭐ Support

* ⭐ Star the repository
* 🍴 Fork it
* 🛠️ Contribute

---

## 📄 License

Educational use only
