/**
 * Luxury Calendar - Interactive Booking Calendar
 * Handles date selection, time slot picking, and real-time availability
 */

class LuxuryCalendar {
    constructor(config = {}) {
        this.config = {
            containerId: 'luxury-calendar',
            startDate: null,
            endDate: null,
            selectedTimeSlot: null,
            selectedFacilityId: null,
            ...config
        };

        this.state = {
            currentMonth: new Date(),
            selectedStartDate: null,
            selectedEndDate: null,
            selectedTimeSlot: null,
            selectedFacilityId: null,
            availability: {},
            slots: [],
            facilities: [],
            loading: false,
        };

        this.init();
    }

    async init() {
        await this.loadCalendarData();
        this.render();
        this.attachEventListeners();
    }

    async loadCalendarData() {
        try {
            this.state.loading = true;
            const today = new Date();
            const endDate = new Date(today.getTime() + 90 * 24 * 60 * 60 * 1000); // 90 days ahead

            const startDateStr = this.formatDate(today);
            const endDateStr = this.formatDate(endDate);

            const response = await fetch(
                `/api/calendar?start_date=${startDateStr}&end_date=${endDateStr}`
            );
            const data = await response.json();

            this.state.facilities = data.facilities || [];
            this.state.loading = false;
        } catch (error) {
            console.error('Error loading calendar data:', error);
            this.state.loading = false;
        }
    }

    async loadAvailabilityForDate(date) {
        try {
            if (!this.state.selectedFacilityId) return;

            const dateStr = this.formatDate(date);
            const response = await fetch(
                `/api/facilities/${this.state.selectedFacilityId}/slots?date=${dateStr}`
            );
            const data = await response.json();

            this.state.slots = data.slots || [];
            this.renderTimeSlots();
        } catch (error) {
            console.error('Error loading time slots:', error);
        }
    }

    render() {
        const container = document.getElementById(this.config.containerId);
        if (!container) return;

        container.innerHTML = `
            <div class="luxury-calendar-container">
                ${this.renderCalendarHeader()}
                ${this.renderCalendarGrid()}
                ${this.renderTimeSlots()}
                ${this.renderBookingSummary()}
            </div>
        `;

        this.attachEventListeners();
    }

    renderCalendarHeader() {
        const monthYear = this.state.currentMonth.toLocaleDateString('en-US', {
            month: 'long',
            year: 'numeric'
        });

        return `
            <div class="calendar-header">
                <h2>${monthYear}</h2>
                <div class="calendar-nav">
                    <button class="btn-prev" aria-label="Previous month">←</button>
                    <button class="btn-next" aria-label="Next month">→</button>
                </div>
            </div>
        `;
    }

    renderCalendarGrid() {
        const year = this.state.currentMonth.getFullYear();
        const month = this.state.currentMonth.getMonth();

        const firstDay = new Date(year, month, 1);
        const lastDay = new Date(year, month + 1, 0);
        const daysInMonth = lastDay.getDate();
        const startingDayOfWeek = firstDay.getDay();

        let html = '<div class="calendar-grid">';

        // Weekday headers
        const weekdays = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
        weekdays.forEach(day => {
            html += `<div class="calendar-weekday">${day}</div>`;
        });

        // Previous month's days
        for (let i = startingDayOfWeek - 1; i >= 0; i--) {
            const prevDate = new Date(year, month, -i);
            html += `<div class="calendar-day outside">${prevDate.getDate()}</div>`;
        }

        // Current month's days
        const today = new Date();
        today.setHours(0, 0, 0, 0);

        for (let day = 1; day <= daysInMonth; day++) {
            const date = new Date(year, month, day);
            date.setHours(0, 0, 0, 0);

            const dateStr = this.formatDate(date);
            const isToday = date.getTime() === today.getTime();
            const isStart = this.state.selectedStartDate && 
                           this.formatDate(this.state.selectedStartDate) === dateStr;
            const isEnd = this.state.selectedEndDate && 
                         this.formatDate(this.state.selectedEndDate) === dateStr;
            const isInRange = this.state.selectedStartDate && this.state.selectedEndDate &&
                             date > this.state.selectedStartDate && date < this.state.selectedEndDate;

            let classes = 'calendar-day';
            if (isToday) classes += ' today';
            if (isStart) classes += ' start-date';
            if (isEnd) classes += ' end-date';
            if (isInRange) classes += ' in-range';

            // Check availability
            const facility = this.state.facilities[0]; // Default to first facility
            if (facility && facility.availability && facility.availability[dateStr]) {
                const avail = facility.availability[dateStr];
                classes += ` ${avail.status}`;
            }

            html += `
                <div class="${classes}" data-date="${dateStr}">
                    ${day}
                    <div class="availability-indicator"></div>
                </div>
            `;
        }

        // Next month's days
        const remainingDays = 42 - (startingDayOfWeek + daysInMonth);
        for (let day = 1; day <= remainingDays; day++) {
            html += `<div class="calendar-day outside">${day}</div>`;
        }

        html += '</div>';

        return html;
    }

    renderTimeSlots() {
        if (!this.state.selectedStartDate) {
            return '<div class="time-slots-container" style="display:none;"></div>';
        }

        let html = `
            <div class="time-slots-container">
                <div class="time-slots-header">
                    <h3>Select Time</h3>
                </div>
                <div class="time-slots-scroll">
        `;

        this.state.slots.forEach(slot => {
            const isSelected = this.state.selectedTimeSlot && 
                             this.state.selectedTimeSlot.id === slot.id;
            const classes = `time-slot ${slot.status} ${isSelected ? 'selected' : ''} ${!slot.is_available ? 'unavailable' : ''}`;

            html += `
                <div class="${classes}" data-slot-id="${slot.id}" data-slot-price="${slot.price_per_hour}">
                    <div class="time-slot-time">${this.formatTime(slot.start_time)}</div>
                    <div class="time-slot-availability">${slot.available_quantity}/${slot.capacity}</div>
                    <div class="time-slot-price">$${parseFloat(slot.price_per_hour).toFixed(2)}</div>
                </div>
            `;
        });

        html += `
                </div>
            </div>
        `;

        return html;
    }

    renderBookingSummary() {
        if (!this.state.selectedStartDate) {
            return '<div class="booking-summary" style="display:none;"></div>';
        }

        const facility = this.state.facilities[0];
        const startStr = this.state.selectedStartDate.toLocaleDateString('en-US', {
            weekday: 'short',
            month: 'short',
            day: 'numeric'
        });
        const endStr = this.state.selectedEndDate ? 
            this.state.selectedEndDate.toLocaleDateString('en-US', {
                weekday: 'short',
                month: 'short',
                day: 'numeric'
            }) : startStr;

        const days = this.state.selectedEndDate ? 
            Math.ceil((this.state.selectedEndDate - this.state.selectedStartDate) / (1000 * 60 * 60 * 24)) + 1 : 1;

        const pricePerDay = facility ? facility.price_per_hour * 24 : 0;
        const totalPrice = pricePerDay * days;

        return `
            <div class="booking-summary">
                <h3>Booking Summary</h3>
                <div class="summary-item">
                    <span class="summary-label">Facility</span>
                    <span class="summary-value">${facility ? facility.name : 'Select facility'}</span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">Dates</span>
                    <span class="summary-value">${startStr} - ${endStr}</span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">Duration</span>
                    <span class="summary-value">${days} day${days > 1 ? 's' : ''}</span>
                </div>
                ${this.state.selectedTimeSlot ? `
                    <div class="summary-item">
                        <span class="summary-label">Time</span>
                        <span class="summary-value">${this.formatTime(this.state.selectedTimeSlot.start_time)}</span>
                    </div>
                ` : ''}
                <div class="summary-item">
                    <span class="summary-label">Total Price</span>
                    <span class="summary-value">$${totalPrice.toFixed(2)}</span>
                </div>
            </div>
        `;
    }

    attachEventListeners() {
        const container = document.getElementById(this.config.containerId);
        if (!container) return;

        // Calendar navigation
        const btnPrev = container.querySelector('.btn-prev');
        const btnNext = container.querySelector('.btn-next');

        if (btnPrev) {
            btnPrev.addEventListener('click', () => this.previousMonth());
        }
        if (btnNext) {
            btnNext.addEventListener('click', () => this.nextMonth());
        }

        // Calendar day selection
        const days = container.querySelectorAll('.calendar-day:not(.outside):not(.disabled)');
        days.forEach(day => {
            day.addEventListener('click', (e) => this.selectDate(e.target.dataset.date));
        });

        // Time slot selection
        const slots = container.querySelectorAll('.time-slot:not(.unavailable)');
        slots.forEach(slot => {
            slot.addEventListener('click', (e) => {
                const slotId = e.currentTarget.dataset.slotId;
                const slotPrice = e.currentTarget.dataset.slotPrice;
                this.selectTimeSlot({ id: slotId, price_per_hour: slotPrice });
            });
        });
    }

    selectDate(dateStr) {
        const date = new Date(dateStr);

        if (!this.state.selectedStartDate) {
            this.state.selectedStartDate = date;
            this.loadAvailabilityForDate(date);
        } else if (!this.state.selectedEndDate) {
            if (date < this.state.selectedStartDate) {
                this.state.selectedStartDate = date;
            } else {
                this.state.selectedEndDate = date;
            }
        } else {
            // Reset selection
            this.state.selectedStartDate = date;
            this.state.selectedEndDate = null;
            this.state.selectedTimeSlot = null;
            this.loadAvailabilityForDate(date);
        }

        this.render();
    }

    selectTimeSlot(slot) {
        this.state.selectedTimeSlot = slot;
        this.render();
    }

    previousMonth() {
        this.state.currentMonth.setMonth(this.state.currentMonth.getMonth() - 1);
        this.render();
    }

    nextMonth() {
        this.state.currentMonth.setMonth(this.state.currentMonth.getMonth() + 1);
        this.render();
    }

    formatDate(date) {
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
    }

    formatTime(time) {
        if (typeof time === 'string') {
            return time.substring(0, 5); // HH:mm
        }
        return time;
    }

    getSelectedData() {
        return {
            startDate: this.state.selectedStartDate ? this.formatDate(this.state.selectedStartDate) : null,
            endDate: this.state.selectedEndDate ? this.formatDate(this.state.selectedEndDate) : null,
            timeSlot: this.state.selectedTimeSlot,
            facilityId: this.state.selectedFacilityId,
        };
    }
}

// Initialize on document ready
document.addEventListener('DOMContentLoaded', () => {
    if (document.getElementById('luxury-calendar')) {
        window.luxuryCalendar = new LuxuryCalendar();
    }
});
