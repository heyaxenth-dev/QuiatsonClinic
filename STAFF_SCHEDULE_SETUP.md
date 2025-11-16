# Staff Schedule Management Setup Guide

This document explains how to set up and use the staff schedule management feature that allows staff to mark dates as unavailable, preventing clients from booking appointments on those dates.

## Database Setup

1. Run the SQL script to create the `staff_schedules` table:
   ```sql
   -- Execute the SQL file
   database/add_staff_schedules.sql
   ```

   Or manually run:
   ```sql
   CREATE TABLE IF NOT EXISTS `staff_schedules` (
     `id` int(11) NOT NULL AUTO_INCREMENT,
     `staff_id` int(11) NOT NULL,
     `schedule_date` date NOT NULL,
     `start_time` time DEFAULT NULL,
     `end_time` time DEFAULT NULL,
     `is_unavailable` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1 = unavailable, 0 = available',
     `reason` varchar(255) DEFAULT NULL,
     `created_by` int(11) NOT NULL,
     `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
     `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
     PRIMARY KEY (`id`),
     KEY `staff_id` (`staff_id`),
     KEY `schedule_date` (`schedule_date`),
     KEY `is_unavailable` (`is_unavailable`)
   ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
   ```

## Features

### Admin Side (Staff Schedule Management)

1. **Access the Schedule Management Page**
   - Navigate to "My Schedule" in the admin sidebar
   - URL: `admin/staff_schedules.php`

2. **Add Unavailable Dates**
   - Select a date (required)
   - Optionally specify start and end times
   - Optionally add a reason (e.g., "Vacation", "Training")
   - Click "Add" to mark the date as unavailable

3. **View Unavailable Dates**
   - Calendar view shows unavailable dates highlighted in red
   - List view shows all unavailable dates with details
   - Filter by month using the month selector

4. **Delete Unavailable Dates**
   - Click the "Delete" button next to any unavailable date
   - Confirm deletion to make the date available again

### Client Side (Appointment Booking)

1. **Automatic Date Blocking**
   - When clients try to book an appointment, dates marked as unavailable by staff are automatically blocked
   - The date picker will show an alert if an unavailable date is selected
   - Unavailable dates are fetched from `get_fully_booked_dates.php` which includes both:
     - Dates marked as unavailable by staff
     - Dates that are fully booked (all slots taken)

2. **Slot Availability**
   - When a date is marked as unavailable, no time slots will be shown for that date
   - The system checks staff schedules before showing available slots

## API Endpoints

### Schedule API (`admin/api/schedule_api.php`)

- **GET** `?action=get&staff_id={id}` - Get all schedules for a staff member
- **GET** `?action=get&staff_id={id}&year={year}&month={month}` - Get schedules for a specific month
- **POST** `action=add` - Add a new unavailable date
  - Required fields: `staff_id`, `schedule_date`, `created_by`
  - Optional fields: `start_time`, `end_time`, `reason`
- **POST** `action=delete&id={schedule_id}` - Delete a schedule

## Files Modified/Created

### New Files:
- `database/add_staff_schedules.sql` - Database table creation script
- `admin/staff_schedules.php` - Staff schedule management page
- `admin/api/schedule_api.php` - API endpoints for schedule CRUD operations

### Modified Files:
- `admin/utils/sidebar.php` - Added "My Schedule" menu item
- `admin/get_available_slots.php` - Added check for staff unavailable dates
- `client/get_available_slots.php` - Added check for staff unavailable dates
- `client/get_fully_booked_dates.php` - Added staff unavailable dates to the list
- `client/assets/js/datepicker.js` - Added validation for unavailable dates

## How It Works

1. **Staff marks a date as unavailable:**
   - Staff logs into admin panel
   - Navigates to "My Schedule"
   - Adds a date with optional time range and reason
   - The date is stored in `staff_schedules` table

2. **Client tries to book on unavailable date:**
   - Client selects a date in the appointment form
   - System checks if date is in `staff_schedules` with `is_unavailable = 1`
   - If unavailable, no time slots are shown
   - Client sees message: "This date is unavailable or all slots are fully booked"

3. **Date availability check:**
   - `get_fully_booked_dates.php` returns all unavailable dates (staff schedules + fully booked)
   - `get_available_slots.php` checks staff schedules before returning time slots
   - Both admin and client sides respect staff schedules

## Notes

- Staff schedules are checked for ALL staff members (if multiple staff mark the same date, it's still unavailable)
- Time ranges are optional - if not specified, the entire day is marked as unavailable
- Staff can only delete their own schedules (verified by `staff_id` and session `admin_id`)
- The system prevents duplicate schedules for the same date and staff member

## Troubleshooting

1. **Dates not showing as unavailable:**
   - Check if the `staff_schedules` table exists
   - Verify the date format matches (YYYY-MM-DD)
   - Check database connection

2. **Can't delete schedule:**
   - Ensure you're logged in as the staff member who created the schedule
   - Check that the schedule ID exists

3. **Client can still select unavailable dates:**
   - Clear browser cache
   - Check that `get_fully_booked_dates.php` is returning the correct dates
   - Verify JavaScript is enabled in the browser

