document.addEventListener('DOMContentLoaded', function () {
	const dateInput = document.getElementById('date');
	const timeSlotSelect = document.getElementById('time_slot');
	const slotInfo = document.getElementById('slot-info');

	// Set minimum date to today
	const today = new Date().toISOString().split('T')[0];
	dateInput.setAttribute('min', today);

	// 🔹 Fetch fully booked dates once when page loads
	fetch('get_fully_booked_dates.php')
		.then((res) => res.json())
		.then((disabledDates) => {
			dateInput.addEventListener('input', function () {
				if (disabledDates.includes(this.value)) {
					alert('All appointments are fully booked for this date.');
					this.value = ''; // reset selection
				}
			});
		});

	// Handle date change
	dateInput.addEventListener('change', function () {
		const selectedDate = this.value;

		if (selectedDate) {
			// Show loading state
			timeSlotSelect.innerHTML =
				'<option value="">Loading available slots...</option>';
			slotInfo.innerHTML =
				'<div class="text-info"><i class="bi bi-hourglass-split"></i> Loading...</div>';

			// Fetch available slots
			fetch('get_available_slots.php', {
				method: 'POST',
				headers: {
					'Content-Type': 'application/x-www-form-urlencoded',
				},
				body: 'date=' + encodeURIComponent(selectedDate),
			})
				.then((response) => response.json())
				.then((data) => {
					timeSlotSelect.innerHTML =
						'<option value="">Select Time Slot</option>';

					if (data.length === 0) {
						timeSlotSelect.innerHTML =
							'<option value="">No available slots for this date</option>';
						slotInfo.innerHTML =
							'<div class="text-warning"><i class="bi bi-exclamation-triangle"></i> All slots are fully booked for this date</div>';
					} else {
						let totalAvailable = 0;

						data.forEach((slot) => {
							const option = document.createElement('option');
							option.value = slot.time_slot;
							option.textContent = `${slot.time_slot} (${slot.available_count} slots available)`;

							// 🔹 Disable if slot is full
							if (slot.available_count <= 0) {
								option.disabled = true;
								option.textContent = `${slot.time_slot} (FULL)`;
							}

							timeSlotSelect.appendChild(option);
							totalAvailable += slot.available_count;
						});

						// Show summary
						slotInfo.innerHTML = `<div class="text-success"><i class="bi bi-check-circle"></i> ${totalAvailable} total openings available</div>`;
					}
				})
				.catch((error) => {
					console.error('Error:', error);
					timeSlotSelect.innerHTML =
						'<option value="">Error loading slots</option>';
					slotInfo.innerHTML =
						'<div class="text-danger"><i class="bi bi-x-circle"></i> Error loading available slots</div>';
				});
		} else {
			timeSlotSelect.innerHTML =
				'<option value="">Select a date first</option>';
			slotInfo.innerHTML = '';
		}
	});

	// Handle time slot selection
	timeSlotSelect.addEventListener('change', function () {
		const selectedSlot = this.value;
		if (selectedSlot) {
			const option = this.options[this.selectedIndex];
			const text = option.textContent;
			const match = text.match(/\((\d+) slots available\)/);
			if (match) {
				const availableCount = parseInt(match[1]);
				if (availableCount <= 3 && availableCount > 0) {
					slotInfo.innerHTML = `<div class="text-warning"><i class="bi bi-clock"></i> Only ${availableCount} slots left for this time!</div>`;
				}
			}
		}
	});
});
