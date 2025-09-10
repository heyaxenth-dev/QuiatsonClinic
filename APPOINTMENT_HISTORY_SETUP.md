# Appointment History Feature Setup

## Overview

This feature allows users to reuse their previously entered appointment data, saving time when making multiple appointments.

## Database Setup

### 1. Create the Appointment History Table

Run the following SQL script in your database:

```sql
-- Execute the contents of database/add_appointment_history.sql
CREATE TABLE IF NOT EXISTS `appointment_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `lastname` varchar(50) DEFAULT NULL,
  `firstname` varchar(50) DEFAULT NULL,
  `middle_initial` char(1) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `sex` enum('Male','Female') DEFAULT NULL,
  `birthdate` date DEFAULT NULL,
  `civil_status` varchar(20) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `weight` varchar(10) DEFAULT NULL,
  `height` varchar(10) DEFAULT NULL,
  `bloodtype` varchar(3) DEFAULT NULL,
  `patient_type` varchar(50) DEFAULT NULL,
  `symptom` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `appointment_history_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `client` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
```

## Features

### 1. **Automatic History Detection**

- When users visit the appointment form, the system automatically checks for previous appointment data
- If history exists, a notification appears with options to preview or use the data

### 2. **Preview Functionality**

- Users can preview their previous data in a detailed modal before deciding to use it
- Shows all personal and medical information from the last appointment

### 3. **One-Click Data Population**

- Users can instantly populate the form with their previous data
- All fields are automatically filled including patient type and symptoms

### 4. **Smart Form Management**

- Added "Clear Form" button to reset all fields
- Form validation works seamlessly with populated data
- Senior/PWD ID upload section is properly handled

### 5. **Data Persistence**

- Every successful appointment automatically saves data to history
- History is updated with the latest information on each appointment
- Only one history record per user (latest data is always preserved)

## User Experience

### For New Users:

- No history alert appears
- Form works normally

### For Returning Users:

1. **History Alert Appears**: Blue notification bar with previous data info
2. **Preview Option**: Click "Preview" to see detailed previous data
3. **Use Previous Data**: Click "Use Previous Data" to instantly populate form
4. **Clear Form**: Option to clear all data and start fresh

## Technical Implementation

### Files Modified/Created:

1. `database/add_appointment_history.sql` - Database table creation
2. `client/get_appointment_history.php` - API endpoint for fetching history
3. `client/appointment.php` - Enhanced with history UI and JavaScript
4. `client/code.php` - Updated to save appointment history

### Key Features:

- **Real-time History Check**: Automatic detection on page load
- **Modal Preview**: Detailed view of previous data
- **Form Population**: Instant data filling with validation
- **Error Handling**: Graceful fallbacks for network issues
- **User Notifications**: Success/error messages for user feedback

## Benefits

1. **Time Saving**: Users don't need to re-enter the same information
2. **User Experience**: Seamless and intuitive interface
3. **Data Accuracy**: Reduces input errors by reusing verified data
4. **Convenience**: Especially helpful for regular patients
5. **Flexibility**: Users can still modify or clear data as needed

## Security & Privacy

- History is tied to user accounts (user_id)
- Data is automatically deleted when user account is deleted (CASCADE)
- Only the most recent appointment data is stored
- No sensitive information like uploaded files are stored in history
