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
- 📎 **File Attachments** - Upload and download multiple files (images, PDFs, documents, up to 10MB each)
- 💬 **Comments System** - Real-time conversation thread within tickets with internal notes for agents
- 📈 **Interactive Charts** - Beautiful data visualizations using Chart.js (pie, bar, doughnut charts)
- 🌍 **Multi-Device Support** - Works across all devices and networks (via ngrok)
- 🕐 **Indian Standard Time (IST)** - All timestamps in Asia/Kolkata timezone
- 👤 **Enhanced Profile Management** - User profile editing with statistics and password change
- 📱 **Responsive Design** - Mobile-friendly Bootstrap 5 interface
- 🔍 **Advanced Search & Filters** - Search tickets by status, priority, keywords
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

<img width="1920" height="1022" alt="Screenshot (228)" src="https://github.com/user-attachments/assets/e9234d46-d111-40b8-9a57-6771e5055280" />

- Customer Dashboard: View all personal tickets

<img width="1920" height="1080" alt="image" src="https://github.com/user-attachments/assets/5b5bb24c-9f4d-4cae-ad4b-0c7d77287b95" />

- Agent Dashboard: Manage assigned tickets with SLA tracking

<img width="1920" height="1024" alt="Screenshot (227)" src="https://github.com/user-attachments/assets/c3cf5286-62d2-426f-b0d6-e4da4e350294" />

- Admin Dashboard: System-wide overview with statistics

### Ticket Management

<img width="1920" height="1080" alt="image" src="https://github.com/user-attachments/assets/85b78cf0-4797-441d-b216-1de8006d8ca6" />

- Create Ticket Form: Simple and intuitive

  <img width="1920" height="1080" alt="image" src="https://github.com/user-attachments/assets/cf7b272b-5fc8-41e6-b742-de160a267580" />
  <img width="1920" height="1080" alt="image" src="https://github.com/user-attachments/assets/c34cb72c-4b4f-480d-90f4-3b339ac27f62" />

- Ticket Details: Complete information with history
- Status Updates: Real-time tracking

### Reports

<img width="1920" height="1080" alt="image" src="https://github.com/user-attachments/assets/5d2191ef-46d1-4cde-89b7-4c4096178922" />
<img width="1920" height="1080" alt="image" src="https://github.com/user-attachments/assets/85aa8438-e52b-485b-99c5-9d170afb80ba" />

- SLA Compliance Report: Track on-time resolutions

<img width="1920" height="1080" alt="image" src="https://github.com/user-attachments/assets/a0313425-60b8-4296-b06f-41221ea1972d" />

- Agent Performance Report: Evaluate team efficiency

---
<img width="1920" height="1080" alt="image" src="https://github.com/user-attachments/assets/6035f29d-34cd-447c-b82d-e3bb59d00a9a" />
<img width="1920" height="1080" alt="image" src="https://github.com/user-attachments/assets/2508f856-8b71-40bf-957b-ccfccb2c163e" />
<img width="1920" height="1080" alt="image" src="https://github.com/user-attachments/assets/0d4625f1-e417-40de-a5e1-1701349f13ce" />
<img width="1920" height="1080" alt="image" src="https://github.com/user-attachments/assets/3276f56e-2b76-4bdb-b97e-919ee6945225" />
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
---
**⭐ If you find this project helpful, please give it a star!**

Made with ❤️ by Rutvi K. Khatariya
