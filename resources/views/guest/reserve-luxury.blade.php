@extends('layouts.app')

@section('content')
    <style>
        :root {
            --sure-yellow: #FFC107;
            --sure-dark: #1a1a1a;
            --sure-light: #f2f2f2;
            --sure-blue: #1d00ff;
            --sure-gray: #6b7280;
            --sure-border: #e5e7eb;
        }

        .reserve-container {
            background-color: #f9fafb;
            min-height: 100vh;
            padding: 2rem 0;
        }

        .reserve-header {
            background: linear-gradient(135deg, var(--sure-dark) 0%, #242424 50%, var(--sure-blue) 100%);
            border-radius: 16px;
            padding: 2rem;
            color: white;
            margin-bottom: 2rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .reserve-header h1 {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            font-family: 'JetBrains Mono', monospace;
        }

        .reserve-header p {
            font-size: 1rem;
            color: rgba(255, 255, 255, 0.85);
            margin: 0;
        }

        .section-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            border: 1px solid var(--sure-border);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .section-header {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid var(--sure-light);
        }

        .section-number {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--sure-yellow) 0%, #FFB300 100%);
            color: var(--sure-dark);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 1.1rem;
            flex-shrink: 0;
        }

        .section-header h2 {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--sure-dark);
            margin: 0;
            font-family: 'JetBrains Mono', monospace;
        }

        .facility-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .facility-option {
            position: relative;
            border: 2px solid var(--sure-border);
            border-radius: 12px;
            padding: 1.25rem;
            cursor: pointer;
            transition: all 0.3s ease;
            background: white;
        }

        .facility-option:hover {
            border-color: var(--sure-yellow);
            box-shadow: 0 4px 12px rgba(255, 193, 7, 0.2);
            transform: translateY(-2px);
        }

        .facility-option input[type="radio"] {
            display: none;
        }

        .facility-option input[type="radio"]:checked + .facility-content {
            color: var(--sure-dark);
        }

        .facility-option input[type="radio"]:checked ~ .facility-check {
            display: flex;
            background: linear-gradient(135deg, var(--sure-yellow) 0%, #FFB300 100%);
            border-color: var(--sure-yellow);
        }

        .facility-option input[type="radio"]:checked {
            border-color: var(--sure-yellow);
        }

        .facility-option.checked {
            border-color: var(--sure-yellow);
            background: linear-gradient(135deg, rgba(255, 193, 7, 0.05) 0%, rgba(29, 0, 255, 0.02) 100%);
        }

        .facility-check {
            position: absolute;
            top: 1rem;
            right: 1rem;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            border: 2px solid var(--sure-border);
            display: none;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            transition: all 0.3s ease;
        }

        .facility-content {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }

        .facility-name {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--sure-dark);
        }

        .facility-info {
            font-size: 0.9rem;
            color: var(--sure-gray);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .facility-price {
            font-size: 1rem;
            font-weight: 700;
            color: var(--sure-yellow);
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            font-size: 0.85rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: var(--sure-dark);
            margin-bottom: 0.5rem;
        }

        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid var(--sure-border);
            border-radius: 8px;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            font-family: inherit;
        }

        .form-group input:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: var(--sure-yellow);
            box-shadow: 0 0 0 3px rgba(255, 193, 7, 0.1);
        }

        .form-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
        }

        .availability-status {
            padding: 1rem;
            border-radius: 8px;
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-top: 1rem;
            animation: slideIn 0.3s ease-out;
        }

        .availability-status.hidden {
            display: none;
        }

        .availability-status.checking {
            background: #f0f9ff;
            border: 1px solid #bfdbfe;
            color: #1e40af;
        }

        .availability-status.available {
            background: #ecfdf5;
            border: 1px solid #d1fae5;
            color: #065f46;
        }

        .availability-status.unavailable {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #7f1d1d;
        }

        .availability-status.pending {
            background: #fffbeb;
            border: 1px solid #fcd34d;
            color: #92400e;
        }

        .status-icon {
            width: 24px;
            height: 24px;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .status-icon svg {
            width: 100%;
            height: 100%;
        }

        .status-text {
            font-size: 0.95rem;
            font-weight: 500;
        }

        .booking-summary {
            background: linear-gradient(135deg, var(--sure-dark) 0%, #242424 100%);
            border-radius: 12px;
            padding: 1.5rem;
            color: white;
            position: sticky;
            top: 2rem;
        }

        .booking-summary h3 {
            font-size: 1.25rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            font-family: 'JetBrains Mono', monospace;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            font-size: 0.95rem;
            color: rgba(255, 255, 255, 0.8);
        }

        .summary-row:last-child {
            border-bottom: none;
        }

        .summary-row.total {
            border-top: 1px solid rgba(255, 255, 255, 0.2);
            border-bottom: none;
            margin-top: 1rem;
            padding-top: 1rem;
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--sure-yellow);
        }

        .summary-label {
            color: rgba(255, 255, 255, 0.7);
        }

        .summary-value {
            color: white;
            font-weight: 600;
        }

        .btn-submit {
            width: 100%;
            padding: 1rem;
            background: linear-gradient(135deg, var(--sure-yellow) 0%, #FFB300 100%);
            color: var(--sure-dark);
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 1.5rem;
            box-shadow: 0 4px 12px rgba(255, 193, 7, 0.3);
        }

        .btn-submit:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(255, 193, 7, 0.4);
        }

        .btn-submit:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .policy-card {
            background: #eff6ff;
            border: 1px solid #bfdbfe;
            border-radius: 8px;
            padding: 1rem;
            margin-top: 1rem;
            font-size: 0.85rem;
            color: #1e40af;
        }

        .policy-card strong {
            display: block;
            margin-bottom: 0.5rem;
        }

        .error-alert {
            background: #fef2f2;
            border-left: 4px solid #dc2626;
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        @keyframes slideIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @media (max-width: 768px) {
            .reserve-header {
                padding: 1.5rem;
            }
            .reserve-header h1 {
                font-size: 1.5rem;
            }
            .booking-summary {
                margin-top: 2rem;
                position: static;
            }
        }
    </style>

    <div class="reserve-container">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <!-- Header -->
                    <div class="reserve-header">
                        <h1>Facility Reservation</h1>
                        <p>Secure your space for meetings, events, or recreational activities.</p>
                    </div>

                    @if($errors->any())
                        <div class="error-alert">
                            <div class="status-icon" style="color: #dc2626;">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="status-text" style="color: #991b1b;">
                                @foreach($errors->all() as $error)
                                    <div>{{ $error }}</div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <form action="{{ route('guest.reserve.store') }}" method="POST" id="reserveForm">
                        @csrf
                        
                        <!-- Step 1: Venue -->
                        <div class="section-card">
                            <div class="section-header">
                                <div class="section-number">1</div>
                                <h2>Select Venue</h2>
                            </div>

                            <div class="facility-grid">
                                @foreach($facilities as $facility)
                                    <label class="facility-option" id="facility-label-{{ $facility->id }}">
                                        <input type="radio" name="facility_id" value="{{ $facility->id }}" 
                                               data-name="{{ $facility->name }}" 
                                               data-price="{{ $facility->price }}"
                                               required onchange="updateSummary()">
                                        <div class="facility-content">
                                            <div class="facility-name">{{ $facility->name }}</div>
                                            <div class="facility-info">
                                                <i class="bi bi-people"></i>
                                                <span>Capacity: {{ $facility->capacity }}</span>
                                            </div>
                                            <div class="facility-price">₱{{ number_format($facility->price, 2) }}</div>
                                        </div>
                                        <div class="facility-check">
                                            <i class="bi bi-check-lg"></i>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <!-- Step 2: Schedule -->
                        <div class="section-card">
                            <div class="section-header">
                                <div class="section-number">2</div>
                                <h2>Booking Schedule</h2>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label>Start Date</label>
                                    <input type="date" name="start_date" id="start_date" 
                                           min="{{ date('Y-m-d') }}" required onchange="checkAvailability()">
                                </div>
                                <div class="form-group">
                                    <label>End Date</label>
                                    <input type="date" name="end_date" id="end_date" 
                                           min="{{ date('Y-m-d') }}" required onchange="checkAvailability()">
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label>Start Time</label>
                                    <input type="time" name="start_time" id="start_time" required onchange="checkAvailability()">
                                </div>
                                <div class="form-group">
                                    <label>End Time</label>
                                    <input type="time" name="end_time" id="end_time" required onchange="checkAvailability()">
                                </div>
                            </div>

                            <div id="availability-status" class="availability-status hidden">
                                <div class="status-icon"></div>
                                <div class="status-text"></div>
                            </div>
                        </div>

                        <!-- Step 3: Details -->
                        <div class="section-card">
                            <div class="section-header">
                                <div class="section-number">3</div>
                                <h2>Additional Details</h2>
                            </div>

                            <div class="form-group">
                                <label>Number of People</label>
                                <input type="number" name="number_of_people" min="1" required placeholder="How many attendees?">
                            </div>

                            <div class="form-group">
                                <label>Purpose of Reservation</label>
                                <textarea name="reason" rows="4" required placeholder="Briefly describe your event or activity..."></textarea>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Sidebar Summary -->
                <div class="col-lg-4">
                    <div class="booking-summary">
                        <h3>Booking Summary</h3>
                        
                        <div class="summary-row">
                            <span class="summary-label">Venue</span>
                            <span class="summary-value" id="summary-venue">Not selected</span>
                        </div>
                        
                        <div class="summary-row">
                            <span class="summary-label">Date</span>
                            <span class="summary-value" id="summary-date">Not selected</span>
                        </div>
                        
                        <div class="summary-row">
                            <span class="summary-label">Time Slot</span>
                            <span class="summary-value" id="summary-time">Not selected</span>
                        </div>
                        
                        <div class="summary-row total">
                            <span class="summary-label">Total Amount</span>
                            <span class="summary-value" id="summary-total">₱0.00</span>
                        </div>

                        <button type="submit" form="reserveForm" class="btn-submit" id="submit-btn" disabled>
                            Confirm Reservation
                        </button>

                        <div class="policy-card">
                            <strong>Reservation Policy</strong>
                            All requests are subject to administrative approval. You will be notified via email once your request is processed.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function updateSummary() {
            const selectedFacility = document.querySelector('input[name="facility_id"]:checked');
            
            // Update UI for all options
            document.querySelectorAll('.facility-option').forEach(opt => opt.classList.remove('checked'));
            
            if (selectedFacility) {
                const label = document.getElementById(`facility-label-${selectedFacility.value}`);
                label.classList.add('checked');
                
                document.getElementById('summary-venue').textContent = selectedFacility.dataset.name;
                document.getElementById('summary-total').textContent = `₱${parseFloat(selectedFacility.dataset.price).toLocaleString(undefined, {minimumFractionDigits: 2})}`;
                
                checkAvailability();
            }
        }

        function checkAvailability() {
            const facilityId = document.querySelector('input[name="facility_id"]:checked')?.value;
            const startDate = document.getElementById('start_date').value;
            const endDate = document.getElementById('end_date').value;
            const startTime = document.getElementById('start_time').value;
            const endTime = document.getElementById('end_time').value;
            
            const statusDiv = document.getElementById('availability-status');
            const statusText = statusDiv.querySelector('.status-text');
            const statusIcon = statusDiv.querySelector('.status-icon');
            const submitBtn = document.getElementById('submit-btn');

            // Update date/time summary
            if (startDate && endDate) {
                document.getElementById('summary-date').textContent = startDate === endDate ? startDate : `${startDate} to ${endDate}`;
            }
            if (startTime && endTime) {
                document.getElementById('summary-time').textContent = `${startTime} - ${endTime}`;
            }

            if (!facilityId || !startDate || !endDate || !startTime || !endTime) return;

            statusDiv.className = 'availability-status checking';
            statusDiv.classList.remove('hidden');
            statusText.textContent = 'Checking availability...';
            statusIcon.innerHTML = '<svg class="animate-spin" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none" style="opacity: 0.25;"></circle><path fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';
            submitBtn.disabled = true;

            fetch(`/guest/check-availability?facility_id=${facilityId}&start_date=${startDate}&end_date=${endDate}&start_time=${startTime}&end_time=${endTime}`)
                .then(response => response.json())
                .then(data => {
                    statusDiv.className = 'availability-status ' + data.status;
                    statusText.textContent = data.message;
                    
                    if (data.status === 'available') {
                        statusIcon.innerHTML = '<svg fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>';
                        submitBtn.disabled = false;
                    } else {
                        statusIcon.innerHTML = '<svg fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>';
                        submitBtn.disabled = true;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    statusDiv.className = 'availability-status unavailable';
                    statusText.textContent = 'Error checking availability. Please try again.';
                });
        }
    </script>
@endsection
