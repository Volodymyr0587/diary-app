# 📝 Personal Diary App

A modern personal diary application built with **Laravel 13**, **Livewire 4**, and **Tailwind CSS**.  
Track your thoughts, moods, and daily reflections in a clean and interactive UI.

---

## ✨ Features

- 🧠 Create, edit, and delete diary entries
- 😊 Mood tracking (happy, sad, neutral, etc.)
- 📊 Mood statistics & insights
- 🔍 Search entries by title and content
- 🏷️ Filter entries by mood
- 🔐 Authorization (users can manage only their own entries)
- ⚡ Reactive UI powered by Livewire
- 🎨 Clean UI with Tailwind CSS

---

## 🛠️ Tech Stack

- **Backend:** Laravel 13
- **Frontend:** Livewire 4, Tailwind CSS
- **Database:** SQLite / MySQL / PostgreSQL
- **Auth:** Laravel built-in authentication
- **Other:**
    - Eloquent ORM
    - Policies for authorization
    - Custom validation rules

---

## 🚀 Installation

### 1. Clone the repository

```bash
git clone https://github.com/Volodymyr0587/diary-app.git
cd diary-app
composer install
npm install
```

### 3. Setup environment

```bash
cp .env.example .env
php artisan key:generate
```

### 4. Configure database

In your .env file:

```
DB_CONNECTION=sqlite
```

Or for MySQL:

```
DB_CONNECTION=mysql
DB_DATABASE=your_db
DB_USERNAME=root
DB_PASSWORD=
```

### 5. Run migrations

```bash
php artisan migrate
```

### 6. Run the app

```bash
composer run dev
```

Or manually:

```bash
php artisan serve
npm run dev
```

## 🧠 Mood System

Each entry can have a mood assigned:

- happy
- neutral
- sad
  (extendable)

The app calculates:

- Most frequent mood
- Mood percentages
- Simple insights like:
    - "You're on a wave of positivity 😄"
    - "Things seem a bit tough lately"

## 🔐 Authorization

- Uses Laravel Policies
- Users can:
    - View only their own entries
    - Update/delete only their own entries

## 🤝 Contributing

Pull requests are welcome!

If you want to improve something:

1. Fork the repo
2. Create a feature branch
3. Commit your changes
4. Open a PR

## 📜 License

Personal tool. Use, modify, and adapt freely.
