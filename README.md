# 🌐 Social Hub – PHP Social Networking Platform

A fully functional **social media web application** built using **PHP, MySQL, HTML, CSS, and JavaScript**, featuring real-time interactions, messaging, and a complete admin moderation system.

---

## 📌 Overview

**Social Hub** is a complete social networking platform where users can:

* Connect and interact
* Share posts and media
* Chat in real-time
* Manage profiles
* Report issues

Admins can fully control the system via a powerful dashboard.

---

## 🔥 Core Features

### 🔐 Authentication System

* User registration & login
* Secure session handling
* Password recovery with security questions

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

👉 Core logic → 

---

### 💬 Comment System

* Add comments
* View comments per post
* Linked with users

👉 Comment handling → 

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

👉 Pagination logic → 

---

### 🚨 Report System

* Report users/issues
* Admin moderation system

---

## 👑 Admin Panel

### ⚙️ Dashboard Controller

* Dynamic routing system

---

### 👥 User Management

* View, edit, delete users

---

### 📝 Post Moderation

* View posts
* Delete posts + comments → 

---

### 💬 Comment Moderation

* View & delete comments

---

### 🚨 Reports Management

* Handle user reports

---

## 🛠️ Tech Stack

* 🧠 PHP (Backend)
* 🗄️ MySQL (Database)
* 🎨 HTML5 / CSS3
* ⚙️ JavaScript / jQuery
* 🧩 Bootstrap

---

## 📁 Project Structure (Real Hierarchy)

```bash
social_network/
│
├── admin/                         # Admin Dashboard
│   ├── admin.php
│   ├── admin_users.php
│   ├── admin_posts.php
│   ├── admin_comments.php
│   ├── reports.php
│   ├── view_post.php
│   ├── view_profile.php
│   ├── view_user.php
│   ├── delete_user.php
│   └── admin_includes/
│
├── functions/                     # Core Backend Logic
│   ├── functions.php
│   ├── comments.php
│   ├── delete_post.php
│   └── pagination.php
│
├── includes/                      # DB & Shared Components
│   ├── connection.php
│   ├── header.php
│   └── contact.php
│
├── users/                         # User profile images
├── imagepost/                     # Post images
├── cover/                         # Cover photos
├── report_image/                  # Report uploads
├── images/                        # UI assets
│
├── home.php                       # Main feed
├── profile.php                    # Profile page
├── user_profile.php               # Public profile
├── messages.php                   # Chat system
├── fetch_messages.php             # Message fetch API
├── submit_message.php             # Send message
│
├── login.php / signup.php         # Auth pages
├── logout.php
├── forgot_password.php
├── change_password.php
│
├── single.php                     # Single post view
├── members.php                    # User search
├── results.php                    # Search results
├── my_post.php                    # User posts
│
└── main.php                       # Entry point
```

👉 Based on your actual hierarchy → 

---

## ⚙️ Installation & Setup

### 1️⃣ Clone

```bash
git clone https://github.com/yourusername/social-hub.git
```

---

### 2️⃣ Database

* Create DB: `social_network`
* Import SQL file

---

### 3️⃣ Config

```php
$conn = mysqli_connect("localhost","root","","social_network");
```

---

### 4️⃣ Run

```
http://localhost/social_network/
```

---

## ⚡ Workflow

```bash
User → Login/Register
      ↓
Create Post → Feed → Comment
      ↓
Chat System → Interaction
      ↓
Reports → Admin → Moderation
```

---

## ⚠️ Known Issues (Honest)

* ❌ SQL injection risks (some raw queries)
* ❌ File upload validation missing
* ❌ No CSRF protection
* ❌ Weak delete handling (GET-based) → 

---

## 🚧 Future Improvements

* 🔐 Use prepared statements everywhere
* 🔑 bcrypt password hashing
* 📡 REST API + React frontend
* 🔔 Notifications system
* 📱 Mobile UI improvements
* ☁️ Deployment (cloud hosting)

---

## 👨‍💻 Author

**M.M. Sayas Ahamed**
🎓 BICT Undergraduate
💻 Full Stack Developer
🎥 Tech Creator

---

## ⭐ Support

* ⭐ Star this repo
* 🍴 Fork it
* 🛠️ Contribute

---

## 📄 License

Educational use only
