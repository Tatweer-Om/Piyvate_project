<script>
    document.addEventListener('DOMContentLoaded', function () {
        const doctorId = document.getElementById('doctor_id').value;

        const calendarEl = document.getElementById('calendar');

        const calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            events: function(fetchInfo, successCallback, failureCallback) {
                // Use fetch() or $.ajax() to send doctor ID
                fetch(`/get-calendar-appointments`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ doctor_id: doctorId })
                })
                .then(response => response.json())
                .then(data => successCallback(data))
                .catch(error => failureCallback(error));
            },
            dateClick: function(info) {
                const date = info.dateStr;

                fetch(`/api/day-schedule/${date}?doctor_id=${doctorId}`)
                    .then(response => response.json())
                    .then(data => {
                        let html = `<h5>Schedules for ${date}</h5><ul>`;
                        if (data.length === 0) {
                            html += '<li>No schedules</li>';
                        } else {
                            data.forEach(slot => {
                                html += `<li>${slot.time_from || slot.session_time}}</li>`;
                            });
                        }
                        html += '</ul>';
                        alert(html); // You can use a modal here
                    });
            }
        });

        calendar.render();
    });
    </script>
