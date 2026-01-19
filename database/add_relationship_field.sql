-- Add relationship field to appointments table
-- This field stores the relationship of the person creating the appointment to the patient
-- (e.g., Self, Spouse, Parent, Child, etc.)

ALTER TABLE `appointments` 
ADD COLUMN `relationship` VARCHAR(50) DEFAULT NULL AFTER `symptom`;

-- Add relationship field to appointment_history table
ALTER TABLE `appointment_history` 
ADD COLUMN `relationship` VARCHAR(50) DEFAULT NULL AFTER `symptom`;

