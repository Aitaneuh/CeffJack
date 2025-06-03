# ♠️ CeffJack – A Laravel Blackjack Game

**CeffJack** is a modern and full-featured Blackjack game built with **Laravel**, showcasing a betting system, live statistics, profit calculation, and a leaderboard. It was developed as a personal learning project in web development and backend logic, with a focus on real-world functionality and database-driven features.

---

## 🚀 Features

- 🃏 **Fully Functional Blackjack Engine** – Random card drawing, bust logic, blackjack recognition, dealer rules
- 💸 **Betting System** – Players can place bets, earn payouts, and see their profit
- 📈 **Player Statistics** – Track total games, win rate, bet average, and profit
- 🏆 **Leaderboard** – Displays the top players ranked by profit and win count
- 🔐 **Admin Dashboard** – Manage players and view global stats
- 📊 **Daily Game Seeder** – Simulate thousands of games with realistic outcomes for testing and charts
- 🌙 **Dark Mode Ready** – Clean and modern UI with TailwindCSS and Blade components

---

## 🧰 Tech Stack

- **Laravel 10+**
- **PHP 8.2+**
- **Blade (Laravel Templating)**
- **TailwindCSS**
- **Seeder for game data simulation**

---

## ⚙️ Installation

```bash
# Clone the repository
git clone https://github.com/<your-username>/ceffjack.git
cd ceffjack

# Install dependencies
composer install
npm install && npm run dev

# Create and configure your .env file
cp .env.example .env
php artisan key:generate

# Set up your database connection in .env, then:
php artisan migrate --seed

# Run the app
php artisan serve
````

---

## 🌐 Live Demo (if hosted)

[https://ceffjack.up.railway.app](https://ceffjack.up.railway.app)
*Note: App may take a few seconds to start on free hosting.*

---

## 🤝 Contributing

This project was built for educational purposes and self-improvement, but feel free to fork it or open issues if you'd like to contribute or adapt it.

---

## 📚 Credits

Created by [Aitaneuh](https://github.com/Aitaneuh) – Apprentice developer at CEFF Industrie, Switzerland 🇨🇭
Inspired by the logic of real Blackjack rules and Laravel best practices.
