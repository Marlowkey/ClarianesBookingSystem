@extends('layouts.app')

@section('title', 'Booking')

@section('content')
<section class="site-hero overlay" data-stellar-background-ratio="0.5" style="background-image: url({{ asset('user/images/bg.png') }});">
    <div class="container">
        <div class="row align-items-center site-hero-inner justify-content-center">
            <div class="col-md-12 text-center">
                <div class="mb-5 element-animate">
                    <h1>Reservations</h1>
                    <p>Online, Anytime, Anywhere.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="site-section">
    <div class="container">
        <div class="row">
            <!-- Reservation Form -->
            <div class="col-md-6">
                <h2 class="mb-5 fw-bolder">Appointment Form</h2>
                <form action="{{ route('reservations.store') }}" method="POST" onsubmit="return validateDate()">
                    @csrf
                    <div class="row">
                        <div class="col-sm-6 form-group">
                            <label for="schedule_date" style="font-weight: bold">Schedule Date</label>
                            <div class="position-relative">
                                <input type="date" class="form-control border border-dark" id="schedule_date" name="schedule_date" placeholder="YYYY-MM-DD" required>
                            </div>
                        </div>

                        <div class="col-sm-6 form-group">
                            <label for="available_time_id" style="font-weight: bold">Time</label>
                            <div class="position-relative">
                                <select id="available_time_id" name="available_time_id" class="form-control border border-dark" required>
                                    <option value="" disabled selected>Select option...</option>
                                    @foreach($availableTimes as $time)
                                        <option value="{{ $time->id }}">{{ $time->time_slot }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label for="pName" style="font-weight: bold">Patient Name</label>
                            <input type="text" id="pName" name="patient_name" class="form-control border border-dark" placeholder="Enter patient's name" required>
                        </div>

                        <div class="col-md-6 form-group">
                            <label for="gName" style="font-weight: bold">Guardian Name</label>
                            <input type="text" id="gName" name="guardian_name" class="form-control border border-dark" placeholder="Enter guardian's name" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 form-group">
                            <label for="pNumber" style="font-weight: bold">Phone Number</label>
                            <input type="text" id="pNumber" name="phone_number" class="form-control border border-dark" placeholder="Enter phone number (ex. 09xxxxxxxxx)" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 form-group">
                            <label for="message" style="font-weight: bold">Write your reason</label>
                            <textarea id="message" name="message" class="form-control border border-dark" cols="30" rows="5" placeholder="Enter reason for appointment"></textarea>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 form-group">
                            <button type="submit" class="btn btn-primary">Book Now</button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="col-md-1"></div>
            <div class="col-md-5 mt-2">
                <h3 class="mb-3 fw-bolder text-muted">Input Appointment Reservation</h3>
                <div class="media d-block mb-0">
                    <figure class="text-center">
                        <img src="{{ asset('user/images/appointment.png') }}" alt="Contact Image" class="img-fluid img-fluid d-inline-block" style="max-width: 300px; height: auto;">
                    </figure>
                    <div class="media-body mt-2">
                        <p class="lead font-italic text-justify">"Get in touch with us today! Whether you have questions or need to schedule an appointment, we’re here to provide the care and support your child deserves."</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- END section -->

<script>
    function validateDate() {
        const dateInput = document.getElementById('schedule_date');
        const selectedDate = new Date(dateInput.value);
        const day = selectedDate.getUTCDay(); // 0 = Sunday, 1 = Monday, ..., 6 = Saturday

        if (day === 0) {
            alert("Sorry, bookings are not allowed on Sundays.");
            return false;
        }
        return true;
    }
</script>
@endsection
