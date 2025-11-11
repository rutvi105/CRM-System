# 🎫 CRM System for IT Agencies

A comprehensive Customer Relationship Management (CRM) system built with Laravel for managing customer support tickets with SLA tracking, role-based access control, and automated email notifications.

---

## ✨ Features

### Core Functionality
- ✅ **User Authentication** - Secure login, registration, and password reset
- ✅ **Role-Based Access Control (RBAC)** - Customer, Agent, and Admin roles
- ✅ **Ticket Management System** - Complete CRUD operations for support tickets
- ✅ **SLA Management** - Three-tier service packages (Basic, Gold, Ultimate)
- ✅ **Status Tracking** - Open, In Progress, Pending, Resolved, Closed
- ✅ **Priority Management** - Low, Medium, High priority levels
- ✅ **Ticket Assignment** - Automatic and manual assignment to support agents
- ✅ **Complete Audit Trail** - Full history tracking for all ticket changes
- ✅ **Activity Logging** - Comprehensive system activity monitoring

### Advanced Features
- 📧 **Email Notifications** - Automated emails for status updates and assignments
- 📊 **Reports & Analytics** - SLA compliance and agent performance reports
- 🌍 **Multi-Device Support** - Works across all devices and networks (via ngrok)
- 🕐 **Indian Standard Time (IST)** - All timestamps in Asia/Kolkata timezone
- 👤 **Enhanced Profile Management** - User profile editing with statistics
- 📱 **Responsive Design** - Mobile-friendly Bootstrap 5 interface
- 🔍 **Advanced Search & Filters** - Search tickets by status, priority, keywords
- 📈 **Dashboard Analytics** - Role-specific dashboards with key metrics
- 💾 **CSV Export** - Export reports for external analysis

---

## 🛠️ Technology Stack

### Backend
- **Framework:** Laravel 12.36.1
- **Language:** PHP 8.2.12
- **Architecture:** MVC (Model-View-Controller)
- **ORM:** Eloquent
- **Authentication:** Laravel Breeze

### Frontend
- **HTML5, CSS3, JavaScript**
- **Bootstrap 5.3.0** - Responsive UI framework
- **Bootstrap Icons** - Icon library
- **Blade Templating Engine** - Laravel's templating system

### Database
- **MySQL 8.0** - Relational database management system

### Email Service
- **Gmail SMTP** - Email notifications delivery

### Development Tools
- **Composer** - PHP dependency manager
- **NPM** - Node package manager
- **VS Code** - Code editor
- **XAMPP** - Local development server
- **ngrok** - Public URL tunneling for testing

---

## 💻 System Requirements

- PHP >= 8.2
- Composer >= 2.0
- Node.js >= 18.0
- MySQL >= 8.0
- XAMPP or similar (Apache + MySQL)
- Modern web browser (Chrome, Firefox, Edge)

---

## 🚀 Installation

### Step 1: Clone the Repository

```bash
git clone https://github.com/yourusername/crm-system.git
cd crm-system
```

### Step 2: Install Dependencies

```bash
# Install PHP dependencies
composer install

# Install Node dependencies
npm install && npm run build
```

### Step 3: Environment Setup

```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### Step 4: Configure Database

Edit `.env` file:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=crm_system
DB_USERNAME=root
DB_PASSWORD=your_password

APP_TIMEZONE=Asia/Kolkata
APP_URL=http://127.0.0.1:8000
```

Create database:
```sql
CREATE DATABASE crm_system;
```

### Step 5: Run Migrations & Seeders

```bash
php artisan migrate
php artisan db:seed
```

### Step 6: Configure Email (Gmail)

Update `.env`:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-email@gmail.com
MAIL_FROM_NAME="${APP_NAME}"
```

**Note:** Generate Gmail App Password from: https://myaccount.google.com/security

### Step 7: Start Development Server

```bash
php artisan serve
```

Visit: `http://127.0.0.1:8000`

---

## ⚙️ Configuration

### SLA Packages

The system includes three SLA packages:

| Package  | Response Time | Description |
|----------|---------------|-------------|
| Basic    | 5 days        | Standard support for regular issues |
| Gold     | 24 hours      | Priority support for important clients |
| Ultimate | 3 days        | Enhanced support with quick resolution |

### User Roles & Permissions

| Role     | Permissions |
|----------|-------------|
| Customer | Create tickets, View own tickets, Edit profile |
| Agent    | View assigned tickets, Update ticket status, Manage assigned cases |
| Admin    | Full system access, User management, Reports, System configuration |

---

## 📖 Usage

### Default Login Credentials

After running seeders, use these credentials:

**Admin Account:**
- Email: `admin@crm.com`
- Password: `password`

**Agent Account:**
- Email: `agent2@crm.com`
- Password: `password`

**Customer Account:**
- Email: `customer@crm.com`
- Password: `password`

### Creating a Ticket (Customer)

1. Login as customer
2. Navigate to Dashboard
3. Click "New Ticket" button
4. Fill in title, description, and priority
5. Submit ticket
6. Track ticket status from dashboard

### Managing Tickets (Agent)

1. Login as agent
2. View assigned tickets on dashboard
3. Click "Manage" to update status
4. Add comments or update priority
5. Mark as resolved when completed

### Admin Features

1. **User Management:** Create, edit, delete users
2. **Ticket Assignment:** Assign tickets to agents
3. **Reports:** View SLA compliance and agent performance
4. **Activity Logs:** Monitor all system activities

---

## 👥 User Roles

### Customer
- Create support tickets
- View ticket status and history
- Receive email notifications on updates
- Edit personal profile

### Agent
- View assigned tickets
- Update ticket status (Open, In Progress, Pending, Resolved, Closed)
- Add pending reasons
- Manage workload efficiently
- Receive email notifications for new assignments

### Admin
- Complete system access
- User management (Create, Read, Update, Delete)
- Ticket assignment to agents
- View comprehensive reports
- Monitor system activity logs
- Export data to CSV
- System configuration

---

## 📸 Screenshots

### Dashboard Views

<img width="1920" height="1080" alt="image" src="https://github.com/user-attachments/assets/58e4e8f1-ea98-4daf-a6df-b0f1a1b16a3d" />

- Customer Dashboard: View all personal tickets

<img width="1920" height="1080" alt="image" src="https://github.com/user-attachments/assets/5b5bb24c-9f4d-4cae-ad4b-0c7d77287b95" />

- Agent Dashboard: Manage assigned tickets with SLA tracking

<img width="1920" height="1080" alt="Screenshot (210)" src="https://github.com/user-attachments/assets/58f6350d-8e2b-4b56-b650-506a75ef22ee" />

- Admin Dashboard: System-wide overview with statistics

### Ticket Management
- Create Ticket Form: Simple and intuitive
- Ticket Details: Complete information with history
- Status Updates: Real-time tracking

### Reports
- SLA Compliance Report: Track on-time resolutions
- Agent Performance Report: Evaluate team efficiency

---

## 🗄️ Database Schema

### Main Tables

**users**
- id, name, email, password, role, package_type, created_at, updated_at

**tickets**
- id, user_id, title, description, status, priority, assigned_to, sla_due_at, resolved_at, closed_at, pending_reason, created_at, updated_at

**ticket_history**
- id, ticket_id, changed_by, action, old_value, new_value, created_at

**activity_logs**
- id, user_id, action, ip_address, user_agent, details, created_at

### Relationships

- User has many Tickets (as creator)
- User has many Tickets (as assigned agent)
- Ticket belongs to User
- Ticket has many TicketHistory entries
- User has many ActivityLogs

---

## 🧪 Testing

### Manual Testing Checklist

**Customer Flow:**
- [ ] Register new account
- [ ] Login successfully
- [ ] Create ticket
- [ ] View ticket details
- [ ] Receive status update email
- [ ] Edit profile
- [ ] Change password
- [ ] Logout

**Agent Flow:**
- [ ] Login as agent
- [ ] View assigned tickets
- [ ] Update ticket status
- [ ] Add pending reason
- [ ] Mark ticket as resolved
- [ ] Receive assignment email

**Admin Flow:**
- [ ] Login as admin
- [ ] Create new user
- [ ] Edit existing user
- [ ] Assign ticket to agent
- [ ] View SLA Compliance Report
- [ ] View Agent Performance Report
- [ ] Check Activity Logs
- [ ] Export CSV report

### Running Tests

```bash
# Run PHPUnit tests
php artisan test

# Run specific test
php artisan test --filter TicketTest
```

---

## 🌐 Deployment

### Using ngrok (Development/Testing)

```bash
# Install ngrok
# Download from: https://ngrok.com/download

# Authenticate
ngrok config add-authtoken YOUR_TOKEN

# Start tunnel
ngrok http 8000

# Update .env
APP_URL=https://your-ngrok-url.ngrok-free.app
```

### Production Deployment

**Recommended Hosting:**
- DigitalOcean
- AWS
- Heroku
- Railway.app

**Deployment Steps:**
1. Upload files to server
2. Configure `.env` with production settings
3. Run migrations: `php artisan migrate --force`
4. Set permissions: `chmod -R 755 storage bootstrap/cache`
5. Configure web server (Apache/Nginx)

---

## 🤝 Contributing

Contributions are welcome! Please follow these steps:

1. Fork the repository
2. Create feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to branch (`git push origin feature/AmazingFeature`)
5. Open Pull Request

---

## 📄 License

This project is licensed under the MIT License - see the LICENSE file for details.

---

## 📞 Contact

**Developer:** Rutvi K. Khatariya  
**Email:** your-email@example.com  
**College:** College of Agricultural Information Technology, Anand Agricultural University  
**Project Guide:** Dr. X.U. Shukla

**Project Link:** [https://github.com/yourusername/crm-system](https://github.com/yourusername/crm-system)

---

## 🙏 Acknowledgments

- Laravel Documentation
- Bootstrap Documentation
- Stack Overflow Community
- Dr. X.U. Shukla (Project Guide)
- College of Agricultural Information Technology

---

## 📝 Project Information

**Course:** PRJT 411  
**Semester:** 7th  
**Enrollment No:** 3060822014  
**Submission Date:** November 14, 2025  
**Institution:** Anand Agricultural University, Anand

---

**⭐ If you find this project helpful, please give it a star!**

Made with ❤️ by Rutvi K. Khatariya
