// Fungsi untuk menampilkan kalender
function showCalendar(month, year) {
    const monthYearElement = document.getElementById("month-year");
    const calendarBody = document.getElementById("calendar-body");
  
    let currentDate = new Date(year, month, 1);
    let currentMonth = currentDate.getMonth();
    let currentYear = currentDate.getFullYear();
  
    function getMonthName(month) {
      const months = [
        "Januari", "Februari", "Maret", "April", "Mei", "Juni",
        "Juli", "Agustus", "September", "Oktober", "November", "Desember"
      ];
      return months[month];
    }
  
    function prevMonth() {
      currentYear = (currentMonth === 0) ? currentYear - 1 : currentYear;
      currentMonth = (currentMonth === 0) ? 11 : currentMonth - 1;
      updateCalendar();
    }
  
    function nextMonth() {
      currentYear = (currentMonth === 11) ? currentYear + 1 : currentYear;
      currentMonth = (currentMonth + 1) % 12;
      updateCalendar();
    }
  
    function updateCalendar() {
      currentDate = new Date(currentYear, currentMonth, 1);
      currentMonth = currentDate.getMonth();
      currentYear = currentDate.getFullYear();
      showCalendar(currentMonth, currentYear);
    }
  
    monthYearElement.textContent = `${getMonthName(currentMonth)} ${currentYear}`;
  
    let firstDay = new Date(currentYear, currentMonth, 1);
    let lastDay = new Date(currentYear, currentMonth + 1, 0);
  
    let day = 1;
    calendarBody.innerHTML = '';
  
    const datesWithEvents = [3]; // Contoh tanggal dengan kegiatan
  
    for (let i = 0; i < 6; i++) {
      for (let j = 0; j < 7; j++) {
        if (i === 0 && j < firstDay.getDay()) {
          const emptyDay = document.createElement("div");
          emptyDay.classList.add("calendar-day", "empty");
          calendarBody.appendChild(emptyDay);
        } else if (day > lastDay.getDate()) {
          break;
        } else {
          const calendarDay = document.createElement("div");
          calendarDay.classList.add("calendar-day");
          calendarDay.textContent = day;
  
          // Tambahkan kelas 'has-event' ke tanggal dengan kegiatan
          if (datesWithEvents.includes(day)) {
            calendarDay.classList.add("has-event");
          }
  
          // Tambahkan logika untuk menandai tanggal dengan dua kegiatan
          if (datesWithEvents.filter(date => date === day).length > 1) {
            calendarDay.classList.add("double-event");
          }
  
          // Tambahkan kegiatan pada tanggal tertentu
          calendarDay.addEventListener("click", function() {
            const event = prompt(`Tambahkan kegiatan untuk tanggal ${day}/${currentMonth + 1}/${currentYear}:`);
            if (event) {
              const eventDetails = document.getElementById("event-details");
              const eventInfo = document.createElement("div");
              eventInfo.classList.add("event-info");
              eventInfo.innerHTML = `
                <strong>Tanggal:</strong> ${day}/${currentMonth + 1}/${currentYear}<br>
                <strong>Kegiatan:</strong> ${event}<br>
                <strong>Pengirim:</strong> John Doe<br>
                <strong>Nama Dosen:</strong> Prof. Smith<br>
                <strong>Waktu:</strong> 09:00
              `;
              eventDetails.appendChild(eventInfo);
            }

            const selectedDate = `${currentYear}-${currentMonth + 1}-${day}`;

            // Melakukan permintaan AJAX ke skrip PHP atau endpoint API Anda
            fetch(`koneksi.php?date=${selectedDate}`) // Ganti script.php dengan endpoint yang sesuai di backend Anda
              .then(response => response.json())
              .then(data => {
                // Menampilkan data kegiatan dalam event-details
                const eventDetails = document.getElementById("event-details");
                eventDetails.innerHTML = ''; // Mengosongkan event-details sebelum menambahkan info baru
          
                if (data.length > 0) {
                  data.forEach(event => {
                    const eventInfo = document.createElement("div");
                    eventInfo.classList.add("event-info");
                    eventInfo.innerHTML = `
                      <strong>Tanggal:</strong> ${event.tanggal}<br>
                      <strong>Kegiatan:</strong> ${event.kegiatan}<br>
                      <strong>Pengirim:</strong> ${event.pengirim}<br>
                      <strong>Nama Dosen:</strong> ${event.nama_dosen}<br>
                      <strong>Waktu:</strong> ${event.waktu}
                    `;
                    eventDetails.appendChild(eventInfo);
                  });
                } else {
                  const noEvent = document.createElement("div");
                  noEvent.textContent = "Tidak ada kegiatan untuk tanggal ini.";
                  eventDetails.appendChild(noEvent);
                }
              })
              .catch(error => {
                console.error('Error:', error);
              });

          });
  
          calendarBody.appendChild(calendarDay);
          day++;
        }
      }
    }
  }
  
  // Panggil fungsi showCalendar untuk menampilkan kalender pertama kali
  const currentDate = new Date();
  const currentMonth = currentDate.getMonth();
  const currentYear = currentDate.getFullYear();
  showCalendar(currentMonth, currentYear);
  